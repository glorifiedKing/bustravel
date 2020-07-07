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
            $x_axis = [];
            $y_axis = [];
            $y_axis1 = [];
        if ($period == 1) {
            $now = \Carbon\Carbon::now();
            $weekStartDate = $now->startOfWeek()->format('Y-m-d ');
            $weekEndDate = $now->endOfWeek()->format('Y-m-d');

            $daterange = CarbonPeriod::create($weekStartDate, $weekEndDate);
            $weekdates = [];
            foreach ($daterange as $weekdate) {
                $weekdates[] = $weekdate->format('Y-m-d');
                $x_axis[] = $weekdate->format('D');
            }
            foreach ($weekdates as $wdates) {
                  // Main Routes Bookings
                  $weeksales_main = Booking::whereBetween($this->CreatedAt, [$wdates.$this->StartDayTime, $wdates.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$main_services)
                  ->where($this->route_type,$this->main_route)
                  ->where($this->Status, 1)->sum($this->Amount);
                  $weeksalescount_main = Booking::whereBetween($this->CreatedAt, [$wdates.$this->StartDayTime, $wdates.$this->EndDayTime])
                  ->where($this->route_type,$this->main_route)
                  ->whereIn($this->RoutesDepartureTimeId,$main_services)->where($this->Status, 1)->count();
                  // Stop Overs Routes Bookings
                  $weeksales_stover = Booking::whereBetween($this->CreatedAt, [$wdates.$this->StartDayTime, $wdates.$this->EndDayTime])
                  ->whereIn($this->RoutesDepartureTimeId,$stover_services)
                  ->where($this->route_type,$this->stop_over_route)
                  ->where($this->Status, 1)->sum($this->Amount);
                  $weeksalescount_stover = Booking::whereBetween($this->CreatedAt, [$wdates.$this->StartDayTime, $wdates.$this->EndDayTime])
                  ->where($this->route_type,$this->stop_over_route)
                  ->whereIn($this->RoutesDepartureTimeId,$stover_services)->where($this->Status, 1)->count();
                 $daysales = $weeksales_main +$weeksales_stover;
                 $daysalescount = $weeksalescount_main +$weeksalescount_stover;
                   $y_axis[] = $daysales;
                $y_axis1[] = $daysalescount;
            }
        } elseif ($period == 2) {
            $now = \Carbon\Carbon::now();
            $monthStartDate = $now->startOfMonth()->format('Y-m-d ');
            $monthEndDate = $now->endOfMonth()->format('Y-m-d');

            $daterange = CarbonPeriod::create($monthStartDate, $monthEndDate);
            $monthdates = [];
            foreach ($daterange as $monthdate) {
                $monthdates[] = $monthdate->format('Y-m-d');
                $x_axis[] = $monthdate->format('d');
            }
            foreach ($monthdates as $mdates) {
                // Main Routes Bookings
                $monthsales_main = Booking::whereBetween($this->CreatedAt, [$mdates.$this->StartDayTime, $mdates.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$main_services)
                ->where($this->route_type,$this->main_route)
                ->where($this->Status, 1)->sum($this->Amount);
                $monthsalescount_main = Booking::whereBetween($this->CreatedAt, [$mdates.$this->StartDayTime, $mdates.$this->EndDayTime])
                ->where($this->route_type,$this->main_route)
                ->whereIn($this->RoutesDepartureTimeId,$main_services)->where($this->Status, 1)->count();
                // Stop Overs Routes Bookings
                $monthsales_stover = Booking::whereBetween($this->CreatedAt, [$mdates.$this->StartDayTime, $mdates.$this->EndDayTime])
                ->whereIn($this->RoutesDepartureTimeId,$stover_services)
                ->where($this->route_type,$this->stop_over_route)
                ->where($this->Status, 1)->sum($this->Amount);
                $monthsalescount_stover = Booking::whereBetween($this->CreatedAt, [$mdates.$this->StartDayTime, $mdates.$this->EndDayTime])
                ->where($this->route_type,$this->stop_over_route)
                ->whereIn($this->RoutesDepartureTimeId,$stover_services)->where($this->Status, 1)->count();
               $monthsales = $monthsales_main +$monthsales_stover;
               $monthsalescount = $monthsalescount_main +$monthsalescount_stover;

                $y_axis[] = $monthsales;
                $y_axis1[] = $monthsalescount;
            }
        } elseif ($period == 3) {
            $now = \Carbon\Carbon::now();
            $monthStartDatestring = $now->startOfMonth()->subMonth();
            $monthStartDate = $monthStartDatestring->startOfMonth()->format('Y-m-d');
            $monthEndDate = $monthStartDatestring->endOfMonth()->format('Y-m-d');

            $daterange = CarbonPeriod::create($monthStartDate, $monthEndDate);
            $monthdates = [];
            foreach ($daterange as $monthdate) {
                $monthdates[] = $monthdate->format('Y-m-d');
                $x_axis[] = $monthdate->format('d');
            }
            foreach ($monthdates as $mdates) {
                // Main Routes Bookings
                $sales_last_month_main = Booking::whereBetween($this->CreatedAt, [$mdates.$this->StartDayTime, $mdates.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$main_services)
                ->where($this->route_type,$this->main_route)
                ->where($this->Status, 1)->sum($this->Amount);
                $sales_last_month_count_main = Booking::whereBetween($this->CreatedAt, [$mdates.$this->StartDayTime, $mdates.$this->EndDayTime])
                ->where($this->route_type,$this->main_route)
                ->whereIn($this->RoutesDepartureTimeId,$main_services)->where($this->Status, 1)->count();
                // Stop Overs Routes Bookings
                $sales_last_month_stover = Booking::whereBetween($this->CreatedAt, [$mdates.$this->StartDayTime, $mdates.$this->EndDayTime])
                ->whereIn($this->RoutesDepartureTimeId,$stover_services)
                ->where($this->route_type,$this->stop_over_route)
                ->where($this->Status, 1)->sum($this->Amount);
                $sales_last_month_count_stover = Booking::whereBetween($this->CreatedAt, [$mdates.$this->StartDayTime, $mdates.$this->EndDayTime])
                ->where($this->route_type,$this->stop_over_route)
                ->whereIn($this->RoutesDepartureTimeId,$stover_services)->where($this->Status, 1)->count();
               $sales_last_month = $sales_last_month_main +$sales_last_month_stover;
               $sales_last_month_count = $sales_last_month_count_main +$sales_last_month_count_stover;

              $y_axis[] = $sales_last_month;
                $y_axis1[] = $sales_last_month_count;
            }
        } elseif ($period == 4) {
            //$monthStartDatestring = $now->startOfMonth();
            $startperiod = \Carbon\Carbon::now()->startOfMonth();
            $endperiod = \Carbon\Carbon::now()->startOfMonth()->subMonths(2);
            $daterange = CarbonPeriod::create($endperiod, '1 month', $startperiod);
            foreach ($daterange as $month) {
                $x_axis[] = $month->format('M-y');
                $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
                $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
                  // Main Routes Bookings
                  $sales_3months_main = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$main_services)
                  ->where($this->route_type,$this->main_route)
                  ->where($this->Status, 1)->sum($this->Amount);
                  $sales_3months_count_main = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])
                  ->where($this->route_type,$this->main_route)
                  ->whereIn($this->RoutesDepartureTimeId,$main_services)->where($this->Status, 1)->count();
                  // Stop Overs Routes Bookings
                  $sales_3months_stover = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])
                  ->whereIn($this->RoutesDepartureTimeId,$stover_services)
                  ->where($this->route_type,$this->stop_over_route)
                  ->where($this->Status, 1)->sum($this->Amount);
                  $sales_3months_count_stover = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])
                  ->where($this->route_type,$this->stop_over_route)
                  ->whereIn($this->RoutesDepartureTimeId,$stover_services)->where($this->Status, 1)->count();
                 $sales_3months = $sales_3months_main +$sales_3months_stover;
                 $sales_3months_count = $sales_3months_count_main +$sales_3months_count_stover;

                $y_axis[] = $sales_3months;
                $y_axis1[] = $sales_3months_count;
            }
        } elseif ($period == 5) {
            $startperiod = \Carbon\Carbon::now()->startOfMonth();
            $endperiod = \Carbon\Carbon::now()->startOfMonth()->subMonths(5);
            $daterange = CarbonPeriod::create($endperiod, '1 month', $startperiod);
            foreach ($daterange as $month) {
                $x_axis[] = $month->format('M-y');
                $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
                $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
                  // Main Routes Bookings
                  $sales_6months_main = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$main_services)
                  ->where($this->route_type,$this->main_route)
                  ->where($this->Status, 1)->sum($this->Amount);
                  $sales_6months_count_main = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])
                  ->where($this->route_type,$this->main_route)
                  ->whereIn($this->RoutesDepartureTimeId,$main_services)->where($this->Status, 1)->count();
                  // Stop Overs Routes Bookings
                  $sales_6months_stover = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])
                  ->whereIn($this->RoutesDepartureTimeId,$stover_services)
                  ->where($this->route_type,$this->stop_over_route)
                  ->where($this->Status, 1)->sum($this->Amount);
                  $sales_6months_count_stover = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])
                  ->where($this->route_type,$this->stop_over_route)
                  ->whereIn($this->RoutesDepartureTimeId,$stover_services)->where($this->Status, 1)->count();
                 $sales_6months = $sales_6months_main +$sales_6months_stover;
                 $sales_6months_count = $sales_6months_count_main +$sales_6months_count_stover;
                $y_axis[] = $sales_6months;
                $y_axis1[] = $sales_6months_count;
            }
        } elseif ($period == 6) {
            $yearStartDate = \Carbon\Carbon::parse('first day of January');
            $yearEndDate = \Carbon\Carbon::parse('last day of December');
            $daterange = CarbonPeriod::create($yearStartDate, '1 month', $yearEndDate);
            foreach ($daterange as $month) {
                $x_axis[] = $month->format('M-y');
                $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $startdate[] = $monthdatestring;
                $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
                $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
                  // Main Routes Bookings
                  $year_sales_main = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$main_services)
                  ->where($this->route_type,$this->main_route)
                  ->where($this->Status, 1)->sum($this->Amount);
                  $year_sales_count_main = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])
                  ->where($this->route_type,$this->main_route)
                  ->whereIn($this->RoutesDepartureTimeId,$main_services)->where($this->Status, 1)->count();
                  // Stop Overs Routes Bookings
                  $year_sales_stover = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])
                  ->whereIn($this->RoutesDepartureTimeId,$stover_services)
                  ->where($this->route_type,$this->stop_over_route)
                  ->where($this->Status, 1)->sum($this->Amount);
                  $year_sales_count_stover = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])
                  ->where($this->route_type,$this->stop_over_route)
                  ->whereIn($this->RoutesDepartureTimeId,$stover_services)->where($this->Status, 1)->count();
                 $year_sales = $year_sales_main +$year_sales_stover;
                 $year_sales_count = $year_sales_count_main +$year_sales_count_stover;
                $y_axis[] = $year_sales;
                $y_axis1[] = $year_sales_count;
            }
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
            $r_services =RoutesDepartureTime::whereIn('route_id',$S_routes)->limit(5)->get();
        }
        $r_x_axis = [];
        $r_weekarray=[];
        if ($r_period == 1) {
            $r_now = \Carbon\Carbon::now();
            $r_weekStartDate = $r_now->startOfWeek()->format('Y-m-d ');
            $r_weekEndDate = $r_now->endOfWeek()->format('Y-m-d');
            $r_daterange = CarbonPeriod::create($r_weekStartDate, $r_weekEndDate);
            $r_weekdates = [];
            foreach ($r_daterange as $r_weekdate) {
                $r_weekdates[] = $r_weekdate->format('Y-m-d');
                $r_x_axis[] = $r_weekdate->format('D');
            }
            foreach ($r_weekdates as $wdates) {

              foreach($r_services as $r_route_departure){
                // Main Routes Bookings
                $r_stopover_services_ids =RoutesStopoversDepartureTime::where($this->RoutesTimesId,$r_route_departure->id)->pluck('id');
                $Psales_main = Booking::whereBetween($this->CreatedAt, [$wdates.$this->StartDayTime, $wdates.$this->EndDayTime])
                ->where($this->RoutesDepartureTimeId,$r_route_departure->id)
                ->where($this->route_type,$this->main_route)
                ->where($this->Status, 1)->sum($this->Amount);
                // Stop Overs Routes Bookings
                $Psales_stover = Booking::whereBetween($this->CreatedAt, [$wdates.$this->StartDayTime, $wdates.$this->EndDayTime])
                ->whereIn($this->RoutesDepartureTimeId,$r_stopover_services_ids)
                ->where($this->route_type,$this->stop_over_route)
                ->where($this->Status, 1)->sum($this->Amount);
                  $routeday[$r_route_departure->id] = $Psales_main+$Psales_stover;
                $r_weekarray[$wdates] =$routeday;
              }

              }

        } elseif ($r_period == 2) {
            $r_now = \Carbon\Carbon::now();
            $r_monthStartDate = $r_now->startOfMonth()->format('Y-m-d ');
            $r_monthEndDate = $r_now->endOfMonth()->format('Y-m-d');
            $r_daterange = CarbonPeriod::create($r_monthStartDate, $r_monthEndDate);
            $r_monthdates = [];
            foreach ($r_daterange as $monthdate) {
                $r_monthdates[] = $monthdate->format('Y-m-d');
                $r_x_axis[] = $monthdate->format('d');
            }
            foreach ($r_monthdates as $wdates) {
              foreach($r_services as $r_route_departure){
                // Main Routes Bookings
                $r_stopover_services_ids =RoutesStopoversDepartureTime::where($this->RoutesTimesId,$r_route_departure->id)->pluck('id');
                $Psales_main_month = Booking::whereBetween($this->CreatedAt, [$wdates.$this->StartDayTime, $wdates.$this->EndDayTime])
                ->where($this->RoutesDepartureTimeId,$r_route_departure->id)
                ->where($this->route_type,$this->main_route)
                ->where($this->Status, 1)->sum($this->Amount);
                // Stop Overs Routes Bookings
                $Psales_stover_month = Booking::whereBetween($this->CreatedAt, [$wdates.$this->StartDayTime, $wdates.$this->EndDayTime])
                ->whereIn($this->RoutesDepartureTimeId,$r_stopover_services_ids)
                ->where($this->route_type,$this->stop_over_route)
                ->where($this->Status, 1)->sum($this->Amount);
                  $routeday[$r_route_departure->id] = $Psales_main_month+$Psales_stover_month;
                $r_weekarray[$wdates] =$routeday;
              }
              }

        } elseif ($r_period == 3) {
            $r_now = \Carbon\Carbon::now();
            $r_monthStartDatestring = $r_now->startOfMonth()->subMonth();
            $r_monthStartDate = $r_monthStartDatestring->startOfMonth()->format('Y-m-d');
            $r_monthEndDate = $r_monthStartDatestring->endOfMonth()->format('Y-m-d');
            $r_daterange = CarbonPeriod::create($r_monthStartDate, $r_monthEndDate);
            $r_monthdates = [];
            foreach ($r_daterange as $r_monthdate) {
                $r_monthdates[] = $r_monthdate->format('Y-m-d');
                $r_x_axis[] = $r_monthdate->format('d');
            }
            foreach ($r_monthdates as $wdates) {
              foreach($r_services as $r_route_departure){
                // Main Routes Bookings
                $r_stopover_services_ids =RoutesStopoversDepartureTime::where($this->RoutesTimesId,$r_route_departure->id)->pluck('id');
                $Psales_main_last = Booking::whereBetween($this->CreatedAt, [$wdates.$this->StartDayTime, $wdates.$this->EndDayTime])
                ->where($this->RoutesDepartureTimeId,$r_route_departure->id)
                ->where($this->route_type,$this->main_route)
                ->where($this->Status, 1)->sum($this->Amount);
                // Stop Overs Routes Bookings
                $Psales_stover_last = Booking::whereBetween($this->CreatedAt, [$wdates.$this->StartDayTime, $wdates.$this->EndDayTime])
                ->whereIn($this->RoutesDepartureTimeId,$r_stopover_services_ids)
                ->where($this->route_type,$this->stop_over_route)
                ->where($this->Status, 1)->sum($this->Amount);
                  $routeday[$r_route_departure->id] = $Psales_main_last+$Psales_stover_last;
                $r_weekarray[$wdates] =$routeday;
              }
              }
        } elseif ($r_period == 4) {
            $r_now = \Carbon\Carbon::now();
            $r_startperiod = \Carbon\Carbon::now()->startOfMonth();
            $r_endperiod = \Carbon\Carbon::now()->startOfMonth()->subMonths(2);
            $r_daterange = CarbonPeriod::create($r_endperiod, '1 month', $r_startperiod);
            foreach ($r_daterange as $month) {
                $r_x_axis[] = $month->format('M-y');
                $r_monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $r_monthStartDate = $r_monthdatestring->startOfMonth()->format('Y-m-d');
                $r_monthEndDate = $r_monthdatestring->endOfMonth()->format('Y-m-d');
                foreach($r_services as $r_route_departure){
                  // Main Routes Bookings
                  $r_stopover_services_ids =RoutesStopoversDepartureTime::where($this->RoutesTimesId,$r_route_departure->id)->pluck('id');
                  $Psales_main_3months = Booking::whereBetween($this->CreatedAt, [$r_monthStartDate.$this->StartDayTime, $r_monthEndDate.$this->EndDayTime])
                  ->where($this->RoutesDepartureTimeId,$r_route_departure->id)
                  ->where($this->route_type,$this->main_route)
                  ->where($this->Status, 1)->sum($this->Amount);
                  // Stop Overs Routes Bookings
                  $Psales_stover_3months = Booking::whereBetween($this->CreatedAt, [$r_monthStartDate.$this->StartDayTime, $r_monthEndDate.$this->EndDayTime])
                  ->whereIn($this->RoutesDepartureTimeId,$r_stopover_services_ids)
                  ->where($this->route_type,$this->stop_over_route)
                  ->where($this->Status, 1)->sum($this->Amount);
                    $routeday[$r_route_departure->id] = $Psales_main_3months+$Psales_stover_3months;
                  $r_weekarray[$month->format('M-y')] =$routeday;
                }
            }
        } elseif ($r_period == 5) {
            $r_now = \Carbon\Carbon::now();
            $r_startperiod = \Carbon\Carbon::now()->startOfMonth();
            $r_endperiod = \Carbon\Carbon::now()->startOfMonth()->subMonths(5);
            $r_daterange = CarbonPeriod::create($r_endperiod, '1 month', $r_startperiod);
            foreach ($r_daterange as $month) {
                $r_x_axis[] = $month->format('M-y');
                $r_monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $r_monthStartDate = $r_monthdatestring->startOfMonth()->format('Y-m-d');
                $r_monthEndDate = $r_monthdatestring->endOfMonth()->format('Y-m-d');
                  foreach($r_services as $r_route_departure){
                    // Main Routes Bookings
                    $r_stopover_services_ids =RoutesStopoversDepartureTime::where($this->RoutesTimesId,$r_route_departure->id)->pluck('id');
                    $Psales_main_6months = Booking::whereBetween($this->CreatedAt, [$r_monthStartDate.$this->StartDayTime, $r_monthEndDate.$this->EndDayTime])
                    ->where($this->RoutesDepartureTimeId,$r_route_departure->id)
                    ->where($this->route_type,$this->main_route)
                    ->where($this->Status, 1)->sum($this->Amount);
                    // Stop Overs Routes Bookings
                    $Psales_stover_6months = Booking::whereBetween($this->CreatedAt, [$r_monthStartDate.$this->StartDayTime, $r_monthEndDate.$this->EndDayTime])
                    ->whereIn($this->RoutesDepartureTimeId,$r_stopover_services_ids)
                    ->where($this->route_type,$this->stop_over_route)
                    ->where($this->Status, 1)->sum($this->Amount);
                      $routeday[$r_route_departure->id] = $Psales_main_6months+$Psales_stover_6months;
                    $r_weekarray[$month->format('M-y')] =$routeday;
                  }
            }
        } elseif ($r_period == 6) {
            $yearStartDate = \Carbon\Carbon::parse('first day of January');
            $yearEndDate = \Carbon\Carbon::parse('last day of December');
            $daterange = CarbonPeriod::create($yearStartDate, '1 month', $yearEndDate);
            foreach ($daterange as $month) {
                $r_x_axis[] = $month->format('M-y');
                $r_monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $r_startdate[] = $r_monthdatestring;
                $r_monthStartDate = $r_monthdatestring->startOfMonth()->format('Y-m-d');
                $r_monthEndDate = $r_monthdatestring->endOfMonth()->format('Y-m-d');
                  foreach($r_services as $r_route_departure){
                    // Main Routes Bookings
                    $r_stopover_services_ids =RoutesStopoversDepartureTime::where($this->RoutesTimesId,$r_route_departure->id)->pluck('id');
                    $Psales_main_year = Booking::whereBetween($this->CreatedAt, [$r_monthStartDate.$this->StartDayTime, $r_monthEndDate.$this->EndDayTime])
                    ->where($this->RoutesDepartureTimeId,$r_route_departure->id)
                    ->where($this->route_type,$this->main_route)
                    ->where($this->Status, 1)->sum($this->Amount);
                    // Stop Overs Routes Bookings
                    $Psales_stover_year = Booking::whereBetween($this->CreatedAt, [$r_monthStartDate.$this->StartDayTime, $r_monthEndDate.$this->EndDayTime])
                    ->whereIn($this->RoutesDepartureTimeId,$r_stopover_services_ids)
                    ->where($this->route_type,$this->stop_over_route)
                    ->where($this->Status, 1)->sum($this->Amount);
                      $routeday[$r_route_departure->id] = $Psales_main_year+$Psales_stover_year;
                    $r_weekarray[$month->format('M-y')] =$routeday;
                  }
            }
        }

          $r_routes =Route::where($this->OperatorId,$r_Selected_OperatorId)->get();
          $r_operators =Operator::where($this->Status,1)->get();


        return view('bustravel::backend.reports.profitableroutes', compact('r_x_axis',  'r_period','r_routes','r_route_id','r_weekarray','r_services','r_operators','r_Selected_OperatorId','r_operator_Name'));
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
      $c_start_station = request()->input('start_station') ?? null;
      if (!is_null($c_ticket)) {

        $ticket_main_bookings = Booking::where($this->TicketNumber, $c_ticket)->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->get();
        $ticket_stop_over_bookings =Booking::where($this->TicketNumber, $c_ticket)->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->get();
        $bookings = ListBookings::list($ticket_main_bookings,$ticket_stop_over_bookings);
      }else{
        if(!is_null($c_start_station))
        {

          if(auth()->user()->hasAnyRole($this->role_cashier))
            {
                  $role_cashier_routes =Route::where('start_station',$c_start_station)->pluck('id');
                  $role_cashier_route_times=RoutesDepartureTime::whereIn('route_id',$role_cashier_routes)->pluck('id');
                  $role_cashier_stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId, $role_cashier_route_times)->pluck('id');
                  $role_cashier_main_bookings = Booking::where($this->userId,auth()->user()->id)->whereIn($this->RoutesDepartureTimeId,$role_cashier_route_times)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                  $role_cashier_stop_over_bookings =Booking::where($this->userId,auth()->user()->id)->whereIn($this->RoutesDepartureTimeId,$role_cashier_stover_times_ids)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                  $bookings = ListBookings::list($role_cashier_main_bookings,$role_cashier_stop_over_bookings);

            }elseif(auth()->user()->hasAnyRole('BT Administrator')){
              $role_admin_routes =Route::where($this->OperatorId,auth()->user()->operator_id)->where('start_station',$c_start_station)->pluck('id');
              $role_admin_route_times=RoutesDepartureTime::whereIn('route_id',$role_admin_routes)->pluck('id');
              $role_admin_stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId, $role_admin_route_times)->pluck('id');
              $role_admin_main_bookings = Booking::whereIn($this->RoutesDepartureTimeId,$role_admin_route_times)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
              $role_admin_stop_over_bookings =Booking::whereIn($this->RoutesDepartureTimeId,$role_admin_stover_times_ids)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
              $bookings = ListBookings::list($role_admin_main_bookings,$role_admin_stop_over_bookings);

            }else{
              $c_routes =Route::where('start_station',$c_start_station)->pluck('id');
              $c_route_times=RoutesDepartureTime::whereIn('route_id',$c_routes)->pluck('id');
              $c_stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId, $c_route_times)->pluck('id');
              $c_main_bookings = Booking::whereIn($this->RoutesDepartureTimeId,$c_route_times)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
              $c_stop_over_bookings =Booking::whereIn($this->RoutesDepartureTimeId,$c_stover_times_ids)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
              $bookings = ListBookings::list($c_main_bookings,$c_stop_over_bookings);

            }

        }else{
          if(auth()->user()->hasAnyRole($this->role_cashier))
            {
                  $role_cashier1_main_bookings = Booking::where($this->userId,auth()->user()->id)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                  $role_cashier1_stop_over_bookings =Booking::where($this->userId,auth()->user()->id)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                  $bookings = ListBookings::list($role_cashier1_main_bookings,$role_cashier1_stop_over_bookings);

            }elseif(auth()->user()->hasAnyRole('BT Administrator')){
              $role_admin1_routes =Route::where($this->OperatorId,auth()->user()->operator_id)->pluck('id');
              $role_admin1_route_times=RoutesDepartureTime::whereIn('route_id',$role_admin1_routes)->pluck('id');
              $role_admin1_stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId, $role_admin1_route_times)->pluck('id');
              $role_admin1_main_bookings = Booking::whereIn($this->RoutesDepartureTimeId,$role_admin1_route_times)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
              $role_admin1_stop_over_bookings =Booking::whereIn($this->RoutesDepartureTimeId,$role_admin1_stover_times_ids)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
              $bookings = ListBookings::list($role_admin1_main_bookings,$role_admin1_stop_over_bookings);
            }else{
              $c1_main_bookings = Booking::whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
              $c1_stop_over_bookings =Booking::whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
              $bookings = ListBookings::list($c1_main_bookings,$c1_stop_over_bookings);
            }
      }


      }

        $stations =Station::all();
        return view('bustravel::backend.reports.cashier_report', compact('bookings', 'c_ticket', 'c_from', 'c_to','stations','c_start_station'));
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
