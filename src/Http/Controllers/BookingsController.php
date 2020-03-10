<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\Booking;
use glorifiedking\BusTravel\BookingCustomField;
use glorifiedking\BusTravel\BookingsField;
use glorifiedking\BusTravel\Bus;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\RoutesDepartureTime;
use glorifiedking\BusTravel\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use PDF;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\EscposImage;


class BookingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
    }

    //fetching buses route('bustravel.buses')
    public function index()
    {
      if(auth()->user()->hasAnyRole('BT Administrator'))
        {
         $routes_ids =Route::where('operator_id',auth()->user()->operator_id)->pluck('id')->all();
         $times_ids =RoutesDepartureTime::whereIn('route_id',$routes_ids)->pluck('id')->all();
         $bookings = Booking::whereIn('routes_departure_time_id',$times_ids)->orderBy('id', 'DESC')->get();
        }
      else
        {
           $bookings = Booking::orderBy('id', 'DESC')->get();
        }


        return view('bustravel::backend.bookings.index', compact('bookings'));
    }

    //creating buses form route('bustravel.buses.create')
    public function create()
    {
        $routes_ids =Route::where('operator_id',auth()->user()->operator_id)->pluck('id')->all();
        $routes_times =RoutesDepartureTime::whereIn('route_id',$routes_ids)->get();
        $users = User::all();
        $custom_fields = BookingCustomField::where('operator_id',auth()->user()->operator_id)->where('status', 1)->orderBy('field_order', 'ASC')->get();

        return view('bustravel::backend.bookings.create', compact('users', 'routes_times', 'custom_fields'));
    }

    // saving a new route departure times in the database  route('bustravel.routes.departures.store')
    public function store(Request $request)
    {
        //validation
        $validation = request()->validate(Booking::$rules);
        $route = RoutesDepartureTime::find(request()->input('routes_departure_time_id'));
        //saving to the database
        $booking = new Booking();
        $booking->routes_departure_time_id = request()->input('routes_departure_time_id');
        $booking->amount = request()->input('amount');
        $booking->date_paid = request()->input('date_paid') ?? null;
        $booking->date_of_travel = request()->input('date_of_travel') ?? null;
        $booking->time_of_travel = $route->departure_time;
        $booking->ticket_number = Str::random(5);
        $booking->user_id = request()->input('user_id');
        $booking->status = request()->input('status');
        $booking->save();

        $fields_values = request()->input('field_value') ?? 0;
        $fields_id = request()->input('field_id') ?? 0;

        if ($fields_values != 0) {
            foreach ($fields_values as $index =>  $fields_value) {
                $custom_fields = new BookingsField();
                $custom_fields->booking_id = $booking->id;
                $custom_fields->field_id = $fields_id[$index];
                $custom_fields->field_value = $fields_values[$index];
                $custom_fields->save();
            }
        }

        $alerts = [
        'bustravel-flash'         => true,
        'bustravel-flash-type'    => 'success',
        'bustravel-flash-title'   => 'Booking Saving',
        'bustravel-flash-message' => 'Booking has successfully been saved',
    ];
       
        $bus_reg_no = "RW 123a";  //GET         
        $departure_station = $booking->route_departure_time->route->start->name;
        $arrival_station = $booking->route_departure_time->route->end->name;
        $arrival_time = "00:00:00"; //get
        $connector = new DummyPrintConnector();
        $profile = CapabilityProfile::load("simple");
        $printer = new Printer($connector);  
        $operator_logo = EscposImage::load("ritco_black.jpg",false);
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer->bitImage($operator_logo);
        $printer->text("\n");      
        //$printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
        $printer->text("Bus Travel Ticket \n");
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer->selectPrintMode();
        $printer->text("\n");
        $printer->text("Ticket No: ".$booking->ticket_number."\n");
        $printer->text("Date of Travel: ".$booking->date_of_travel." ".$booking->time_of_travel."\n");
        $printer->text("Bus Reg No: ".$bus_reg_no."\n");
        $printer->text("Price: ".$booking->amount."\n");
        $printer->text("FROM: ".$departure_station."\n");
        $printer->text("TO: ".$arrival_station."\n");
        $printer->text("Arriving at: ".$arrival_time."\n");
        $printer->text("\n");
        $printer->text("\n");

        //add custom fields to receipt
        if ($fields_values != 0) {
            foreach ($fields_values as $index =>  $fields_value) {
                $custom_field = BookingCustomField::where([
                    ['operator_id','=',auth()->user()->operator_id],
                    ['id',$fields_id[$index]],
                ])->first();
                $printer->text($custom_field->field_name.":".$fields_values[$index]."\n");
                
            }
        }
        $printer->text("\n");
        $printer->text("\n");
        $printer->text("\n");
        $printer->text("\n");
        $printer->barcode("12365418568",Printer::BARCODE_UPCA);
    //$printer->qrcode($booking->ticket_number/*,Printer::QR_ECLEVEL_M,10,Printer::QR_MODEL_2*/);
        $printer->text("\n");
        $printer->text("Powered by PalmKash \n");
        $printer->text("www.transport.palmkash.com \n");
        $printer->text("\n");
        $printer->cut();
        //dd(base64_encode($connector->getData()));
        return redirect()->away('rawbt:base64,'.base64_encode($connector->getData()));
        return redirect()->route('bustravel.bookings')->with($alerts);
    }

    //Bus Edit form route('bustravel.buses.edit')
    public function edit($id)
    {
        $routes_times = RoutesDepartureTime::all();
        $users = User::all();
        $booking_fields = BookingsField::where('booking_id', $id)->get();
        $booking_fields_ids = BookingsField::where('booking_id', $id)->pluck('field_id')->all();
        $custom_fields = BookingCustomField::where('status', 1)->orderBy('field_order', 'ASC')->get();
        $booking = Booking::find($id);
        if (is_null($booking)) {
            return Redirect::route('bustravel.bookings');
        }

        return view('bustravel::backend.bookings.edit', compact('booking_fields_ids', 'booking_fields', 'booking', 'users', 'routes_times', 'custom_fields'));
    }

    //Update Operator route('bustravel.operators.upadate')
    public function update($id, Request $request)
    {
        //validation
        $validation = request()->validate(Booking::$rules);
        //saving to the database
        $booking = Booking::find($id);
        $booking->routes_departure_time_id = request()->input('routes_departure_time_id');
        $booking->amount = request()->input('amount');
        $booking->date_paid = request()->input('date_paid') ?? null;
        $booking->date_of_travel = request()->input('date_of_travel') ?? null;
        $booking->time_of_travel = $booking->time_of_travel;
        $booking->ticket_number = $booking->ticket_number;
        $booking->user_id = request()->input('user_id');
        $booking->status = request()->input('status');
        $booking->save();

        $fields_values = request()->input('field_value') ?? 0;
        $fields_id = request()->input('field_id') ?? 0;
        $fields_empty = BookingsField::where('booking_id', $id)->delete();
        if ($fields_values != 0) {
            foreach ($fields_values as $index =>  $fields_value) {
                $custom_fields = new BookingsField();
                $custom_fields->booking_id = $booking->id;
                $custom_fields->field_id = $fields_id[$index];
                $custom_fields->field_value = $fields_values[$index];
                $custom_fields->save();
            }
        }

        $alerts = [
        'bustravel-flash'         => true,
        'bustravel-flash-type'    => 'success',
        'bustravel-flash-title'   => 'Booking Updating',
        'bustravel-flash-message' => 'Booking has successfully been updated',
    ];

        return redirect()->route('bustravel.bookings.edit', $id)->with($alerts);
    }

    //Delete Route Departure Times
    public function delete($id)
    {
        $booking = Booking::find($id);
        $name = $booking->ticket_number;
        $booking->delete();
        $alerts = [
            'bustravel-flash'         => true,
            'bustravel-flash-type'    => 'error',
            'bustravel-flash-title'   => 'Booking Deleting ',
            'bustravel-flash-message' => 'Booking '.$name.' has successfully been Deleted',
        ];

        return Redirect::route('bustravel.bookings')->with($alerts);
    }
}
