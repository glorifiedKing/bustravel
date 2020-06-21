<?php

namespace glorifiedking\BusTravel\Http\Controllers;
use Carbon\CarbonPeriod;
use glorifiedking\BusTravel\RoutesDepartureTime;
use glorifiedking\BusTravel\RoutesStopoversDepartureTime;
use glorifiedking\BusTravel\Booking;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\Station;
use glorifiedking\BusTravel\ListBookings;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
class ReportsController extends Controller
{
    public $role_cashier ='BT Cashier',
     $userId='user_id', $route_type ="route_type", $main_route ="main_route", $stop_over_route="stop_over_route",
     $Status="status",$CreatedAt="created_at",$StartDayTime=' 00:00:00', $EndDayTime=' 23:59:59',$RoutesDepartureTimeId='routes_departure_time_id',
     $RoutesTimesId='routes_times_id',$TicketNumber='ticket_number',$Amount='amount'
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
        $route_id=request()->input('route') ?? null;
        if(is_null($route_id))
        {
          $route =Route::first();
          $route_id=$route->id;
        }else{
          $route =Route::find($route_id);
        }

        $route_times=$route->departure_times()->pluck('id');

        if ($period == 1) {
            $now = \Carbon\Carbon::now();
            $weekStartDate = $now->startOfWeek()->format('Y-m-d ');
            $weekEndDate = $now->endOfWeek()->format('Y-m-d');

            $daterange = CarbonPeriod::create($weekStartDate, $weekEndDate);
            $weekdates = [];
            $x_axis = [];
            foreach ($daterange as $weekdate) {
                $weekdates[] = $weekdate->format('Y-m-d');
                $x_axis[] = $weekdate->format('D');
            }
            $y_axis = [];
            $y_axis1 = [];
            foreach ($weekdates as $wdates) {
                $daysales = Booking::whereBetween($this->CreatedAt, [$wdates.$this->StartDayTime, $wdates.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->sum($this->Amount);
                $daysalescount = Booking::whereBetween($this->CreatedAt, [$wdates.$this->StartDayTime, $wdates.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->count();
                $y_axis[] = $daysales;
                $y_axis1[] = $daysalescount;
            }
        } elseif ($period == 2) {
            $now = \Carbon\Carbon::now();
            $monthStartDate = $now->startOfMonth()->format('Y-m-d ');
            $monthEndDate = $now->endOfMonth()->format('Y-m-d');

            $daterange = CarbonPeriod::create($monthStartDate, $monthEndDate);
            $monthdates = [];
            $x_axis = [];
            foreach ($daterange as $monthdate) {
                $monthdates[] = $monthdate->format('Y-m-d');
                $x_axis[] = $monthdate->format('d');
            }

            $y_axis = [];
            $y_axis1 = [];
            foreach ($monthdates as $mdates) {
                $daysales = Booking::whereBetween($this->CreatedAt, [$mdates.$this->StartDayTime, $mdates.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->sum($this->Amount);
                $daysalescount = Booking::whereBetween($this->CreatedAt, [$mdates.$this->StartDayTime, $mdates.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->count();
                $y_axis[] = $daysales;
                $y_axis1[] = $daysalescount;
            }
        } elseif ($period == 3) {
            $now = \Carbon\Carbon::now();
            $monthStartDatestring = $now->startOfMonth()->subMonth();
            $monthStartDate = $monthStartDatestring->startOfMonth()->format('Y-m-d');
            $monthEndDate = $monthStartDatestring->endOfMonth()->format('Y-m-d');

            $daterange = CarbonPeriod::create($monthStartDate, $monthEndDate);
            $monthdates = [];
            $x_axis = [];
            foreach ($daterange as $monthdate) {
                $monthdates[] = $monthdate->format('Y-m-d');
                $x_axis[] = $monthdate->format('d');
            }
            $y_axis = [];
            $y_axis1 = [];
            foreach ($monthdates as $mdates) {
                $daysales = Booking::whereBetween($this->CreatedAt, [$mdates.$this->StartDayTime, $mdates.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->sum($this->Amount);
                $daysalescount = Booking::whereBetween($this->CreatedAt, [$mdates.$this->StartDayTime, $mdates.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->count();
                $y_axis[] = $daysales;
                $y_axis1[] = $daysalescount;
            }
        } elseif ($period == 4) {
            $x_axis = [];
            $y_axis = [];
            $y_axis1 = [];
            $now = \Carbon\Carbon::now();
            //$monthStartDatestring = $now->startOfMonth();
            $startperiod = \Carbon\Carbon::now()->endOfMonth();
            $endperiod = \Carbon\Carbon::now()->endOfMonth()->subMonths(2);
            $daterange = CarbonPeriod::create($endperiod, '1 month', $startperiod);
            foreach ($daterange as $month) {
                $x_axis[] = $month->format('M-y');
                $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
                $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
                $monthsales = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->sum($this->Amount);
                $monthsalescount = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->count();
                $y_axis[] = $monthsales;
                $y_axis1[] = $monthsalescount;
            }
        } elseif ($period == 5) {
            $x_axis = [];
            $y_axis = [];
            $y_axis1 = [];
            $now = \Carbon\Carbon::now();
            $startperiod = \Carbon\Carbon::now()->endOfMonth();
            $endperiod = \Carbon\Carbon::now()->endOfMonth()->subMonths(5);
            $daterange = CarbonPeriod::create($endperiod, '1 month', $startperiod);
            foreach ($daterange as $month) {
                $x_axis[] = $month->format('M-y');
                $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
                $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
                $monthsales = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->sum($this->Amount);
                $monthsalescount = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->count();
                $y_axis[] = $monthsales;
                $y_axis1[] = $monthsalescount;
            }
        } elseif ($period == 6) {
            $x_axis = [];
            $y_axis = [];
            $y_axis1 = [];
            $yearStartDate = \Carbon\Carbon::parse('first day of January');
            $yearEndDate = \Carbon\Carbon::parse('last day of December');
            $daterange = CarbonPeriod::create($yearStartDate, '1 month', $yearEndDate);
            foreach ($daterange as $month) {
                $x_axis[] = $month->format('M-y');
                $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $startdate[] = $monthdatestring;
                $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
                $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
                $monthsales = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->sum($this->Amount);
                $monthsalescount = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->count();
                $y_axis[] = $monthsales;
                $y_axis1[] = $monthsalescount;
            }
        }
        if(auth()->user()->hasAnyRole('BT Administrator'))
          {
            $routes =Route::where('operator_id',auth()->user()->operator_id)->get();
          }
        else
          {
          $routes =Route::all();
          }

        return view('bustravel::backend.reports.sales', compact('x_axis', 'y_axis','y_axis1', 'period','routes','route_id','route'));
    }

    //Profitable Route Report
    public function routes()
    {
        if (request()->isMethod('post')) {
        }
        $period = request()->input('period') ?? 1;
        $route_id=request()->input('route') ?? null;
        if(is_null($route_id))
        {
          $route =Route::first();
          $route_id=$route->id;
        }else{
          $route =Route::find($route_id);
        }
        $route_times=$route->departure_times()->pluck('id');
        $route_departures=$route->departure_times()->get();

        if ($period == 1) {
            $now = \Carbon\Carbon::now();
            $weekStartDate = $now->startOfWeek()->format('Y-m-d ');
            $weekEndDate = $now->endOfWeek()->format('Y-m-d');
            $daterange = CarbonPeriod::create($weekStartDate, $weekEndDate);
            $weekdates = [];
            $x_axis = [];
            foreach ($daterange as $weekdate) {
                $weekdates[] = $weekdate->format('Y-m-d');
                $x_axis[] = $weekdate->format('D');
            }
            $weekarray=[];
            foreach ($weekdates as $wdate) {

              foreach($route_departures as $route_departure){
               $daysales11 = Booking::whereBetween($this->CreatedAt, [$wdate.$this->EndDayTime, $wdate.$this->EndDayTime])->where($this->Status, 1)->where($this->RoutesDepartureTimeId, $route_departure->id)->sum($this->Amount);
                $routeday[$route_departure->id] = $daysales11;
                $weekarray[$wdate] =$routeday;
              }

              }

        } elseif ($period == 2) {
            $now = \Carbon\Carbon::now();
            $monthStartDate = $now->startOfMonth()->format('Y-m-d ');
            $monthEndDate = $now->endOfMonth()->format('Y-m-d');
            $daterange = CarbonPeriod::create($monthStartDate, $monthEndDate);
            $monthdates = [];
            $x_axis = [];
            foreach ($daterange as $monthdate) {
                $monthdates[] = $monthdate->format('Y-m-d');
                $x_axis[] = $monthdate->format('d');
            }
            $weekarray=[];
            foreach ($monthdates as $wdate) {
              foreach($route_departures as $route_departure){
               $daysales11 = Booking::whereBetween($this->CreatedAt, [$wdate.$this->StartDayTime, $wdate.$this->EndDayTime])->where($this->Status, 1)->where($this->RoutesDepartureTimeId, $route_departure->id)->sum($this->Amount);
                $routeday[$route_departure->id] = $daysales11;
                $weekarray[$wdate] =$routeday;
              }
              }

        } elseif ($period == 3) {
            $now = \Carbon\Carbon::now();
            $monthStartDatestring = $now->startOfMonth()->subMonth();
            $monthStartDate = $monthStartDatestring->startOfMonth()->format('Y-m-d');
            $monthEndDate = $monthStartDatestring->endOfMonth()->format('Y-m-d');

            $daterange = CarbonPeriod::create($monthStartDate, $monthEndDate);
            $monthdates = [];
            $x_axis = [];
            foreach ($daterange as $monthdate) {
                $monthdates[] = $monthdate->format('Y-m-d');
                $x_axis[] = $monthdate->format('d');
            }
            $weekarray=[];
            foreach ($monthdates as $wdate) {
              foreach($route_departures as $route_departure){
               $daysales11 = Booking::whereBetween($this->CreatedAt, [$wdate.$this->StartDayTime, $wdate.$this->EndDayTime])->where($this->Status, 1)->where($this->RoutesDepartureTimeId, $route_departure->id)->sum($this->Amount);
                $routeday[$route_departure->id] = $daysales11;
                $weekarray[$wdate] =$routeday;
              }
              }
        } elseif ($period == 4) {
            $x_axis = [];
            $y_axis = [];
            $now = \Carbon\Carbon::now();
            //$monthStartDatestring = $now->startOfMonth();
            $startperiod = \Carbon\Carbon::now()->endOfMonth();
            $endperiod = \Carbon\Carbon::now()->endOfMonth()->subMonths(2);
            $daterange = CarbonPeriod::create($endperiod, '1 month', $startperiod);
               $weekarray=[];
            foreach ($daterange as $month) {
                $x_axis[] = $month->format('M-y');
                $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
                $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
                foreach($route_departures as $route_departure){
                 $daysales11 = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])->where($this->Status, 1)->where($this->RoutesDepartureTimeId, $route_departure->id)->sum($this->Amount);
                  $routeday[$route_departure->id] = $daysales11;
                  $weekarray[$month->format('M-y')] =$routeday;
                }
            }
        } elseif ($period == 5) {
            $x_axis = [];
            $y_axis = [];
            $now = \Carbon\Carbon::now();
            //$monthStartDatestring = $now->startOfMonth();
            $startperiod = \Carbon\Carbon::now()->endOfMonth();
            $endperiod = \Carbon\Carbon::now()->endOfMonth()->subMonths(5);
            $daterange = CarbonPeriod::create($endperiod, '1 month', $startperiod);
             $weekarray=[];
            foreach ($daterange as $month) {
                $x_axis[] = $month->format('M-y');
                $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
                $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
                  foreach($route_departures as $route_departure){
                   $daysales11 = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])->where($this->Status, 1)->where($this->RoutesDepartureTimeId, $route_departure->id)->sum($this->Amount);
                    $routeday[$route_departure->id] = $daysales11;
                    $weekarray[$month->format('M-y')] =$routeday;
                  }
            }
        } elseif ($period == 6) {
            $x_axis = [];
            $y_axis = [];
            $yearStartDate = \Carbon\Carbon::parse('first day of January');
            $yearEndDate = \Carbon\Carbon::parse('last day of December');
            $daterange = CarbonPeriod::create($yearStartDate, '1 month', $yearEndDate);
                $weekarray=[];
            foreach ($daterange as $month) {
                $x_axis[] = $month->format('M-y');
                $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $startdate[] = $monthdatestring;
                $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
                $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
                  foreach($route_departures as $route_departure){
                   $daysales11 = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])->where($this->Status, 1)->where($this->RoutesDepartureTimeId, $route_departure->id)->sum($this->Amount);
                    $routeday[$route_departure->id] = $daysales11;
                    $weekarray[$month->format('M-y')] =$routeday;
                  }
            }
        }
        if(auth()->user()->hasAnyRole('BT Administrator'))
          {
            $routes =Route::where('operator_id',auth()->user()->operator_id)->get();
          }
        else
          {
          $routes =Route::all();
          }


        return view('bustravel::backend.reports.profitableroutes', compact('x_axis', 'y_axis1', 'y_axis2', 'y_axis3', 'period', 'first', 'second', 'third','routes','route_id','weekarray','route_departures','route'));
    }

    //Traffic Report
    public function traffic()
    {
        if (request()->isMethod('post')) {
        }
        $period = request()->input('period') ?? 1;
        $route_id=request()->input('route') ?? null;
        if(is_null($route_id))
        {
          $route =Route::first();
          $route_id=$route->id;
        }else{
          $route =Route::find($route_id);
        }
        $route_times=$route->departure_times()->pluck('id');

        if ($period == 1) {
            $now = \Carbon\Carbon::now();
            $weekStartDate = $now->startOfWeek()->format('Y-m-d ');
            $weekEndDate = $now->endOfWeek()->format('Y-m-d');

            $daterange = CarbonPeriod::create($weekStartDate, $weekEndDate);
            $weekdates = [];
            $x_axis = [];
            foreach ($daterange as $weekdate) {
                $weekdates[] = $weekdate->format('Y-m-d');
                $x_axis[] = $weekdate->format('D');
            }
            $y_axis = [];
            foreach ($weekdates as $wdates) {
                $daysales = Booking::whereBetween($this->CreatedAt, [$wdates.$this->StartDayTime, $wdates.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->count();
                $y_axis[] = $daysales;
            }
        } elseif ($period == 2) {
            $now = \Carbon\Carbon::now();
            $monthStartDate = $now->startOfMonth()->format('Y-m-d ');
            $monthEndDate = $now->endOfMonth()->format('Y-m-d');

            $daterange = CarbonPeriod::create($monthStartDate, $monthEndDate);
            $monthdates = [];
            $x_axis = [];
            foreach ($daterange as $monthdate) {
                $monthdates[] = $monthdate->format('Y-m-d');
                $x_axis[] = $monthdate->format('d');
            }

            $y_axis = [];
            foreach ($monthdates as $mdates) {
                $daysales = Booking::whereBetween($this->CreatedAt, [$mdates.$this->StartDayTime, $mdates.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->count();
                $y_axis[] = $daysales;
            }
        } elseif ($period == 3) {
            $now = \Carbon\Carbon::now();
            $monthStartDatestring = $now->startOfMonth()->subMonth();
            $monthStartDate = $monthStartDatestring->startOfMonth()->format('Y-m-d');
            $monthEndDate = $monthStartDatestring->endOfMonth()->format('Y-m-d');

            $daterange = CarbonPeriod::create($monthStartDate, $monthEndDate);
            $monthdates = [];
            $x_axis = [];
            foreach ($daterange as $monthdate) {
                $monthdates[] = $monthdate->format('Y-m-d');
                $x_axis[] = $monthdate->format('d');
            }
            $y_axis = [];
            foreach ($monthdates as $mdates) {
                $daysales = Booking::whereBetween($this->CreatedAt, [$mdates.$this->StartDayTime, $mdates.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->count();
                $y_axis[] = $daysales;
            }
        } elseif ($period == 4) {
            $x_axis = [];
            $y_axis = [];
            $now = \Carbon\Carbon::now();
            //$monthStartDatestring = $now->startOfMonth();
            $startperiod = \Carbon\Carbon::now()->endOfMonth();
            $endperiod = \Carbon\Carbon::now()->endOfMonth()->subMonths(2);
            $daterange = CarbonPeriod::create($endperiod, '1 month', $startperiod);
            foreach ($daterange as $month) {
                $x_axis[] = $month->format('M-y');
                $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
                $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
                $monthsales = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->count();
                $y_axis[] = $monthsales;
            }
        } elseif ($period == 5) {
            $x_axis = [];
            $y_axis = [];
            $now = \Carbon\Carbon::now();
            //$monthStartDatestring = $now->startOfMonth();
            $startperiod = \Carbon\Carbon::now()->endOfMonth();
            $endperiod = \Carbon\Carbon::now()->endOfMonth()->subMonths(5);
            $daterange = CarbonPeriod::create($endperiod, '1 month', $startperiod);
            foreach ($daterange as $month) {
                $x_axis[] = $month->format('M-y');
                $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
                $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
                $monthsales = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->count();
                $y_axis[] = $monthsales;
            }
        } elseif ($period == 6) {
            $x_axis = [];
            $y_axis = [];
            $yearStartDate = \Carbon\Carbon::parse('first day of January');
            $yearEndDate = \Carbon\Carbon::parse('last day of December');
            $daterange = CarbonPeriod::create($yearStartDate, '1 month', $yearEndDate);
            foreach ($daterange as $month) {
                $x_axis[] = $month->format('M-y');
                $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $startdate[] = $monthdatestring;
                $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
                $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
                $monthsales = Booking::whereBetween($this->CreatedAt, [$monthStartDate.$this->StartDayTime, $monthEndDate.$this->EndDayTime])->whereIn($this->RoutesDepartureTimeId,$route_times)->where($this->Status, 1)->count();
                $y_axis[] = $monthsales;
            }
        }
        if(auth()->user()->hasAnyRole('BT Administrator'))
          {
            $routes =Route::where('operator_id',auth()->user()->operator_id)->get();
          }
        else
          {
          $routes =Route::all();
          }

        return view('bustravel::backend.reports.traffic', compact('x_axis', 'y_axis', 'period','route','route_id','routes'));
    }

    public function booking()
    {
        if (request()->isMethod('post')) {
        }
        $from = request()->input('from') ?? date('Y-m-d');
        $to = request()->input('to') ?? date('Y-m-d');
        $ticket = request()->input('ticket') ?? null;
        $start_station = request()->input('start_station') ?? null;
        if (!is_null($ticket)) {
          $main_bookings = Booking::where($this->TicketNumber, $ticket)->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->get();
          $stop_over_bookings =Booking::where($this->TicketNumber, $ticket)->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->get();
          $bookings = ListBookings::list($main_bookings,$stop_over_bookings);
        } else {
           if(!is_null($start_station))
           {
             if(auth()->user()->hasAnyRole('BT Administrator'))
               {
                $routes_ids =Route::where('start_station',$start_station)->where('operator_id',auth()->user()->operator_id)->pluck('id');
                $times_ids =RoutesDepartureTime::whereIn('route_id',$routes_ids)->pluck('id');
                $stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId,$times_ids)->pluck('id');
                $main_bookings = Booking::whereIn($this->RoutesDepartureTimeId,$times_ids)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                $stop_over_bookings =Booking::whereIn($this->RoutesDepartureTimeId,$stover_times_ids)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                $bookings = ListBookings::list($main_bookings,$stop_over_bookings);
               }
               elseif(auth()->user()->hasAnyRole($this->role_cashier))
               {
                 $routes =Route::where('start_station',$start_station)->pluck('id');
                 $route_times=RoutesDepartureTime::whereIn('route_id',$routes)->pluck('id');
                 $stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId, $route_times)->pluck('id');
                 $main_bookings = Booking::where($this->userId,auth()->user()->id)->whereIn($this->RoutesDepartureTimeId,$route_times)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                 $stop_over_bookings =Booking::where($this->userId,auth()->user()->id)->whereIn($this->RoutesDepartureTimeId,$stover_times_ids)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($main_bookings,$stop_over_bookings);

                }else
               {
                 $routes =Route::where('start_station',$start_station)->pluck('id');
                 $route_times=RoutesDepartureTime::whereIn('route_id',$routes)->pluck('id');
                 $stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId, $route_times)->pluck('id');
                 $main_bookings = Booking::whereIn($this->RoutesDepartureTimeId,$route_times)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                 $stop_over_bookings =Booking::whereIn($this->RoutesDepartureTimeId,$stover_times_ids)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($main_bookings,$stop_over_bookings);

               }
           }else{

             if(auth()->user()->hasAnyRole('BT Administrator'))
               {
                 $routes_ids =Route::where('operator_id',auth()->user()->operator_id)->pluck('id')->all();
                 $times_ids =RoutesDepartureTime::whereIn('route_id',$routes_ids)->pluck('id');
                 $stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId,$times_ids)->pluck('id')->all();
                 $main_bookings = Booking::whereIn($this->RoutesDepartureTimeId,$times_ids)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                 $stop_over_bookings =Booking::whereIn($this->RoutesDepartureTimeId,$stover_times_ids)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($main_bookings,$stop_over_bookings);

               }
               elseif(auth()->user()->hasAnyRole($this->role_cashier))
               {
                 $main_bookings = Booking::where($this->userId,auth()->user()->id)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                 $stop_over_bookings =Booking::where($this->userId,auth()->user()->id)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($main_bookings,$stop_over_bookings);
               }
               else
               {
                 $main_bookings = Booking::whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                 $stop_over_bookings =Booking::whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($main_bookings,$stop_over_bookings);
               }
           }

        }
        $stations =Station::all();

        return view('bustravel::backend.reports.bookings', compact('bookings', 'ticket', 'from', 'to','stations','start_station'));
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

        $c_main_bookings = Booking::where($this->TicketNumber, $c_ticket)->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->get();
        $c_stop_over_bookings =Booking::where($this->TicketNumber, $c_ticket)->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->get();
        $bookings = ListBookings::list($c_main_bookings,$c_stop_over_bookings);
      }else{
        if(!is_null($c_start_station))
        {
          if(auth()->user()->hasAnyRole($this->role_cashier))
            {
                  $c_routes =Route::where('start_station',$c_start_station)->pluck('id');
                  $c_route_times=RoutesDepartureTime::whereIn('route_id',$c_routes)->pluck('id');
                  $c_stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId, $c_route_times)->pluck('id');
                  $c_main_bookings = Booking::where($this->userId,auth()->user()->id)->whereIn($this->RoutesDepartureTimeId,$c_route_times)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                  $c_stop_over_bookings =Booking::where($this->userId,auth()->user()->id)->whereIn($this->RoutesDepartureTimeId,$c_stover_times_ids)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                  $bookings = ListBookings::list($c_main_bookings,$c_stop_over_bookings);

            }elseif(auth()->user()->hasAnyRole('BT Administrator')){
              $c_routes =Route::where('operator_id',auth()->user()->operator_id)->where('start_station',$c_start_station)->pluck('id');
              $c_route_times=RoutesDepartureTime::whereIn('route_id',$c_routes)->pluck('id');
              $c_stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId, $c_route_times)->pluck('id');
              $c_main_bookings = Booking::whereIn($this->RoutesDepartureTimeId,$c_route_times)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
              $c_stop_over_bookings =Booking::whereIn($this->RoutesDepartureTimeId,$c_stover_times_ids)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
              $bookings = ListBookings::list($c_main_bookings,$c_stop_over_bookings);

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
                  $c_main_bookings = Booking::where($this->userId,auth()->user()->id)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                  $c_stop_over_bookings =Booking::where($this->userId,auth()->user()->id)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
                  $bookings = ListBookings::list($c_main_bookings,$c_stop_over_bookings);

            }elseif(auth()->user()->hasAnyRole('BT Administrator')){
              $c_routes =Route::where('operator_id',auth()->user()->operator_id)->pluck('id');
              $c_route_times=RoutesDepartureTime::whereIn('route_id',$c_routes)->pluck('id');
              $c_stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId, $c_route_times)->pluck('id');
              $c_main_bookings = Booking::whereIn($this->RoutesDepartureTimeId,$c_route_times)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
              $c_stop_over_bookings =Booking::whereIn($this->RoutesDepartureTimeId,$c_stover_times_ids)->whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
              $bookings = ListBookings::list($c_main_bookings,$c_stop_over_bookings);
            }else{
              $c_main_bookings = Booking::whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->main_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
              $c_stop_over_bookings =Booking::whereBetween($this->CreatedAt, [$c_from.$this->StartDayTime, $c_to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->whereNotIn($this->Status,[2])->orderBy('id', 'DESC')->get();
              $bookings = ListBookings::list($c_main_bookings,$c_stop_over_bookings);
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
        $from = request()->input('from') ?? date('Y-m-d');
        $to = request()->input('to') ?? date('Y-m-d');
        $ticket = request()->input('ticket') ?? null;
        $start_station = request()->input('start_station') ?? null;
        if (!is_null($ticket)) {
          $main_bookings = Booking::where($this->TicketNumber, $ticket)->where($this->route_type,$this->main_route)->where($this->Status,2)->get();
          $stop_over_bookings =Booking::where($this->TicketNumber, $ticket)->where($this->route_type,$this->stop_over_route)->where($this->Status,2)->get();
          $bookings = ListBookings::list($main_bookings,$stop_over_bookings);
        } else {
           if(!is_null($start_station))
           {
             if(auth()->user()->hasAnyRole('BT Administrator'))
               {
                $routes_ids =Route::where('start_station',$start_station)->where('operator_id',auth()->user()->operator_id)->pluck('id');
                $times_ids =RoutesDepartureTime::whereIn('route_id',$routes_ids)->pluck('id');
                $stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId,$times_ids)->pluck('id');
                $main_bookings = Booking::whereIn($this->RoutesDepartureTimeId,$times_ids)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->main_route)->where($this->Status,2)->orderBy('id', 'DESC')->get();
                $stop_over_bookings =Booking::whereIn($this->RoutesDepartureTimeId,$stover_times_ids)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->where($this->Status,2)->orderBy('id', 'DESC')->get();
                $bookings = ListBookings::list($main_bookings,$stop_over_bookings);
               }
               elseif(auth()->user()->hasAnyRole($this->role_cashier))
               {
                 $routes =Route::where('start_station',$start_station)->pluck('id');
                 $route_times=RoutesDepartureTime::whereIn('route_id',$routes)->pluck('id');
                 $stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId, $route_times)->pluck('id');
                 $main_bookings = Booking::where($this->userId,auth()->user()->id)->whereIn($this->RoutesDepartureTimeId,$route_times)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->main_route)->where($this->Status,2)->orderBy('id', 'DESC')->get();
                 $stop_over_bookings =Booking::where($this->userId,auth()->user()->id)->whereIn($this->RoutesDepartureTimeId,$stover_times_ids)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->where($this->Status,2)->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($main_bookings,$stop_over_bookings);

                }else
               {
                 $routes =Route::where('start_station',$start_station)->pluck('id');
                 $route_times=RoutesDepartureTime::whereIn('route_id',$routes)->pluck('id');
                 $stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId, $route_times)->pluck('id');
                 $main_bookings = Booking::whereIn($this->RoutesDepartureTimeId,$route_times)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->main_route)->where($this->Status,2)->orderBy('id', 'DESC')->get();
                 $stop_over_bookings =Booking::whereIn($this->RoutesDepartureTimeId,$stover_times_ids)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->where($this->Status,2)->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($main_bookings,$stop_over_bookings);

               }
           }else{

             if(auth()->user()->hasAnyRole('BT Administrator'))
               {
                 $routes_ids =Route::where('operator_id',auth()->user()->operator_id)->pluck('id')->all();
                 $times_ids =RoutesDepartureTime::whereIn('route_id',$routes_ids)->pluck('id');
                 $stover_times_ids =RoutesStopoversDepartureTime::whereIn($this->RoutesTimesId,$times_ids)->pluck('id')->all();
                 $main_bookings = Booking::whereIn($this->RoutesDepartureTimeId,$times_ids)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->main_route)->where($this->Status,2)->orderBy('id', 'DESC')->get();
                 $stop_over_bookings =Booking::whereIn($this->RoutesDepartureTimeId,$stover_times_ids)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->where($this->Status,2)->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($main_bookings,$stop_over_bookings);

               }
               elseif(auth()->user()->hasAnyRole($this->role_cashier))
               {
                 $main_bookings = Booking::where($this->userId,auth()->user()->id)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->main_route)->where($this->Status,2)->orderBy('id', 'DESC')->get();
                 $stop_over_bookings =Booking::where($this->userId,auth()->user()->id)->whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->where($this->Status,2)->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($main_bookings,$stop_over_bookings);
               }
               else
               {
                 $main_bookings = Booking::whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->main_route)->where($this->Status,2)->orderBy('id', 'DESC')->get();
                 $stop_over_bookings =Booking::whereBetween($this->CreatedAt, [$from.$this->StartDayTime, $to.$this->EndDayTime])->where($this->route_type,$this->stop_over_route)->where($this->Status,2)->orderBy('id', 'DESC')->get();
                 $bookings = ListBookings::list($main_bookings,$stop_over_bookings);
               }
           }

        }
        $stations =Station::all();

        return view('bustravel::backend.reports.void_bookings', compact('bookings', 'ticket', 'from', 'to','stations','start_station'));
    }

}
