<?php

namespace glorifiedking\BusTravel\Http\Controllers;

use Carbon\CarbonPeriod;
use glorifiedking\BusTravel\Booking;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
    }

    //sales Report
    public function sales()
    {
        if (request()->isMethod('post')) {
        }
        $period = request()->input('period') ?? 1;

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
                $daysales = Booking::whereBetween('created_at', [$wdates.' 00:00:00', $wdates.' 23:59:59'])->where('status', 1)->sum('amount');
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
                $daysales = Booking::whereBetween('created_at', [$mdates.' 00:00:00', $mdates.' 23:59:59'])->where('status', 1)->sum('amount');
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
                $daysales = Booking::whereBetween('created_at', [$mdates.' 00:00:00', $mdates.' 23:59:59'])->where('status', 1)->sum('amount');
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
                $monthsales = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->sum('amount');
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
                $monthsales = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->sum('amount');
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
                $monthsales = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->sum('amount');
                $y_axis[] = $monthsales;
            }
        }

        return view('bustravel::backend.reports.sales', compact('x_axis', 'y_axis', 'period'));
    }

    //Profitable Route Report
    public function routes()
    {
        if (request()->isMethod('post')) {
        }
        $period = request()->input('period') ?? 1;

        if ($period == 1) {
            $now = \Carbon\Carbon::now();
            $weekStartDate = $now->startOfWeek()->format('Y-m-d ');
            $weekEndDate = $now->endOfWeek()->format('Y-m-d');
            $daysales = Booking::select(\DB::raw('sum(amount) as total,routes_departure_time_id'))->whereBetween('created_at', [$weekStartDate.' 00:00:00', $weekEndDate.' 23:59:59'])->where('status', 1)
        ->groupBy('routes_departure_time_id')
        ->orderBY('total', 'DESC')->get();
            $first_route = $daysales[0]->routes_departure_time_id ?? 0;
            $second_route = $daysales[1]->routes_departure_time_id ?? 0;
            $third_route = $daysales[2]->routes_departure_time_id ?? 0;
            if ($first_route == 0) {
                $first = '';
            } else {
                $first = '1st  '.($daysales[0]->route_departure_time->route->start->code ?? '').'-'.($daysales[0]->route_departure_time->route->end->code ?? '').' / '.$daysales[0]->route_departure_time->departure_time ?? '';
            }
            if ($second_route == 0) {
                $second = '';
            } else {
                $second = '2st  '.($daysales[1]->route_departure_time->route->start->code ?? '').'-'.($daysales[1]->route_departure_time->route->end->code ?? '').' / '.$daysales[1]->route_departure_time->departure_time ?? '';
            }
            if ($third_route == 0) {
                $third = '';
            } else {
                $third = '3st  '.($daysales[2]->route_departure_time->route->start->code ?? '').'-'.($daysales[2]->route_departure_time->route->end->code ?? '').' / '.$daysales[2]->route_departure_time->departure_time ?? '';
            }
            $daterange = CarbonPeriod::create($weekStartDate, $weekEndDate);
            $weekdates = [];
            $x_axis = [];
            foreach ($daterange as $weekdate) {
                $weekdates[] = $weekdate->format('Y-m-d');
                $x_axis[] = $weekdate->format('D');
            }
            $y_axis1 = [];
            $y_axis2 = [];
            $y_axis3 = [];
            foreach ($weekdates as $wdates) {
                $daysales1 = Booking::whereBetween('created_at', [$wdates.' 00:00:00', $wdates.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $first_route)->sum('amount');
                $y_axis1[] = $daysales1;
                $daysales2 = Booking::whereBetween('created_at', [$wdates.' 00:00:00', $wdates.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $second_route)->sum('amount');
                $y_axis2[] = $daysales2;
                $daysales3 = Booking::whereBetween('created_at', [$wdates.' 00:00:00', $wdates.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $third_route)->sum('amount');
                $y_axis3[] = $daysales3;
            }
        } elseif ($period == 2) {
            $now = \Carbon\Carbon::now();
            $monthStartDate = $now->startOfMonth()->format('Y-m-d ');
            $monthEndDate = $now->endOfMonth()->format('Y-m-d');
            $daysales = Booking::select(\DB::raw('sum(amount) as total,routes_departure_time_id'))->whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)
         ->groupBy('routes_departure_time_id')
         ->orderBY('total', 'DESC')->limit(3)->get();
            $first_route = $daysales[0]->routes_departure_time_id ?? 0;
            $second_route = $daysales[1]->routes_departure_time_id ?? 0;
            $third_route = $daysales[2]->routes_departure_time_id ?? 0;
            if ($first_route == 0) {
                $first = '';
            } else {
                $first = '1st  '.($daysales[0]->route_departure_time->route->start->code ?? '').'-'.($daysales[0]->route_departure_time->route->end->code ?? '').' / '.$daysales[0]->route_departure_time->departure_time ?? '';
            }
            if ($second_route == 0) {
                $second = '';
            } else {
                $second = '2st  '.($daysales[1]->route_departure_time->route->start->code ?? '').'-'.($daysales[1]->route_departure_time->route->end->code ?? '').' / '.$daysales[1]->route_departure_time->departure_time ?? '';
            }
            if ($third_route == 0) {
                $third = '';
            } else {
                $third = '3st  '.($daysales[2]->route_departure_time->route->start->code ?? '').'-'.($daysales[2]->route_departure_time->route->end->code ?? '').' / '.$daysales[2]->route_departure_time->departure_time ?? '';
            }

            $daterange = CarbonPeriod::create($monthStartDate, $monthEndDate);
            $monthdates = [];
            $x_axis = [];
            foreach ($daterange as $monthdate) {
                $monthdates[] = $monthdate->format('Y-m-d');
                $x_axis[] = $monthdate->format('d');
            }

            $y_axis1 = [];
            $y_axis2 = [];
            $y_axis3 = [];
            foreach ($monthdates as $mdates) {
                $daysales1 = Booking::whereBetween('created_at', [$mdates.' 00:00:00', $mdates.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $first_route)->sum('amount');
                $y_axis1[] = $daysales1;
                $daysales2 = Booking::whereBetween('created_at', [$mdates.' 00:00:00', $mdates.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $second_route)->sum('amount');
                $y_axis2[] = $daysales2;
                $daysales3 = Booking::whereBetween('created_at', [$mdates.' 00:00:00', $mdates.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $third_route)->sum('amount');
                $y_axis3[] = $daysales3;
            }
        } elseif ($period == 3) {
            $now = \Carbon\Carbon::now();
            $monthStartDatestring = $now->startOfMonth()->subMonth();
            $monthStartDate = $monthStartDatestring->startOfMonth()->format('Y-m-d');
            $monthEndDate = $monthStartDatestring->endOfMonth()->format('Y-m-d');
            $daysales = Booking::select(\DB::raw('sum(amount) as total,routes_departure_time_id'))->whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)
          ->groupBy('routes_departure_time_id')
          ->orderBY('total', 'DESC')->limit(3)->get();
            $first_route = $daysales[0]->routes_departure_time_id ?? 0;
            $second_route = $daysales[1]->routes_departure_time_id ?? 0;
            $third_route = $daysales[2]->routes_departure_time_id ?? 0;
            if ($first_route == 0) {
                $first = '';
            } else {
                $first = '1st  '.($daysales[0]->route_departure_time->route->start->code ?? '').'-'.($daysales[0]->route_departure_time->route->end->code ?? '').' / '.$daysales[0]->route_departure_time->departure_time ?? '';
            }
            if ($second_route == 0) {
                $second = '';
            } else {
                $second = '2st  '.($daysales[1]->route_departure_time->route->start->code ?? '').'-'.($daysales[1]->route_departure_time->route->end->code ?? '').' / '.$daysales[1]->route_departure_time->departure_time ?? '';
            }
            if ($third_route == 0) {
                $third = '';
            } else {
                $third = '3st  '.($daysales[2]->route_departure_time->route->start->code ?? '').'-'.($daysales[2]->route_departure_time->route->end->code ?? '').' / '.$daysales[2]->route_departure_time->departure_time ?? '';
            }

            $daterange = CarbonPeriod::create($monthStartDate, $monthEndDate);
            $monthdates = [];
            $x_axis = [];
            foreach ($daterange as $monthdate) {
                $monthdates[] = $monthdate->format('Y-m-d');
                $x_axis[] = $monthdate->format('d');
            }
            $y_axis1 = [];
            $y_axis2 = [];
            $y_axis3 = [];
            foreach ($monthdates as $mdates) {
                $daysales1 = Booking::whereBetween('created_at', [$mdates.' 00:00:00', $mdates.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $first_route)->sum('amount');
                $y_axis1[] = $daysales1;
                $daysales2 = Booking::whereBetween('created_at', [$mdates.' 00:00:00', $mdates.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $second_route)->sum('amount');
                $y_axis2[] = $daysales2;
                $daysales3 = Booking::whereBetween('created_at', [$mdates.' 00:00:00', $mdates.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $third_route)->sum('amount');
                $y_axis3[] = $daysales3;
            }
        } elseif ($period == 4) {
            $x_axis = [];
            $y_axis = [];
            $now = \Carbon\Carbon::now();
            //$monthStartDatestring = $now->startOfMonth();
            $startperiod = \Carbon\Carbon::now()->endOfMonth();
            $endperiod = \Carbon\Carbon::now()->endOfMonth()->subMonths(2);
            $daysales = Booking::select(\DB::raw('sum(amount) as total,routes_departure_time_id'))
          ->whereBetween('created_at', [$endperiod->format('Y-m-d').' 00:00:00', $startperiod->format('Y-m-d').' 23:59:59'])->where('status', 1)
          ->groupBy('routes_departure_time_id')
          ->orderBY('total', 'DESC')->limit(3)->get();
            $first_route = $daysales[0]->routes_departure_time_id ?? 0;
            $second_route = $daysales[1]->routes_departure_time_id ?? 0;
            $third_route = $daysales[2]->routes_departure_time_id ?? 0;
            if ($first_route == 0) {
                $first = '';
            } else {
                $first = '1st  '.($daysales[0]->route_departure_time->route->start->code ?? '').'-'.($daysales[0]->route_departure_time->route->end->code ?? '').' / '.$daysales[0]->route_departure_time->departure_time ?? '';
            }
            if ($second_route == 0) {
                $second = '';
            } else {
                $second = '2st  '.($daysales[1]->route_departure_time->route->start->code ?? '').'-'.($daysales[1]->route_departure_time->route->end->code ?? '').' / '.$daysales[1]->route_departure_time->departure_time ?? '';
            }
            if ($third_route == 0) {
                $third = '';
            } else {
                $third = '3st  '.($daysales[2]->route_departure_time->route->start->code ?? '').'-'.($daysales[2]->route_departure_time->route->end->code ?? '').' / '.$daysales[2]->route_departure_time->departure_time ?? '';
            }
            $daterange = CarbonPeriod::create($endperiod, '1 month', $startperiod);
            foreach ($daterange as $month) {
                $x_axis[] = $month->format('M-y');
                $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
                $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
                $daysales1 = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $first_route)->sum('amount');
                $y_axis1[] = $daysales1;
                $daysales2 = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $second_route)->sum('amount');
                $y_axis2[] = $daysales2;
                $daysales3 = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $third_route)->sum('amount');
                $y_axis3[] = $daysales3;
            }
        } elseif ($period == 5) {
            $x_axis = [];
            $y_axis = [];
            $now = \Carbon\Carbon::now();
            //$monthStartDatestring = $now->startOfMonth();
            $startperiod = \Carbon\Carbon::now()->endOfMonth();
            $endperiod = \Carbon\Carbon::now()->endOfMonth()->subMonths(5);
            $daysales = Booking::select(\DB::raw('sum(amount) as total,routes_departure_time_id'))
           ->whereBetween('created_at', [$endperiod->format('Y-m-d').' 00:00:00', $startperiod->format('Y-m-d').' 23:59:59'])->where('status', 1)
           ->groupBy('routes_departure_time_id')
           ->orderBY('total', 'DESC')->limit(3)->get();
            $first_route = $daysales[0]->routes_departure_time_id ?? 0;
            $second_route = $daysales[1]->routes_departure_time_id ?? 0;
            $third_route = $daysales[2]->routes_departure_time_id ?? 0;
            if ($first_route == 0) {
                $first = '';
            } else {
                $first = '1st  '.($daysales[0]->route_departure_time->route->start->code ?? '').'-'.($daysales[0]->route_departure_time->route->end->code ?? '').' / '.$daysales[0]->route_departure_time->departure_time ?? '';
            }
            if ($second_route == 0) {
                $second = '';
            } else {
                $second = '2st  '.($daysales[1]->route_departure_time->route->start->code ?? '').'-'.($daysales[1]->route_departure_time->route->end->code ?? '').' / '.$daysales[1]->route_departure_time->departure_time ?? '';
            }
            if ($third_route == 0) {
                $third = '';
            } else {
                $third = '3st  '.($daysales[2]->route_departure_time->route->start->code ?? '').'-'.($daysales[2]->route_departure_time->route->end->code ?? '').' / '.$daysales[2]->route_departure_time->departure_time ?? '';
            }
            $daterange = CarbonPeriod::create($endperiod, '1 month', $startperiod);
            foreach ($daterange as $month) {
                $x_axis[] = $month->format('M-y');
                $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
                $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
                $daysales1 = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $first_route)->sum('amount');
                $y_axis1[] = $daysales1;
                $daysales2 = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $second_route)->sum('amount');
                $y_axis2[] = $daysales2;
                $daysales3 = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $third_route)->sum('amount');
                $y_axis3[] = $daysales3;
            }
        } elseif ($period == 6) {
            $x_axis = [];
            $y_axis = [];
            $yearStartDate = \Carbon\Carbon::parse('first day of January');
            $yearEndDate = \Carbon\Carbon::parse('last day of December');
            $daysales = Booking::select(\DB::raw('sum(amount) as total,routes_departure_time_id'))
             ->whereBetween('created_at', [$yearStartDate->format('Y-m-d').' 00:00:00', $yearEndDate->format('Y-m-d').' 23:59:59'])->where('status', 1)
             ->groupBy('routes_departure_time_id')
             ->orderBY('total', 'DESC')->limit(3)->get();
            $first_route = $daysales[0]->routes_departure_time_id ?? 0;
            $second_route = $daysales[1]->routes_departure_time_id ?? 0;
            $third_route = $daysales[2]->routes_departure_time_id ?? 0;
            if ($first_route == 0) {
                $first = '';
            } else {
                $first = '1st  '.($daysales[0]->route_departure_time->route->start->code ?? '').'-'.($daysales[0]->route_departure_time->route->end->code ?? '').' / '.$daysales[0]->route_departure_time->departure_time ?? '';
            }
            if ($second_route == 0) {
                $second = '';
            } else {
                $second = '2st  '.($daysales[1]->route_departure_time->route->start->code ?? '').'-'.($daysales[1]->route_departure_time->route->end->code ?? '').' / '.$daysales[1]->route_departure_time->departure_time ?? '';
            }
            if ($third_route == 0) {
                $third = '';
            } else {
                $third = '3st  '.($daysales[2]->route_departure_time->route->start->code ?? '').'-'.($daysales[2]->route_departure_time->route->end->code ?? '').' / '.$daysales[2]->route_departure_time->departure_time ?? '';
            }
            $daterange = CarbonPeriod::create($yearStartDate, '1 month', $yearEndDate);
            foreach ($daterange as $month) {
                $x_axis[] = $month->format('M-y');
                $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
                $startdate[] = $monthdatestring;
                $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
                $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
                $daysales1 = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $first_route)->sum('amount');
                $y_axis1[] = $daysales1;
                $daysales2 = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $second_route)->sum('amount');
                $y_axis2[] = $daysales2;
                $daysales3 = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->where('routes_departure_time_id', $third_route)->sum('amount');
                $y_axis3[] = $daysales3;
            }
        }

        return view('bustravel::backend.reports.profitableroutes', compact('x_axis', 'y_axis1', 'y_axis2', 'y_axis3', 'period', 'first', 'second', 'third'));
    }

    //Traffic Report
    public function traffic()
    {
        if (request()->isMethod('post')) {
        }
        $period = request()->input('period') ?? 1;

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
                $daysales = Booking::whereBetween('created_at', [$wdates.' 00:00:00', $wdates.' 23:59:59'])->where('status', 1)->count();
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
                $daysales = Booking::whereBetween('created_at', [$mdates.' 00:00:00', $mdates.' 23:59:59'])->where('status', 1)->count();
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
                $daysales = Booking::whereBetween('created_at', [$mdates.' 00:00:00', $mdates.' 23:59:59'])->where('status', 1)->count();
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
                $monthsales = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->count();
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
                $monthsales = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->count();
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
                $monthsales = Booking::whereBetween('created_at', [$monthStartDate.' 00:00:00', $monthEndDate.' 23:59:59'])->where('status', 1)->count();
                $y_axis[] = $monthsales;
            }
        }

        return view('bustravel::backend.reports.traffic', compact('x_axis', 'y_axis', 'period'));
    }

    public function booking()
    {
        if (request()->isMethod('post')) {
        }
        $from = request()->input('from') ?? date('Y-m-d');
        $to = request()->input('to') ?? date('Y-m-d');
        $ticket = request()->input('ticket') ?? null;
        if (!is_null($ticket)) {
            $bookings = Booking::where('ticket_number', $ticket)->orderBY('id', 'DESC')->get();
        } else {
            $bookings = Booking::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->orderBY('id', 'DESC')->get();
        }

        return view('bustravel::backend.reports.bookings', compact('bookings', 'ticket', 'from', 'to'));
    }
}
