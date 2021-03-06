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
use glorifiedking\BusTravel\VoidTicket;
use glorifiedking\BusTravel\RouteTracking;
use glorifiedking\BusTravel\RoutesDepartureTime;
use glorifiedking\BusTravel\ToastNotification;
use glorifiedking\BusTravel\ListBookings;
use glorifiedking\BusTravel\NewBooking;
use glorifiedking\BusTravel\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use PDF;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\EscposImage;
use glorifiedking\BusTravel\Http\Requests\CreateBookingRequest;


class BookingsController extends Controller
{
    public $travel_date='date_of_travel',$userId='user_id',
    $on_board='On Board',$Status='status',$CreatedAt='created_at',
    $b_StartDayTime=' 00:00:00', $b_EndDayTime=' 23:59:59',
    $routeType ='route_type', $main_route ='main_route', $stop_over_route='stop_over_route',
    $route_manifest='bustravel.bookings.route.manifest', $RoutesDepartureTimeId='routes_departure_time_id',
    $RoutesTimesId='routes_times_id',$b_TicketNumber='ticket_number',$OperatorId='operator_id',
    $RouteId ='route_id'
    ;
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('can:View BT Bookings')->only('index');
        $this->middleware('can:Create BT Bookings')->only('create','store');
        $this->middleware('can:Update BT Bookings')->only('edit','update');
        $this->middleware('can:Delete BT Bookings')->only('delete');
        $this->middleware('can:View BT Driver Manifest')->only('manifest','route_manifest','boarded');

    }

    //fetching buses route('bustravel.buses')
    public function index()
    {
      if (request()->isMethod('post')) {
      }
      $b_from = request()->input('b_from') ?? date('Y-m-d');
      $b_to = request()->input('b_to') ?? date('Y-m-d');
      $b_ticket = request()->input('b_ticket') ?? null;
      $b_Selected_OperatorId=request()->input($this->OperatorId)??auth()->user()->operator_id??0;
      $b_sales_operator=Operator::find($b_Selected_OperatorId);
      $b_operator_Name =$b_sales_operator->name??'';
      $b_routes =Route::where($this->OperatorId,$b_Selected_OperatorId)->pluck('id');
      $b_route_times=RoutesDepartureTime::whereIn($this->RouteId,$b_routes)->pluck('id');
      $b_stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId, $b_route_times)->pluck('id');
      if (!is_null($b_ticket)) {
        $b_main_bookings = Booking::where($this->b_TicketNumber, $b_ticket)->where($this->routeType,$this->main_route)->whereNotIn($this->Status,[2])->get();
        $b_stop_over_bookings =Booking::where($this->b_TicketNumber, $b_ticket)->where($this->routeType,$this->stop_over_route)->whereNotIn($this->Status,[2])->get();
        $bookings = ListBookings::list($b_main_bookings,$b_stop_over_bookings);
      } else {

        if(auth()->user()->hasAnyRole('BT Cashier'))
                {
                  $cashier_main_bookings = Booking::where($this->userId,auth()->user()->id)
                  ->whereBetween($this->CreatedAt, [$b_from.$this->b_StartDayTime, $b_to.$this->b_EndDayTime])
                  ->where($this->routeType,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                  $cashier_stop_over_bookings =Booking::where($this->userId,auth()->user()->id)
                  ->whereBetween($this->CreatedAt, [$b_from.$this->b_StartDayTime, $b_to.$this->b_EndDayTime])
                  ->where($this->routeType,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                  $bookings = ListBookings::list($cashier_main_bookings,$cashier_stop_over_bookings);
                }
              else
                {
                  $other_main_bookings = Booking::whereIn($this->RoutesDepartureTimeId,$b_route_times)->where($this->routeType,$this->main_route)
                   ->whereBetween($this->CreatedAt, [$b_from.$this->b_StartDayTime, $b_to.$this->b_EndDayTime])
                  ->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                  $other_stop_over_bookings =Booking::whereIn($this->RoutesDepartureTimeId,$b_stover_times_ids)
                  ->where($this->routeType,$this->stop_over_route)->whereNotIn($this->Status,[2])
                  ->whereBetween($this->CreatedAt, [$b_from.$this->b_StartDayTime, $b_to.$this->b_EndDayTime])
                  ->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($other_main_bookings,$other_stop_over_bookings);

                }
      }
    $b_operators =Operator::where($this->Status,1)->get();
    $Operator_main_bookings_count = Booking::whereIn($this->RoutesDepartureTimeId,$b_route_times)
    ->where($this->routeType,$this->main_route)->where($this->Status,1)->count();
    $Operator_stop_over_bookings_count =Booking::whereIn($this->RoutesDepartureTimeId,$b_stover_times_ids)
    ->where($this->routeType,$this->stop_over_route)->where($this->Status,1)->count();
    $Operator_main_bookings_amount = Booking::whereIn($this->RoutesDepartureTimeId,$b_route_times)
    ->where($this->routeType,$this->main_route)->where($this->Status,1)->sum('amount');
    $Operator_stop_over_bookings_amount =Booking::whereIn($this->RoutesDepartureTimeId,$b_stover_times_ids)
    ->where($this->routeType,$this->stop_over_route)->where($this->Status,1)->sum('amount');
    $total_bookings=$Operator_main_bookings_count+ $Operator_stop_over_bookings_count;
    $total_bookings_amount=$Operator_main_bookings_amount+ $Operator_stop_over_bookings_amount;
    $total_number_of_routes =Route::where($this->OperatorId,$b_Selected_OperatorId)->where($this->Status,1)->count();
    $total_number_of_services=RoutesDepartureTime::whereIn($this->RouteId,$b_routes)->count();

        return view('bustravel::backend.bookings.index', compact('bookings','b_from','b_to','b_ticket','b_operators','b_Selected_OperatorId','b_operator_Name','total_bookings','total_bookings_amount','total_number_of_routes','total_number_of_services'));
    }

    //creating buses form route('bustravel.buses.create')
    public function create()
    {
        $operator_id = Auth::user()->operator_id ?? 0;


        $my_workstation_id = Auth::user()->workstation ?? 0;
        $workstation = Station::find($my_workstation_id);
        $printers = BusTravelPrinter::where($this->OperatorId,$operator_id)->get();
        $stations = Station::all();

        $custom_fields = BookingCustomField::where($this->OperatorId,auth()->user()->operator_id)->where($this->Status, 1)->orderBy('field_order', 'ASC')->get();

        return view('bustravel::backend.bookings.create', compact('workstation', 'stations', 'custom_fields','printers'));
    }

    // saving a new route departure times in the database  route('bustravel.routes.departures.store')
    public function store(CreateBookingRequest $request)
    {
        $route_id = $request->route_id;
        $routeT =$this->routeType;
        $route_type = $request->$routeT;
        $payment_method = $request->payment_method;

        $departure_time = ($route_type == $this->main_route) ? RoutesDepartureTime::find($route_id) : RoutesStopoversDepartureTime::find($route_id);

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
            $footer_powered_by = env('receipt_footer_powered',"Powered by Palm Kash");
            $footer_powered_url = env('receipt_footer_powered_url',"https://transport.palmkash.com");
            if($selected_printer->printer_url == 'rawbt:base64')
            {
                try
                {
                    $logo_file = $operator->logo_printer;
                    //$connector = new DummyPrintConnector();
                    $connector = new RawbtPrintConnector;
                    $profile = CapabilityProfile::load("TSP600");
                    $printer = new Printer($connector,$profile);
                    $operator_logo = EscposImage::load(public_path('logos')."/".$logo_file,false);
                    $printer->setJustification(Printer::JUSTIFY_CENTER);
                    $printer->qrcode($booking->ticket_number,Printer::QR_ECLEVEL_M,10,Printer::QR_MODEL_2);
                    //$printer->selectPrintMode(); /// enable this to print two copies 
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text("-------------------------------");
                    $printer->setJustification(Printer::JUSTIFY_CENTER);
                    $printer->bitImage($operator_logo);
                    $printer->text("\n");
                    //$printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                    $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
                    $printer->text("Bus Travel Ticket \n");
                    $printer->feed();
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
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
                                [$this->OperatorId,'=',auth()->user()->operator_id],
                                ['id',$fields_id[$index]],
                            ])->first();
                            $printer->text($custom_field->field_name.":".$fields_values[$index]."\n");

                        }
                    }
                    $printer->text("\n");
                    $printer->text("\n");
                    $printer->text("\n");
                    $printer->text("\n");

                 //   $printer->setBarcodeWidth(20);                
                //    $printer->barcode($booking->ticket_number,Printer::BARCODE_CODE39);
                
                    $printer->text("\n");
                    $printer->text("$footer_powered_by \n");
                    $printer->text("$footer_powered_url \n");

                    $printer->text("\n");
                    $printer->cut();

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
                                [$this->OperatorId,'=',auth()->user()->operator_id],
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
        $custom_fields = BookingCustomField::where($this->Status, 1)->orderBy('field_order', 'ASC')->get();
        $booking = Booking::find($id);
        $void_status =VoidTicket::where('booking_id',$booking->id)->first();
        if (is_null($booking)) {
            return Redirect::route('bustravel.bookings');
        }
        $route_services=[];
        if($booking->route_type=="main_route")
        {
        $route_id =$booking->route_departure_time->route->id;
        $route =Route::find($route_id);
        $main_route_services=$route->departure_times()->get();
        foreach($main_route_services as $service) {
          $result_array = array(
              'id' => $service->id,
              'time'=>$service->departure_time??'',
              'start'=>$service->route->start->name??'',
              'end'=>$service->route->end->name??'',
              'route_type'=> 'main_route',
          );
          $route_services[] = $result_array;
        }
      }else{
        $route_id_stopover =$booking->stop_over_route_departure_time->route->route_id;

        $route =Route::find($route_id_stopover);
        $route_services_id=$route->departure_times()->pluck('id');
        $stop_route_services= RoutesStopoversDepartureTime::whereIn('routes_times_id',$route_services_id)->get();
        foreach($stop_route_services as $service_stopover) {
          $result_array = array(
              'id' => $service_stopover->id,
              'time'=>$service_stopover->departure_time??'',
              'start'=>$service_stopover->route->start_stopover_station->name??'',
              'end'=>$service_stopover->route->end_stopover_station->name??'',
              'route_type'=> 'stop_over',
          );
          $route_services[] = $result_array;
        }

      }

        return view('bustravel::backend.bookings.edit', compact('booking_fields_ids', 'booking_fields', 'booking', 'users', 'routes_times', 'custom_fields','void_status','route_services'));
    }

    //Update Operator route('bustravel.operators.upadate')
    public function update($id, Request $request)
    {
        //saving to the database
        $booking = Booking::find($id);
        $booking->amount = request()->input('amount');
        $booking->status=request()->input($this->Status);
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

       if(request()->input($this->Status)==2)
       {
        $void =VoidTicket::where('booking_id',$booking->id)->first();
        if(is_null($void))
        {
          $void_new = new VoidTicket();
          $void_new->booking_id=$booking->id;
          $void_new->void_reason =request()->input('void_reason');
          $void_new->user_id=auth()->user()->id;
          $void_new->save();
        }else{
          $void->void_reason =request()->input('void_reason');
          $void->save();
        }

      }else{
      $void =VoidTicket::where('booking_id',$booking->id)->delete();
      }
      if(request()->input('change_service')==1){
        $route_id = request()->input('service');
        $route_type = request()->input('route_type');
        $date_of_travel=request()->input('date_of_travel')??date('Y-m-d');
      $new_booking =NewBooking::new($route_id,$route_type,$date_of_travel,$booking);
       if($new_booking=="failed"){
        return redirect()->route('bustravel.bookings.edit', $id)->with(ToastNotification::toast('This service is not avialable on this date, '.$date_of_travel,'Change Service','error'));
       }

      }

        return redirect()->route('bustravel.bookings.edit', $new_booking??$id)->with(ToastNotification::toast('Booking has successfully been updated','Booking Updating'));
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
    if (request()->isMethod('post')) {
    }
    $m_from = request()->input('from') ?? '00:00';
    $m_to = request()->input('to') ?? '23:59';
    $bus_no = request()->input('bus') ??'';
    $travel_day_of_week = date('l');
    $m_Selected_OperatorId=request()->input($this->OperatorId)??auth()->user()->operator_id??0;
    $m_sales_operator=Operator::find($m_Selected_OperatorId);
    $m_operator_Name =$m_sales_operator->name??'';
    $m_routes_ids =Route::where($this->OperatorId,$m_Selected_OperatorId)->pluck('id');
    if(auth()->user()->hasAnyRole('BT Driver'))
      {
       $m_driver =Driver::where($this->userId,auth()->user()->id)->first();
       if($bus_no==""){
         $driver_routes= RoutesDepartureTime::whereIn($this->RouteId,$m_routes_ids)->where('driver_id',$m_driver->id)
         ->where('days_of_week', 'like', "%$travel_day_of_week%")
         ->whereBetween('departure_time', [$m_from, $m_to])
         ->get();
       }else{
         $driver_routes= RoutesDepartureTime::whereIn($this->RouteId,$m_routes_ids)->where('driver_id',$m_driver->id)
         ->where('days_of_week', 'like', "%$travel_day_of_week%")
         ->whereBetween('departure_time', [$m_from, $m_to])->where('bus_id',$bus_no)
         ->get();
       }
       }else
        {
          $m_driver =Driver::where($this->userId,auth()->user()->id)->first();
          if($bus_no==""){
            $driver_routes= RoutesDepartureTime::whereIn($this->RouteId,$m_routes_ids)->where('days_of_week', 'like', "%$travel_day_of_week%")->
             whereBetween('departure_time', [$m_from, $m_to])
            ->orderBy('departure_time','ASC')->get();
          }else{
            $driver_routes= RoutesDepartureTime::whereIn($this->RouteId,$m_routes_ids)->where('days_of_week', 'like', "%$travel_day_of_week%")->
             whereBetween('departure_time', [$m_from, $m_to])->where('bus_id',$bus_no)
            ->orderBy('departure_time','ASC')->get();
          }
        }
        $m_buses =Bus::where($this->Status,1)->where($this->OperatorId,$m_Selected_OperatorId)->get();
        $m_drivers =Driver::where($this->Status,1)->where($this->OperatorId,$m_Selected_OperatorId)->count();
        $m_routes =Route::where($this->Status,1)->where($this->OperatorId,$m_Selected_OperatorId)->count();
        $m_services =RoutesDepartureTime::whereIn($this->RouteId,$m_routes_ids)->count();

       $m_operators =Operator::where($this->Status,1)->get();
      return view('bustravel::backend.bookings.manifest', compact('driver_routes','m_driver','m_from','m_to','m_buses','bus_no','m_operators','m_Selected_OperatorId','m_operator_Name','m_drivers','m_routes','m_services'));
  }
  public function route_manifest($id)
  {
      $today =date('Y-m-d');
      $times_id =RoutesDepartureTime::find($id);
      $route_stop_overs =$times_id->stopovers_times()->pluck('id');
      $main_bookings = Booking::where($this->RoutesDepartureTimeId,$id)->where($this->travel_date,$today)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
      $stop_over_bookings =Booking::whereIn($this->RoutesDepartureTimeId,$route_stop_overs)->where($this->travel_date,$today)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
      $main_bookings_board = Booking::where($this->RoutesDepartureTimeId,$id)->where($this->travel_date,$today)->whereNotIn($this->Status,[2])->where('boarded',1)->orderBy('id', 'DESC')->count();
      $stop_over_bookings_board =Booking::whereIn($this->RoutesDepartureTimeId,$route_stop_overs)->where($this->travel_date,$today)->whereNotIn($this->Status,[2])->where('boarded',1)->orderBy('id', 'DESC')->count();

      if(request()->isMethod('post'))
      {
          $validation = request()->validate([
            'ticket' => 'required',
          ]);
          $driver =Driver::where($this->userId,auth()->user()->id)->first();
          $ticket = request()->input('ticket');
          $main_bookings = Booking::where('ticket_number', $ticket)->where($this->routeType,$this->main_route)->where($this->Status,"!=",2)->get();
          $stop_over_bookings =Booking::where('ticket_number', $ticket)->where($this->routeType,$this->stop_over_route)->where($this->Status,"!=",2)->get();
          $bookings = ListBookings::list($main_bookings,$stop_over_bookings);
        }
      else
      {
          $driver =Driver::where($this->userId,auth()->user()->id)->first();

          $bookings = ListBookings::list($main_bookings,$stop_over_bookings);
          $ticket ="";
      }

      $tracking=RouteTracking::where($this->RoutesTimesId,$id)->where($this->travel_date,$today)->first();
      $bookings_no =$main_bookings->count()+$stop_over_bookings->count();
      $not_booked =($times_id->bus->seating_capacity??0) - $bookings_no;
      $onboard_tickets = $main_bookings_board+$stop_over_bookings_board;
      $notonboard_tickets = $bookings_no-$onboard_tickets;

      return view('bustravel::backend.bookings.routemanifest', compact('bookings','driver','ticket','onboard_tickets','notonboard_tickets','not_booked','times_id','tracking','bookings_no'));
  }

  public function boarded($id)
  {
    $booking =Booking::find($id);
    $booking->boarded=1;
    $booking->save();
    if($booking->route_type=="main_route")
    {
    $service_route =$booking->id;
    }else{
      $service_route =$booking->stop_over_route_departure_time->main_route_departure_time->id;
    }
     return redirect()->route($this->route_manifest,$service_route)->with(ToastNotification::toast($booking->ticket_number.' marked Onboard',$this->on_board));

  }
  public function boarded_all()
  {
    $validation = request()->validate(['tickets' => 'required|array'],['tickets.required'=>'No Ticket Selected']);
    $tickets =request()->input('tickets');
    $id = request()->input($this->RouteId);
    foreach($tickets as $ticket){
      $booking =Booking::find($ticket);
      $booking->boarded=1;
      $booking->save();
    }

     return redirect()->route($this->route_manifest,$id)->with(ToastNotification::toast('Batch Process Completed',$this->on_board));

  }
  public function route_tracking($id)
  {
    $today =date('Y-m-d');
    $travel_time = \Carbon\Carbon::now()->format('H:i');
    $route =RoutesDepartureTime::find($id);
    $tracking= RouteTracking::where($this->RoutesTimesId,$route->id)->where($this->travel_date,$today)->first();
    if(is_null($tracking))
    {
      if($travel_time> \Carbon\Carbon::parse($route->departure_time)->addHours(1)->format('H:i'))
      {
       return redirect()->route('bustravel.bookings.manifest')->with(ToastNotification::toast('Departure Time has already elapsed','Route Tracking','error'));
      }
     $tracking =new RouteTracking;
     $tracking->routes_times_id =$route->id;
     $tracking->driver_id =$route->driver_id??0;
     $tracking->bus_id =$route->bus_id;
     $tracking->date_of_travel =$today;
     $tracking->user_id =auth()->user()->id;
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
            [$this->OperatorId,'=',$operator_id],
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
                        $this->routeType => $this->main_route,
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
                            $this->routeType => $this->stop_over_route,
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
