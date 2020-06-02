<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use glorifiedking\BusTravel\Booking;
use glorifiedking\BusTravel\Driver;
use glorifiedking\BusTravel\BookingCustomField;
use glorifiedking\BusTravel\BookingsField;
use glorifiedking\BusTravel\Station;
use glorifiedking\BusTravel\RoutesStopoversDepartureTime;
use glorifiedking\BusTravel\Bus;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\StopoverStation;
use glorifiedking\BusTravel\Printer as BusTravelPrinter;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\RouteTracking;
use glorifiedking\BusTravel\RoutesDepartureTime;
use glorifiedking\BusTravel\ToastNotification;
use glorifiedking\BusTravel\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use PDF;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\EscposImage;


class BookingsController extends Controller
{
    public $travel_date='date_of_travel',
    $on_board='On Board',
    $route_manifest='bustravel.bookings.route.manifest';
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('can:View BT Bookings')->only('index');
        $this->middleware('can:Create BT Bookings')->only('create','store','edit','update','delete');
        $this->middleware('can:View BT Driver Manifest')->only('manifest','route_manifest','boarded');

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
        elseif(auth()->user()->hasAnyRole('BT Cashier'))
        {
         $bookings = Booking::where('user_id',auth()->user()->id)->orderBy('id', 'DESC')->get();
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
        $operator_id = Auth::user()->operator_id ?? 0;


        $my_workstation_id = Auth::user()->workstation ?? 0;
        $workstation = Station::find($my_workstation_id);
        $printers = BusTravelPrinter::where('operator_id',$operator_id)->get();
        $stations = Station::all();

        $custom_fields = BookingCustomField::where('operator_id',auth()->user()->operator_id)->where('status', 1)->orderBy('field_order', 'ASC')->get();

        return view('bustravel::backend.bookings.create', compact('workstation', 'stations', 'custom_fields','printers'));
    }

    // saving a new route departure times in the database  route('bustravel.routes.departures.store')
    public function store(Request $request)
    {
        //validation
        $validation = $request->validate([
            'route_id' => 'required',
            'route_type' => 'required',
            'amount'     => 'required',
            'printer'    =>   'required',
        ]);
        $route_id = $request->route_id;
        $route_type = $request->route_type;
        $payment_method = $request->payment_method;

        $departure_time = ($route_type == 'main_route') ? RoutesDepartureTime::find($route_id) : RoutesStopoversDepartureTime::find($route_id);

        $operator = $departure_time->route->operator ?? $departure_time->route->route->operator;

        if($payment_method == 'cash')
        {
            $pad_length = 6;
            $pad_char = 0;
            $str_type = 'd'; // treats input as integer, and outputs as a (signed) decimal number
            $pad_format = "%{$pad_char}{$pad_length}{$str_type}"; // or "%04d"
            $booking = new Booking();
            $booking->routes_departure_time_id = $departure_time->id;
            $booking->amount = $departure_time->route->price;
            $booking->date_paid = date('Y-m-d');
            $booking->date_of_travel = date('Y-m-d');
            $booking->time_of_travel = $departure_time->departure_time;
            $ticket_number = $operator->code.date('y').sprintf($pad_format, $booking->getNextId());
            $booking->ticket_number = $ticket_number;
            $booking->user_id = Auth::user()->id;
            $booking->status = '1';
            $booking->route_type = $route_type;
            $booking->payment_source = 'cash';;
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


            // try printing
            $bus_reg_no = $departure_time->bus->number_plate ?? $departure_time->main_route_departure_time->bus->number_plate ?? "NONE";  //GET
            $departure_station = $booking->route_departure_time->route->start->name ?? $booking->stop_over_route_departure_time->route->start->name;
            $arrival_station = $booking->route_departure_time->route->end->name ?? $booking->stop_over_route_departure_time->route->end->name;
            $arrival_time = $booking->route_departure_time->arrival_time ?? $booking->stop_over_route_departure_time->arrival_time; //get
            $selected_printer_id = $request->printer;
            $selected_printer = BusTravelPrinter::find($selected_printer_id);
            if($selected_printer->printer_url == 'rawbt:base64')
            {
                try
                {

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
                    $printer->barcode($booking->ticket_number,Printer::BARCODE_CODE39);
                //$printer->qrcode($booking->ticket_number/*,Printer::QR_ECLEVEL_M,10,Printer::QR_MODEL_2*/);
                    $printer->text("\n");
                    $printer->text("Powered by PalmKash \n");
                    $printer->text("www.transport.palmkash.com \n");
                    $printer->text("\n");
                    $printer->cut();
                    //dd(base64_encode($connector->getData()));
                    return redirect()->away('rawbt:base64,'.base64_encode($connector->getData()));

                }
                catch(\Exception $e)
                {
                    $error = "Booking saved but printing failed, check printer or printing options: ".$e->getMessage()."";
                    return redirect()->route('bustravel.bookings.create')->with(ToastNotification::toast("Error Printing: $error",'Error Printing','error'));
                }
            }
            else if($selected_printer->printer_url != 'rawbt:base64')
            {
                // arrange the ticket
                $print_output = "";
                $print_output .= "Bus Travel Ticket \n";
                $print_output .= "\n";
                $print_output.= "Ticket No: ".$booking->ticket_number."\n";
                $print_output .= "Date of Travel: ".$booking->date_of_travel." ".$booking->time_of_travel."\n";
                $print_output .= "Bus Reg No: ".$bus_reg_no."\n";
                $print_output .= "Price: ".$booking->amount."\n";
                $print_output .= "FROM: ".$departure_station."\n";
                $print_output .= "TO: ".$arrival_station."\n";
                $print_output .= "Arriving at: ".$arrival_time."\n";
                $print_output .= "\n";
                $print_output .= "\n";

                    //add custom fields to receipt
                    if ($fields_values != 0) {
                        foreach ($fields_values as $index =>  $fields_value) {
                            $custom_field = BookingCustomField::where([
                                ['operator_id','=',auth()->user()->operator_id],
                                ['id',$fields_id[$index]],
                            ])->first();
                            $print_output .= $custom_field->field_name.":".$fields_values[$index]."\n";

                        }
                    }
                    $print_output .= "\n";
                    $print_output .= "\n";
                    $print_output .= "\n";
                    $print_output .= "\n";
                    $print_output .= "\n";
                    $print_output .= "Powered by PalmKash \n";
                    $print_output .= "www.transport.palmkash.com \n";
                //network printer
                try
                {
                    $fp = pfsockopen($selected_printer->printer_url,$selected_printer->printer_port);
                    fputs($fp, $print_output);
                    fclose($fp);
                }
                catch(\Exception $e)
                {
                    $error = "Booking Made but Failed to print! Check printer or print options";
                    return redirect()->route('bustravel.bookings.create')->with(ToastNotification::toast($error,'Error Printing','error'));
                }
            }


        }
        else if($payment_method == 'palm_kash')
        {
            // connect to api
            return redirect()->route('bustravel.bookings.create')->with(ToastNotification::toast('Payment with Palm Kash successful'));
        }

       // return redirect()->route('bustravel.bookings')->with($alerts);
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

        //saving to the database
        $booking = Booking::find($id);
        $booking->amount = request()->input('amount');
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
        return redirect()->route('bustravel.bookings.edit', $id)->with(ToastNotification::toast('Booking has successfully been updated','Booking Updating'));
    }

