<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use Carbon\CarbonPeriod;
use glorifiedking\BusTravel\RoutesDepartureTime;
use glorifiedking\BusTravel\Booking;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\Station;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportsController extends Controller
{
    public $role_cashier ='BT Cashier',
     $userId='user_id';

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
                $daysales = Booking::whereBetween('created_at', [$wdates.' 00:00:00', $wdates.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->sum('amount');
                $daysalescount = Booking::whereBetween('created_at', [$wdates.' 00:00:00', $wdates.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->count();
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
                $daysales = Booking::whereBetween('created_at', [$mdates.' 00:00:00', $mdates.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->sum('amount');
                $daysalescount = Booking::whereBetween('created_at', [$mdates.' 00:00:00', $mdates.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->count();
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
                $daysales = Booking::whereBetween('created_at', [$mdates.' 00:00:00', $mdates.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->sum('amount');
                $daysalescount = Booking::whereBetween('created_at', [$mdates.' 00:00:00', $mdates.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->count();
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
                $monthsales = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->sum('amount');
                $monthsalescount = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->count();
                $y_axis[] = $monthsales;
                $y_axis1[] = $monthsalescount;
            }
        } elseif ($period == 5) {
            $x_axis = [];
            $y_axis = [];
            $y_axis1 = [];
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
                $monthsales = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->sum('amount');
                $monthsalescount = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->count();
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
                $monthsales = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->sum('amount');
                $monthsalescount = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->count();
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
      //  dd($route_time);

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
               $daysales11 = Booking::whereBetween('created_at', [$wdate.' 00:00:00', $wdate.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $route_departure->id)->sum('amount');
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
               $daysales11 = Booking::whereBetween('created_at', [$wdate.' 00:00:00', $wdate.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $route_departure->id)->sum('amount');
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
               $daysales11 = Booking::whereBetween('created_at', [$wdate.' 00:00:00', $wdate.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $route_departure->id)->sum('amount');
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
                 $daysales11 = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $route_departure->id)->sum('amount');
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
                   $daysales11 = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $route_departure->id)->sum('amount');
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
                   $daysales11 = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $route_departure->id)->sum('amount');
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
                $daysales = Booking::whereBetween('created_at', [$wdates.' 00:00:00', $wdates.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->count();
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
                $daysales = Booking::whereBetween('created_at', [$mdates.' 00:00:00', $mdates.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->count();
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
                $daysales = Booking::whereBetween('created_at', [$mdates.' 00:00:00', $mdates.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->count();
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
                $monthsales = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->count();
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
                $monthsales = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->count();
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
                $monthsales = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->where('status', 1)->count();
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
            $bookings = Booking::where('ticket_number', $ticket)->orderBY('id', 'DESC')->get();
        } else {
           if(!is_null($start_station))
           {
             if(auth()->user()->hasAnyRole('BT Administrator'))
               {
                $routes =Route::where('operator_id',auth()->user()->operator_id)->where('start_station',$start_station)->pluck('id');
                $route_times=RoutesDepartureTime::whereIn('route_id',$routes)->pluck('id');
                $bookings = Booking::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->orderBY('id', 'DESC')->get();
               }
               elseif(auth()->user()->hasAnyRole($this->role_cashier))
               {
                 $routes =Route::where('start_station',$start_station)->pluck('id');
                 $route_times=RoutesDepartureTime::whereIn('route_id',$routes)->pluck('id');
                 $bookings = Booking::where($this->userId,auth()->user()->id)->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->orderBY('id', 'DESC')->get();
               }
               else
               {
                 $routes =Route::where('start_station',$start_station)->pluck('id');
                 $route_times=RoutesDepartureTime::whereIn('route_id',$routes)->pluck('id');
                 $bookings = Booking::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->orderBY('id', 'DESC')->get();
               }
           }else{

             if(auth()->user()->hasAnyRole('BT Administrator'))
               {
                $routes =Route::where('operator_id',auth()->user()->operator_id)->pluck('id');
                $route_times=RoutesDepartureTime::whereIn('route_id',$routes)->pluck('id');
                $bookings = Booking::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->orderBY('id', 'DESC')->get();
               }
               elseif(auth()->user()->hasAnyRole($this->role_cashier))
               {
                 $bookings = Booking::where($this->userId,auth()->user()->id)->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->orderBY('id', 'DESC')->get();
               }
               else
               {
                 $bookings = Booking::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->orderBY('id', 'DESC')->get();
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

      $from = request()->input('from') ?? date('Y-m-d');
      $to = request()->input('to') ?? date('Y-m-d');
      $ticket = request()->input('ticket') ?? null;
      $start_station = request()->input('start_station') ?? null;

      if(auth()->user()->hasAnyRole($this->role_cashier))
        {
          if (!is_null($ticket)) {

              $bookings = Booking::where('ticket_number', $ticket)->where($this->userId,auth()->user()->id)->orderBY('id', 'DESC')->get();
          } else {

            if(!is_null($start_station))
            {
              $routes =Route::where('start_station',$start_station)->pluck('id');
              $route_times=RoutesDepartureTime::whereIn('route_id',$routes)->pluck('id');
               $bookings = Booking::where($this->userId,auth()->user()->id)->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->orderBY('id', 'DESC')->get();
            }else{
                $bookings = Booking::where($this->userId,auth()->user()->id)->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->orderBY('id', 'DESC')->get();
            }

          }

     }elseif(auth()->user()->hasAnyRole('BT Administrator'))
     {
       if (!is_null($ticket)) {
           $bookings = Booking::where('ticket_number', $ticket)->orderBY('id', 'DESC')->get();
       } else {

         if(!is_null($start_station))
         {
           $routes =Route::where('operator_id',auth()->user()->operator_id)->where('start_station',$start_station)->pluck('id');
           $route_times=RoutesDepartureTime::whereIn('route_id',$routes)->pluck('id');
           $bookings = Booking::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->orderBY('id', 'DESC')->get();
         }else{
           $routes =Route::where('operator_id',auth()->user()->operator_id)->pluck('id');
           $route_times=RoutesDepartureTime::whereIn('route_id',$routes)->pluck('id');
           $bookings = Booking::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->orderBY('id', 'DESC')->get();
         }
       }

     }
      else
        {
          if (!is_null($ticket)) {
              $bookings = Booking::where('ticket_number', $ticket)->orderBY('id', 'DESC')->get();
          } else {

            if(!is_null($start_station))
            {
              $routes =Route::where('start_station',$start_station)->pluck('id');
              $route_times=RoutesDepartureTime::whereIn('route_id',$routes)->pluck('id');

               $bookings = Booking::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->whereIn('routes_departure_time_id',$route_times)->orderBY('id', 'DESC')->get();
            }else{
                $bookings = Booking::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->orderBY('id', 'DESC')->get();
            }
          }
        }
        $stations =Station::all();
        return view('bustravel::backend.reports.cashier_report', compact('bookings', 'ticket', 'from', 'to','stations','start_station'));
    }

}
