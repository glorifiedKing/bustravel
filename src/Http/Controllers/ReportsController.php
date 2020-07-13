<?php

namespace glorifiedking\BusTravel\Http\Controllers;
use Carbon\CarbonPeriod;
use glorifiedking\BusTravel\RoutesDepartureTime;
use glorifiedking\BusTravel\RoutesStopoversDepartureTime;
use glorifiedking\BusTravel\Booking;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\Station;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\ListBookings;
use Illuminate\Http\Request;
use glorifiedking\BusTravel\ReportsRoutesTraffic;
use glorifiedking\BusTravel\ReportsRoutesPerfomance;
use glorifiedking\BusTravel\ReportsSales;
use glorifiedking\BusTravel\User;
use Illuminate\Routing\Controller;
class ReportsController extends Controller
{
    public $role_cashier ='BT Cashier',
     $userId='user_id', $route_type ="route_type", $main_route ="main_route", $stop_over_route="stop_over_route",
     $Status="status",$CreatedAt="created_at",$StartDayTime=' 00:00:00', $EndDayTime=' 23:59:59',$RoutesDepartureTimeId='routes_departure_time_id',
     $RoutesTimesId='routes_times_id',$TicketNumber='ticket_number',$Amount='amount',
     $OperatorId='operator_id'
     ;

    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('can:View BT Reports');
        $this->middleware('can:View BT Sales Reports')->only('sales','routes','traffic');

    }

    //sales Report
    public function sales()
    {
        if (request()->isMethod('post')) {
        }
        $period = request()->input('period') ?? 1;
        $route_id=request()->input('route') ??'all';
        $Selected_OperatorId=request()->input($this->OperatorId)??auth()->user()->operator_id??0;
        $sales_operator=Operator::find($Selected_OperatorId);
        $operator_Name =$sales_operator->name??'';
        if($route_id!='all')
        {
            $route =Route::find($route_id);
            $main_services=$route->departure_times()->pluck('id');
            $stover_services =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId,$main_services)->pluck('id');
            $route_name =$route->start->name.'['.$route->start->code.']-'.$route->end->name.'['.$route->end->code.']';

        }else{

            $route_name ='All';
            $main_services =Route::where($this->OperatorId,$Selected_OperatorId)->pluck('id');
            $stover_services =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId,$main_services)->pluck('id');
        }
        if ($period == 1) {
          $this_week_sales =ReportsSales::week($main_services,$stover_services);
           $x_axis=$this_week_sales[2];
           $y_axis=$this_week_sales[0];
           $y_axis1=$this_week_sales[1];

        } elseif ($period == 2) {
          $this_month_sales =ReportsSales::Thismonth($main_services,$stover_services);
           $x_axis=$this_month_sales[2];
           $y_axis=$this_month_sales[0];
           $y_axis1=$this_month_sales[1];

        } elseif ($period == 3) {
          $last_month_sales =ReportsSales::Lastmonth($main_services,$stover_services);
           $x_axis=$last_month_sales[2];
           $y_axis=$last_month_sales[0];
           $y_axis1=$last_month_sales[1];

        } elseif ($period == 4) {
          $last_3month_sales =ReportsSales::last3months($main_services,$stover_services);
           $x_axis=$last_3month_sales[2];
           $y_axis=$last_3month_sales[0];
           $y_axis1=$last_3month_sales[1];

        } elseif ($period == 5) {
          $last_6month_sales =ReportsSales::last6months($main_services,$stover_services);
           $x_axis=$last_6month_sales[2];
           $y_axis=$last_6month_sales[0];
           $y_axis1=$last_6month_sales[1];
        } elseif ($period == 6) {
          $this_year_sales =ReportsSales::ThisYear($main_services,$stover_services);
           $x_axis=$this_year_sales[2];
           $y_axis=$this_year_sales[0];
           $y_axis1=$this_year_sales[1];
        }
          $routes =Route::where($this->OperatorId,$Selected_OperatorId)->get();
          $operators =Operator::where($this->Status,1)->get();

        return view('bustravel::backend.reports.sales', compact('x_axis', 'y_axis','y_axis1', 'period','routes','route_id','route_name','operators','Selected_OperatorId','operator_Name'));
    }

    //Profitable Route Report
    public function routes()
    {
        if (request()->isMethod('post')) {
        }
        $r_period = request()->input('period') ?? 1;
        $r_route_id=request()->input('route') ?? 'all';
        $r_Selected_OperatorId=request()->input($this->OperatorId)??auth()->user()->operator_id??0;
        $r_sales_operator=Operator::find($r_Selected_OperatorId);
        $r_operator_Name =$r_sales_operator->name??'';
        if($r_route_id!='all')
        {
            $r_route =Route::find($r_route_id);
            $r_services=$r_route->departure_times()->get();
        }else{
            $S_routes =Route::where($this->OperatorId,$r_Selected_OperatorId)->where($this->Status, 1)->pluck('id');
            $r_services =RoutesDepartureTime::whereIn('route_id',$S_routes)->get();

        }
        $r_x_axis = [];
        $r_weekarray=[];
        $service_detials=[];
        if ($r_period == 1) {
          $this_week_traffic =ReportsRoutesPerfomance::week($r_services);
           $r_x_axis=$this_week_traffic[1];
           $r_weekarray=$this_week_traffic[0];
           $service_detials =$this_week_traffic[2];
        } elseif ($r_period == 2) {
          $this_month_traffic =ReportsRoutesPerfomance::Thismonth($r_services);
           $r_x_axis=$this_month_traffic[1];
           $r_weekarray=$this_month_traffic[0];
           $service_detials =$this_month_traffic[2];

        } elseif ($r_period == 3) {
          $last_month_traffic =ReportsRoutesPerfomance::Lastmonth($r_services);
           $r_x_axis=$last_month_traffic[1];
           $r_weekarray=$last_month_traffic[0];
           $service_detials =$last_month_traffic[2];

        } elseif ($r_period == 4) {
          $last_3month_traffic =ReportsRoutesPerfomance::last3months($r_services);
           $r_x_axis=$last_3month_traffic[1];
           $r_weekarray=$last_3month_traffic[0];
           $service_detials =$last_3month_traffic[2];
        } elseif ($r_period == 5) {
          $last_6month_traffic =ReportsRoutesPerfomance::last6months($r_services);
           $r_x_axis=$last_6month_traffic[1];
           $r_weekarray=$last_6month_traffic[0];
           $service_detials =$last_6month_traffic[2];
        } elseif ($r_period == 6) {
          $last_year_traffic =ReportsRoutesPerfomance::ThisYear($r_services);
           $r_x_axis=$last_year_traffic[1];
           $r_weekarray=$last_year_traffic[0];
           $service_detials =$last_year_traffic[2];
        }

          $r_routes =Route::where($this->OperatorId,$r_Selected_OperatorId)->get();
          $r_operators =Operator::where($this->Status,1)->get();


        return view('bustravel::backend.reports.profitableroutes', compact('r_x_axis',  'r_period','r_routes','r_route_id','r_weekarray','service_detials','r_operators','r_Selected_OperatorId','r_operator_Name'));
    }

    //Traffic Report
    public function traffic()
    {
        if (request()->isMethod('post')) {
        }
        $t_period = request()->input('period') ?? 1;
        $t_route_id=request()->input('route') ??'all';
        $t_Selected_OperatorId=request()->input($this->OperatorId)??auth()->user()->operator_id??0;
        $t_sales_operator=Operator::find($t_Selected_OperatorId);
        $t_operator_Name =$t_sales_operator->name??'';
        if($t_route_id!='all')
        {
            $t_route =Route::find($t_route_id);
            $t_main_services=$t_route->departure_times()->pluck('id');
            $t_stover_services =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId,$t_main_services)->pluck('id');
            $t_route_name =$t_route->start->name.'['.$t_route->start->code.']-'.$t_route->end->name.'['.$t_route->end->code.']';
        }else{
            $t_route_name ='All';
            $t_main_services =Route::where($this->OperatorId,$t_Selected_OperatorId)->pluck('id');
            $t_stover_services =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId,$t_main_services)->pluck('id');
        }
        $t_x_axis = [];
        $t_y_axis = [];
        if ($t_period == 1) {
           $this_week_traffic =ReportsRoutesTraffic::week($t_main_services,$t_stover_services);
            $t_x_axis=$this_week_traffic[1];
            $t_y_axis=$this_week_traffic[0];
        } elseif ($t_period == 2) {
          $this_month_traffic =ReportsRoutesTraffic::Thismonth($t_main_services,$t_stover_services);
           $t_x_axis=$this_month_traffic[1];
           $t_y_axis=$this_month_traffic[0];

        } elseif ($t_period == 3) {
          $last_month_traffic =ReportsRoutesTraffic::Lastmonth($t_main_services,$t_stover_services);
           $t_x_axis=$last_month_traffic[1];
           $t_y_axis=$last_month_traffic[0];

        } elseif ($t_period == 4) {
          $last_3month_traffic =ReportsRoutesTraffic::last3months($t_main_services,$t_stover_services);
           $t_x_axis=$last_3month_traffic[1];
           $t_y_axis=$last_3month_traffic[0];

        } elseif ($t_period == 5) {
          $last_6month_traffic =ReportsRoutesTraffic::last6months($t_main_services,$t_stover_services);
          $t_x_axis=$last_6month_traffic[1];
          $t_y_axis=$last_6month_traffic[0];

        } elseif ($t_period == 6) {
          $this_year_traffic =ReportsRoutesTraffic::ThisYear($t_main_services,$t_stover_services);
          $t_x_axis=$this_year_traffic[1];
          $t_y_axis=$this_year_traffic[0];
        }
        $t_routes =Route::where($this->OperatorId,$t_Selected_OperatorId)->get();
        $t_operators =Operator::where($this->Status,1)->get();

        return view('bustravel::backend.reports.traffic', compact('t_x_axis', 't_y_axis', 't_period','t_route_id','t_routes','t_operators','t_Selected_OperatorId','t_operator_Name','t_route_name'));
    }

    public function booking()
    {
        if (request()->isMethod('post')) {
        }
        $from = request()->input('from') ?? date('Y-m-d');
        $to = request()->input('to') ?? date('Y-m-d');
        $ticket = request()->input('ticket') ?? null;
        $Selected_OperatorId=request()->input($this->OperatorId)??auth()->user()->operator_id??0;
        $sales_operator=Operator::find($Selected_OperatorId);
        $operator_Name =$sales_operator->name??'';
        $start_station = request()->input('start_station') ?? null;

        if (!is_null($ticket)) {
          $main_bookings = Booking::where($this->TicketNumber, $ticket)->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->get();
          $stop_over_bookings =Booking::where($this->TicketNumber, $ticket)->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->get();
          $bookings = ListBookings::list($main_bookings,$stop_over_bookings);
        } else {
           if(!is_null($start_station))
           {
                $routes =Route::where('start_station',$start_station)->where($this->OperatorId,$Selected_OperatorId)->pluck('id');
                $route_times=RoutesDepartureTime::whereIn('route_id',$routes)->pluck('id');
                $stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId, $route_times)->pluck('id');
               if(auth()->user()->hasAnyRole($this->role_cashier))
               {
                 $main_bookings = Booking::where($this->userId,auth()->user()->id)->whereIn($this->RoutesDepartureTimeId,$route_times)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                 $stop_over_bookings =Booking::where($this->userId,auth()->user()->id)->whereIn($this->RoutesDepartureTimeId,$stover_times_ids)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($main_bookings,$stop_over_bookings);

                }else
               {
                 $main_bookings = Booking::whereIn($this->RoutesDepartureTimeId,$route_times)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                 $stop_over_bookings =Booking::whereIn($this->RoutesDepartureTimeId,$stover_times_ids)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($main_bookings,$stop_over_bookings);

               }
           }else{
             $routes =Route::where($this->OperatorId,$Selected_OperatorId)->pluck('id');
             $route_times=RoutesDepartureTime::whereIn('route_id',$routes)->pluck('id');
             $stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId, $route_times)->pluck('id');

             if(auth()->user()->hasAnyRole($this->role_cashier))
               {
                 $main_bookings = Booking::where($this->userId,auth()->user()->id)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                 $stop_over_bookings =Booking::where($this->userId,auth()->user()->id)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($main_bookings,$stop_over_bookings);
               }
               else
               {
                 $main_bookings = Booking::whereIn($this->RoutesDepartureTimeId,$route_times)
                 ->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])
                 ->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])
                 ->orderBy('id', 'DESC')->get();
                 $stop_over_bookings =Booking::whereIn($this->RoutesDepartureTimeId,$stover_times_ids)
                 ->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])
                 ->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])
                 ->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($main_bookings,$stop_over_bookings);
               }
           }

        }
        $stations =Station::all();
        $operators =Operator::where($this->Status,1)->get();

        return view('bustravel::backend.reports.bookings', compact('bookings', 'ticket', 'from', 'to','stations','start_station','operators','Selected_OperatorId','operator_Name'));
    }

    public function cashier_report()
    {
      if (request()->isMethod('post')) {
      }

      $c_from = request()->input('from') ?? date('Y-m-d');
      $c_to = request()->input('to') ?? date('Y-m-d');
      $c_ticket = request()->input('ticket') ?? null;
      $cashier_id = request()->input('cashier_id')??auth()->user()->id??0;
      $c_start_station = request()->input('start_station') ?? null;
      $c_Selected_OperatorId=request()->input($this->OperatorId)??auth()->user()->operator_id??0;
      $c_sales_operator=Operator::find($c_Selected_OperatorId);
      $c_operator_Name =$c_sales_operator->name??'';
      if (!is_null($c_ticket)) {

        $ticket_main_bookings = Booking::where($this->TicketNumber, $c_ticket)->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->get();
        $ticket_stop_over_bookings =Booking::where($this->TicketNumber, $c_ticket)->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->get();
        $bookings = ListBookings::list($ticket_main_bookings,$ticket_stop_over_bookings);
      }else{
        if(!is_null($c_start_station))
        {
                  $role_cashier_routes =Route::where('start_station',$c_start_station)->where($this->OperatorId,$c_Selected_OperatorId)->pluck('id');
                  $role_cashier_route_times=RoutesDepartureTime::whereIn('route_id',$role_cashier_routes)->pluck('id');
                  $role_cashier_stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId, $role_cashier_route_times)->pluck('id');
                  $role_cashier_main_bookings = Booking::where($this->userId,$cashier_id)
                  ->whereIn($this->RoutesDepartureTimeId,$role_cashier_route_times)
                  ->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])
                  ->where($this->route_type,$this->main_route)
                  ->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                  $role_cashier_stop_over_bookings =Booking::where($this->userId,$cashier_id)
                  ->whereIn($this->RoutesDepartureTimeId,$role_cashier_stover_times_ids)
                  ->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])
                  ->where($this->route_type,$this->stop_over_route)
                  ->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                  $bookings = ListBookings::list($role_cashier_main_bookings,$role_cashier_stop_over_bookings);

        }else{
                 $role_cashier1_main_bookings = Booking::where($this->userId,$cashier_id)
                 ->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])
                 ->where($this->route_type,$this->main_route)
                 ->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                  $role_cashier1_stop_over_bookings =Booking::where($this->userId,$cashier_id)
                  ->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])
                  ->where($this->route_type,$this->stop_over_route)
                  ->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                  $bookings = ListBookings::list($role_cashier1_main_bookings,$role_cashier1_stop_over_bookings);



      }
    }

        $stations =Station::all();
        $c_operators =Operator::where($this->Status,1)->get();
        $cashiers =config('bustravel.user_model', User::class)::role('BT Cashier')->where($this->OperatorId,$c_Selected_OperatorId)->where($this->Status,1)->get();
        return view('bustravel::backend.reports.cashier_report', compact('bookings', 'c_ticket', 'c_from', 'c_to','stations','c_start_station','c_operators','c_Selected_OperatorId','c_operator_Name','cashiers','cashier_id'));
    }

    public function void_booking()
    {
        if (request()->isMethod('post')) {
        }
        $v_from = request()->input('from') ?? date('Y-m-d');
        $v_to = request()->input('to') ?? date('Y-m-d');
        $v_ticket = request()->input('ticket') ?? null;
        $v_Selected_OperatorId=request()->input($this->OperatorId)??auth()->user()->operator_id??0;
        $v_sales_operator=Operator::find($v_Selected_OperatorId);
        $v_operator_Name =$v_sales_operator->name??'';
        $v_start_station = request()->input('start_station') ?? null;
        if (!is_null($v_ticket)) {
          $vticket_main_bookings = Booking::where($this->TicketNumber, $v_ticket)
          ->where($this->route_type,$this->main_route)->where($this->Status,2)->get();
          $vticket_stop_over_bookings =Booking::where($this->TicketNumber, $v_ticket)
          ->where($this->route_type,$this->stop_over_route)->where($this->Status,2)->get();
          $bookings = ListBookings::list($vticket_main_bookings,$vticket_stop_over_bookings);
        } else {
           if(!is_null($v_start_station))
           {
             $v_station_routes_ids =Route::where('start_station',$v_start_station)->where($this->OperatorId,$v_Selected_OperatorId)->pluck('id');
             $v_station_times_ids =RoutesDepartureTime::whereIn('route_id',$v_station_routes_ids)->pluck('id');
             $v_station_stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId,$v_station_times_ids)->pluck('id');
             if(auth()->user()->hasAnyRole($this->role_cashier))
               {
                 $v_cashier_main_bookings = Booking::where($this->userId,auth()->user()->id)
                 ->whereIn($this->RoutesDepartureTimeId,$v_station_times_ids )
                 ->whereBetween($this->CreatedAt, [$v_from.$this->StartDayTime, $v_to.$this->EndDayTime])
                 ->where($this->route_type,$this->main_route)->where($this->Status,2)
                 ->orderBy('id', 'DESC')->get();
                 $v_cashier_stop_over_bookings =Booking::where($this->userId,auth()->user()->id)
                 ->whereIn($this->RoutesDepartureTimeId,$v_station_stover_times_ids)
                 ->whereBetween($this->CreatedAt, [$v_from.$this->StartDayTime, $v_to.$this->EndDayTime])
                 ->where($this->route_type,$this->stop_over_route)->where($this->Status,2)
                 ->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($v_cashier_main_bookings,$v_cashier_stop_over_bookings);

                }else
               {
                  $v_main_bookings = Booking::whereIn($this->RoutesDepartureTimeId,$v_station_times_ids)
                  ->whereBetween($this->CreatedAt, [$v_from.$this->StartDayTime, $v_to.$this->EndDayTime])
                  ->where($this->route_type,$this->main_route)->where($this->Status,2)
                  ->orderBy('id', 'DESC')->get();
                 $v_stop_over_bookings =Booking::whereIn($this->RoutesDepartureTimeId,$v_station_stover_times_ids)
                 ->whereBetween($this->CreatedAt, [$v_from.$this->StartDayTime, $v_to.$this->EndDayTime])
                 ->where($this->route_type,$this->stop_over_route)->where($this->Status,2)
                 ->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($v_main_bookings,$v_stop_over_bookings);

               }
           }else{
             $v_routes_ids =Route::where($this->OperatorId,$v_Selected_OperatorId)->pluck('id');
             $v_times_ids =RoutesDepartureTime::whereIn('route_id',$v_routes_ids)->pluck('id');
             $v_stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId,$v_times_ids)->pluck('id');

              if(auth()->user()->hasAnyRole($this->role_cashier))
               {
                 $v_cashier1_main_bookings = Booking::where($this->userId,auth()->user()->id)
                 ->whereBetween($this->CreatedAt, [$v_from.$this->StartDayTime, $v_to.$this->EndDayTime])
                  ->whereIn($this->RoutesDepartureTimeId,$v_times_ids)
                 ->where($this->route_type,$this->main_route)->where($this->Status,2)
                 ->orderBy('id', 'DESC')->get();
                 $v_cashier1_stop_over_bookings =Booking::where($this->userId,auth()->user()->id)
                 ->whereIn($this->RoutesDepartureTimeId,$v_stover_times_ids)
                 ->whereBetween($this->CreatedAt, [$v_from.$this->StartDayTime, $v_to.$this->EndDayTime])
                 ->where($this->route_type,$this->stop_over_route)->where($this->Status,2)
                 ->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($v_cashier1_main_bookings,$v_cashier1_stop_over_bookings);
               }
               else
               {
                 $v1_main_bookings = Booking::whereBetween($this->CreatedAt, [$v_from.$this->StartDayTime, $v_to.$this->EndDayTime])
                  ->whereIn($this->RoutesDepartureTimeId,$v_times_ids)
                 ->where($this->route_type,$this->main_route)->where($this->Status,2)
                 ->orderBy('id', 'DESC')->get();
                 $v1_stop_over_bookings =Booking::whereIn($this->RoutesDepartureTimeId,$v_stover_times_ids)
                 ->whereBetween($this->CreatedAt, [$v_from.$this->StartDayTime, $v_to.$this->EndDayTime])
                 ->where($this->route_type,$this->stop_over_route)->where($this->Status,2)
                 ->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($v1_main_bookings,$v1_stop_over_bookings);
               }
           }

        }
        $v_stations =Station::all();
        $v_operators =Operator::where($this->Status,1)->get();

        return view('bustravel::backend.reports.void_bookings', compact('bookings', 'v_ticket', 'v_from', 'v_to','v_stations','v_start_station','v_operators','v_Selected_OperatorId','v_operator_Name'));
    }

}