    //Delete Route Departure Times
    public function delete($id)
    {
        $booking = Booking::find($id);
        $name = $booking->ticket_number;
        $booking->delete();
        return Redirect::route('bustravel.bookings')->with(ToastNotification::toast($name.' has successfully been Deleted','Booking Deleted','error'));
    }

  public function manifest()
  {
    $travel_day_of_week = date('l');
    if(auth()->user()->hasAnyRole('BT Driver'))
      {
       $driver =Driver::where('user_id',auth()->user()->id)->first();
       $routes_ids =Route::where('operator_id',auth()->user()->operator_id)->pluck('id')->all();
       $times_ids =RoutesDepartureTime::whereIn('route_id',$routes_ids)->pluck('id')->all();
       $driver_routes= RoutesDepartureTime::whereIn('route_id',$routes_ids)->where('driver_id',$driver->id)
       ->where('days_of_week', 'like', "%$travel_day_of_week%")->get();
       $bookings = Booking::whereIn('routes_departure_time_id',$times_ids)->orderBy('id', 'DESC')->get();
      }
    else
      {
        $driver =Driver::where('user_id',auth()->user()->id)->first();
        $routes_ids =Route::where('operator_id',auth()->user()->operator_id)->pluck('id')->all();
        $times_ids =RoutesDepartureTime::whereIn('route_id',$routes_ids)->pluck('id')->all();
        $driver_routes= RoutesDepartureTime::where('days_of_week', 'like', "%$travel_day_of_week%")->get();
        $bookings = Booking::orderBy('id', 'DESC')->get();

      }


      return view('bustravel::backend.bookings.manifest', compact('bookings','driver_routes','driver'));
  }
  public function route_manifest($id)
  {
      $today =date('Y-m-d');
      if(request()->isMethod('post'))
      {
          $validation = request()->validate([
            'ticket' => 'required',
          ]);
          $driver =Driver::where('user_id',auth()->user()->id)->first();
          $ticket = request()->input('ticket');
          $bookings = Booking::where('ticket_number',$ticket)->get();

      }
      else
      {
          $driver =Driver::where('user_id',auth()->user()->id)->first();
          $bookings = Booking::where('routes_departure_time_id',$id)->where($this->travel_date,$today)->orderBy('id', 'DESC')->get();
          $ticket ="";
      }

      $tracking=RouteTracking::where('routes_times_id',$id)->where($this->travel_date,$today)->first();
      $times_id =RoutesDepartureTime::find($id);
      $not_booked =$times_id->bus->seating_capacity -$bookings->count();
      $onboard_tickets = Booking::where('routes_departure_time_id',$id)->where($this->travel_date,$today)->where('boarded',1)->count();
      $notonboard_tickets = Booking::where('routes_departure_time_id',$id)->where($this->travel_date,$today)->where('boarded',0)->count();

      return view('bustravel::backend.bookings.routemanifest', compact('bookings','driver','route_id','ticket','onboard_tickets','notonboard_tickets','not_booked','times_id','tracking'));
  }

  public function boarded($id)
  {
    $booking =Booking::find($id);
    $booking->boarded=1;
    $booking->save();
     return redirect()->route($this->route_manifest,$booking->routes_departure_time_id)->with(ToastNotification::toast($booking->ticket_number.' marked Onboard',$this->on_board));

  }
  public function route_tracking($id)
  {
    $today =date('Y-m-d');
    $travel_time = date('H:i');
    $route =RoutesDepartureTime::find($id);
    $bus_seating_capacity=$route->bus->seating_capacity??null;
    if(is_null($bus_seating_capacity))
    {
     return redirect()->route('bustravel.bookings.manifest')->with(ToastNotification::toast('No Bus Assigned to this Route','Route Tracking','error'));
    }

    $tracking= RouteTracking::where($this->travel_date,$today)->first();
    if(is_null($tracking))
    {
      if($travel_time> $route->departure_time)
      {
       return redirect()->route('bustravel.bookings.manifest')->with(ToastNotification::toast('Departure Time has already elapsed','Route Tracking','error'));
      }
     $tracking =new RouteTracking;
     $tracking->routes_times_id =$route->id;
     $tracking->driver_id =$route->driver_id;
     $tracking->bus_id =$route->bus_id;
     $tracking->date_of_travel =$today;
     $tracking->save();
    }
   return redirect()->route($this->route_manifest,$route->id);
  }

  public function route_tracking_start($id)
  {
    $tracking =RouteTracking::find($id);
    $tracking->started=1;
    $tracking->start_time =date('H:i');
    $tracking->save();
     return redirect()->route($this->route_manifest,$tracking->routes_times_id)->with(ToastNotification::toast('Route has started ,Safe Journey ',$this->on_board));

  }

  public function route_tracking_end($id)
  {
    $tracking =RouteTracking::find($id);
    $tracking->ended=1;
    $tracking->end_time =date('H:i A');
    $tracking->save();
     return redirect()->route($this->route_manifest,$tracking->routes_times_id)->with(ToastNotification::toast('Route has Ended ,Welcome Back',$this->on_board));

  }

    public function get_route_times(Request $request,$operator_id)
    {
        $validated = $request->validate([
            'to_station_id' => 'required',
            'from_station_id' => 'required',
        ]);

        $from = $request->from_station_id;
        $to = $request->to_station_id;
        $results = array();
        $travel_day_of_week = date('l');
        $travel_time = date('H:i');
        $route_results = Route::with(['departure_times' => function ($query) use($travel_day_of_week,$travel_time) {
            $query->where('days_of_week', 'like', "%$travel_day_of_week%")->whereTime('departure_time','>',$travel_time);
        }])->where([
            ['start_station', '=', $from],
            ['end_station', '=', $to],
            ['operator_id','=',$operator_id],
        ])->get();

        foreach($route_results as $key=> $route)
        {
            foreach($route->departure_times as $d_time)
            {
                // check if route is full
                $seats_left = $d_time->number_of_seats_left(date('Y-m-d'));
                if($seats_left > 0)
                {
                    $result_array = array(
                        'id' => $d_time->id,
                        'price' => $route->price,
                        'time' => $d_time->departure_time,
                        'route_type' => 'main_route',
                        'operator' => $route->operator->name,
                        'seats_left' => $seats_left
                    );
                    $results[] = $result_array;
                }
            }
        }

        $stop_over_routes = StopoverStation::with(['departure_times' => function ($query) use($travel_day_of_week,$travel_time) {

            $query->whereHas('main_route_departure_time', function ($query) use($travel_day_of_week) {
                $query->where('days_of_week', 'like', "%$travel_day_of_week%");
            });
            $query->whereTime('arrival_time','>',$travel_time);
        },

        ])->where([
            ['start_station', '=', $from],
            ['end_station', '=', $to],
        ])->get();
        foreach($stop_over_routes as $key=> $route)
        {
            foreach($route->departure_times as $d_time)
            {
                $seats_left =$d_time->main_route_departure_time->number_of_seats_left(date('Y-m-d'));
                if($seats_left > 0)
                {
                    if($route->route->operator->id == $operator_id)
                    {
                        $result_array = array(
                            'id' => $d_time->id,
                            'price' => $route->price,
                            'time' => $d_time->departure_time,
                            'route_type' => 'stop_over_route',
                            'operator' => $route->route->operator->name,
                            'seats_left' => $seats_left
                        );
                        $results[] = $result_array;
                    }
                }
            }
        }

        return $results;
    }
}
