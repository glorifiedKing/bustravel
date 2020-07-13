<?php

namespace glorifiedking\BusTravel;
use Carbon\CarbonPeriod;
use glorifiedking\BusTravel\Booking;
use glorifiedking\BusTravel\RoutesStopoversDepartureTime;
class ReportsRoutesPerfomance
{
   public static $CreatedAt1="created_at",$Status1="status",
   $RoutesDepartureTimeId1='routes_departure_time_id',
   $StartDayTime1=' 00:00:00', $EndDayTime1=' 23:59:59',
   $route_type1 ="route_type", $main_route1 ="main_route",
   $stop_over_route1="stop_over_route",$RoutesTimesId1='routes_times_id',$Amount1='amount'

   ;


    public static function week($r_services=[])
    {
      $r_x_axis = [];
      $r_weekarray = [];
      $array_services=[];
      $r_weekStartDate = \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d ');
      $r_weekEndDate = \Carbon\Carbon::now()->endOfWeek()->format('Y-m-d');
      $r_daterange = CarbonPeriod::create($r_weekStartDate, $r_weekEndDate);
      $r_weekdates = [];
      foreach ($r_daterange as $r_weekdate) {
          $r_weekdates[] = $r_weekdate->format('Y-m-d');
          $r_x_axis[] = $r_weekdate->format('D');
      }
      //best Routes
      $service_week=[];
      foreach($r_services as $r_route_departure){
        // Main Routes Bookings
        $r_stopover_services_ids =RoutesStopoversDepartureTime::where(self::$RoutesTimesId1,$r_route_departure->id)->pluck('id');
        $Psales_main = Booking::whereBetween(self::$CreatedAt1, [$r_weekStartDate.self::$StartDayTime1, $r_weekEndDate .self::$EndDayTime1])
        ->where(self::$RoutesDepartureTimeId1,$r_route_departure->id)
        ->where(self::$route_type1,self::$main_route1)
        ->where(self::$Status1, 1)->sum(self::$Amount1);
        // Stop Overs Routes Bookings
        $Psales_stover = Booking::whereBetween(self::$CreatedAt1, [$r_weekStartDate.self::$StartDayTime1, $r_weekEndDate.self::$EndDayTime1])
        ->whereIn(self::$RoutesDepartureTimeId1,$r_stopover_services_ids)
        ->where(self::$route_type1,self::$stop_over_route1)
        ->where(self::$Status1, 1)->sum(self::$Amount1);
          $service_week[$r_route_departure->id] = $Psales_main+$Psales_stover;
      }
      arsort($service_week);
      $best_5_services =array_slice($service_week,0,5,true);
      foreach ($r_weekdates as $wdates) {
        foreach($best_5_services as $key=> $r_route_departure){
          // Main Routes Bookings
          $route_time =RoutesDepartureTime::find($key);
          $r_stopover_services_ids =RoutesStopoversDepartureTime::where(self::$RoutesTimesId1,$key)->pluck('id');
          $Psales_main = Booking::whereBetween(self::$CreatedAt1, [$wdates.self::$StartDayTime1, $wdates.self::$EndDayTime1])
          ->where(self::$RoutesDepartureTimeId1,$key)
          ->where(self::$route_type1,self::$main_route1)
          ->where(self::$Status1, 1)->sum(self::$Amount1);
          // Stop Overs Routes Bookings
          $Psales_stover = Booking::whereBetween(self::$CreatedAt1, [$wdates.self::$StartDayTime1, $wdates.self::$EndDayTime1])
          ->whereIn(self::$RoutesDepartureTimeId1,$r_stopover_services_ids)
          ->where(self::$route_type1,self::$stop_over_route1)
          ->where(self::$Status1, 1)->sum(self::$Amount1);
            $routeday[$key] = $Psales_main+$Psales_stover;
            $start_code =$route_time->route->start->code??"None";
            $end_code= $route_time->route->end->code??"None";
            $start_time=str_replace(' ', '', $route_time->departure_time);
            $array_services[$key]= $start_code.'-'.$end_code.'/'.$start_time;
          $r_weekarray[$wdates] =$routeday;
        }

        }
        return [$r_weekarray,$r_x_axis,$array_services];
    }

    public static function Thismonth($r_services=[])
    {
      $r_x_axis1 = [];
      $r_weekarray1 = [];
      $array_services1=[];
      $r_monthStartDate = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d ');
      $r_monthEndDate = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');
      $r_daterange = CarbonPeriod::create($r_monthStartDate, $r_monthEndDate);
      $r_monthdates = [];
      //best Routes
      $service_month=[];
      foreach($r_services as $r_route_departure){
        // Main Routes Bookings
        $r_stopover_services_ids =RoutesStopoversDepartureTime::where(self::$RoutesTimesId1,$r_route_departure->id)->pluck('id');
        $Psales_main = Booking::whereBetween(self::$CreatedAt1, [$r_monthStartDate.self::$StartDayTime1, $r_monthEndDate .self::$EndDayTime1])
        ->where(self::$RoutesDepartureTimeId1,$r_route_departure->id)
        ->where(self::$route_type1,self::$main_route1)
        ->where(self::$Status1, 1)->sum(self::$Amount1);
        // Stop Overs Routes Bookings
        $Psales_stover = Booking::whereBetween(self::$CreatedAt1, [$r_monthStartDate.self::$StartDayTime1, $r_monthEndDate.self::$EndDayTime1])
        ->whereIn(self::$RoutesDepartureTimeId1,$r_stopover_services_ids)
        ->where(self::$route_type1,self::$stop_over_route1)
        ->where(self::$Status1, 1)->sum(self::$Amount1);
          $service_month[$r_route_departure->id] = $Psales_main+$Psales_stover;
      }
      arsort($service_month);
      $best_5_services =array_slice($service_month,0,5,true);
      foreach ($r_daterange as $monthdate) {
          $r_monthdates[] = $monthdate->format('Y-m-d');
          $r_x_axis1[] = $monthdate->format('d');
      }
      foreach ($r_monthdates as $wdates) {
        foreach($best_5_services as $key=> $r_route_departure){
          // Main Routes Bookings
          $route_time =RoutesDepartureTime::find($key);
          $r_stopover_services_ids =RoutesStopoversDepartureTime::where(self::$RoutesTimesId1,$key)->pluck('id');
          $Psales_main_month = Booking::whereBetween(self::$CreatedAt1, [$wdates.self::$StartDayTime1, $wdates.self::$EndDayTime1])
          ->where(self::$RoutesDepartureTimeId1,$key)
          ->where(self::$route_type1,self::$main_route1)
          ->where(self::$Status1, 1)->sum(self::$Amount1);
          // Stop Overs Routes Bookings
          $Psales_stover_month = Booking::whereBetween(self::$CreatedAt1, [$wdates.self::$StartDayTime1, $wdates.self::$EndDayTime1])
          ->whereIn(self::$RoutesDepartureTimeId1,$r_stopover_services_ids)
          ->where(self::$route_type1,self::$stop_over_route1)
          ->where(self::$Status1, 1)->sum(self::$Amount1);
            $routeday[$key] = $Psales_main_month+$Psales_stover_month;

          $r_weekarray1[$wdates] =$routeday;
          $start_code =$route_time->route->start->code??"None";
          $end_code= $route_time->route->end->code??"None";
          $start_time=str_replace(' ', '', $route_time->departure_time);
          $array_services1[$key]= $start_code.'-'.$end_code.'/'.$start_time;
        }
        }
        return [$r_weekarray1,$r_x_axis1,$array_services1];
    }
    public static function Lastmonth($r_services=[])
    {
      $r_x_axis2 = [];
      $r_weekarray2 = [];
      $array_services2=[];
      $r_now = \Carbon\Carbon::now();
      $r_monthStartDatestring = $r_now->startOfMonth()->subMonth();
      $r_monthStartDate = $r_monthStartDatestring->startOfMonth()->format('Y-m-d');
      $r_monthEndDate = $r_monthStartDatestring->endOfMonth()->format('Y-m-d');
      $r_daterange = CarbonPeriod::create($r_monthStartDate, $r_monthEndDate);
      $r_monthdates = [];
      //best Routes
      $service_thismonth=[];
      foreach($r_services as $r_route_departure){
        // Main Routes Bookings
        $r_stopover_services_ids =RoutesStopoversDepartureTime::where(self::$RoutesTimesId1,$r_route_departure->id)->pluck('id');
        $Psales_main_l = Booking::whereBetween(self::$CreatedAt1, [$r_monthStartDate.self::$StartDayTime1, $r_monthEndDate .self::$EndDayTime1])
        ->where(self::$RoutesDepartureTimeId1,$r_route_departure->id)
        ->where(self::$route_type1,self::$main_route1)
        ->where(self::$Status1, 1)->sum(self::$Amount1);
        // Stop Overs Routes Bookings
        $Psales_stover_l = Booking::whereBetween(self::$CreatedAt1, [$r_monthStartDate.self::$StartDayTime1, $r_monthEndDate.self::$EndDayTime1])
        ->whereIn(self::$RoutesDepartureTimeId1,$r_stopover_services_ids)
        ->where(self::$route_type1,self::$stop_over_route1)
        ->where(self::$Status1, 1)->sum(self::$Amount1);
          $service_thismonth[$r_route_departure->id] = $Psales_main_l+$Psales_stover_l;
      }
      arsort($service_thismonth);
      $best_5_services =array_slice($service_thismonth,0,5,true);
      foreach ($r_daterange as $r_monthdate) {
          $r_monthdates[] = $r_monthdate->format('Y-m-d');
          $r_x_axis2[] = $r_monthdate->format('d');
      }
      foreach ($r_monthdates as $wdates) {
        foreach($best_5_services as $key=> $r_route_departure){
          // Main Routes Bookings
          $route_time =RoutesDepartureTime::find($key);
          $r_stopover_services_ids =RoutesStopoversDepartureTime::where(self::$RoutesTimesId1,$key)->pluck('id');
          $Psales_main_last = Booking::whereBetween(self::$CreatedAt1, [$wdates.self::$StartDayTime1, $wdates.self::$EndDayTime1])
          ->where(self::$RoutesDepartureTimeId1,$key)
          ->where(self::$route_type1,self::$main_route1)
          ->where(self::$Status1, 1)->sum(self::$Amount1);
          // Stop Overs Routes Bookings
          $Psales_stover_last = Booking::whereBetween(self::$CreatedAt1, [$wdates.self::$StartDayTime1, $wdates.self::$EndDayTime1])
          ->whereIn(self::$RoutesDepartureTimeId1,$r_stopover_services_ids)
          ->where(self::$route_type1,self::$stop_over_route1)
          ->where(self::$Status1, 1)->sum(self::$Amount1);
            $routeday[$key] = $Psales_main_last+$Psales_stover_last;
          $r_weekarray2[$wdates] =$routeday;
          $start_code =$route_time->route->start->code??"None";
          $end_code= $route_time->route->end->code??"None";
          $start_time=str_replace(' ', '', $route_time->departure_time);
          $array_services2[$key]= $start_code.'-'.$end_code.'/'.$start_time;
        }
        }
        return [$r_weekarray2,$r_x_axis2,$array_services2];
    }
    public static function last3months($r_services=[])
    {
      $r_x_axis3 = [];
      $r_weekarray3 = [];
      $array_services3=[];
      $r_startperiod = \Carbon\Carbon::now()->startOfMonth();
      $r_endperiod = \Carbon\Carbon::now()->startOfMonth()->subMonths(2);
      $r_daterange = CarbonPeriod::create($r_endperiod, '1 month', $r_startperiod);
      $r_monthStartDate = $r_startperiod->startOfMonth()->format('Y-m-d');
      $r_monthEndDate = $r_endperiod->endOfMonth()->format('Y-m-d');
      //best Routes
      $service_3month=[];
      foreach($r_services as $r_route_departure){
        // Main Routes Bookings
        $r_stopover_services_ids =RoutesStopoversDepartureTime::where(self::$RoutesTimesId1,$r_route_departure->id)->pluck('id');
        $Psales_main_3m = Booking::whereBetween(self::$CreatedAt1, [$r_monthStartDate.self::$StartDayTime1, $r_monthEndDate .self::$EndDayTime1])
        ->where(self::$RoutesDepartureTimeId1,$r_route_departure->id)
        ->where(self::$route_type1,self::$main_route1)
        ->where(self::$Status1, 1)->sum(self::$Amount1);
        // Stop Overs Routes Bookings
        $Psales_stover_3m = Booking::whereBetween(self::$CreatedAt1, [$r_monthStartDate.self::$StartDayTime1, $r_monthEndDate.self::$EndDayTime1])
        ->whereIn(self::$RoutesDepartureTimeId1,$r_stopover_services_ids)
        ->where(self::$route_type1,self::$stop_over_route1)
        ->where(self::$Status1, 1)->sum(self::$Amount1);
          $service_3month[$r_route_departure->id] = $Psales_main_3m+$Psales_stover_3m;
      }
      arsort($service_3month);
      $best_5_services =array_slice($service_3month,0,5,true);
      foreach ($r_daterange as $month) {
          $r_x_axis3[] = $month->format('M-y');
          $r_monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
          $r_monthStartDate = $r_monthdatestring->startOfMonth()->format('Y-m-d');
          $r_monthEndDate = $r_monthdatestring->endOfMonth()->format('Y-m-d');
          foreach($best_5_services as $key=> $r_route_departure){
            // Main Routes Bookings
            $route_time =RoutesDepartureTime::find($key);
            $r_stopover_services_ids =RoutesStopoversDepartureTime::where(self::$RoutesTimesId1,$key)->pluck('id');
            $Psales_main_3months = Booking::whereBetween(self::$CreatedAt1, [$r_monthStartDate.self::$StartDayTime1, $r_monthEndDate.self::$EndDayTime1])
            ->where(self::$RoutesDepartureTimeId1,$key)
            ->where(self::$route_type1,self::$main_route1)
            ->where(self::$Status1, 1)->sum(self::$Amount1);
            // Stop Overs Routes Bookings
            $Psales_stover_3months = Booking::whereBetween(self::$CreatedAt1, [$r_monthStartDate.self::$StartDayTime1, $r_monthEndDate.self::$EndDayTime1])
            ->whereIn(self::$RoutesDepartureTimeId1,$r_stopover_services_ids)
            ->where(self::$route_type1,self::$stop_over_route1)
            ->where(self::$Status1, 1)->sum(self::$Amount1);
              $routeday[$key] = $Psales_main_3months+$Psales_stover_3months;
            $r_weekarray3[$month->format('M-y')] =$routeday;
            $start_code =$route_time->route->start->code??"None";
            $end_code= $route_time->route->end->code??"None";
            $start_time=str_replace(' ', '', $route_time->departure_time);
            $array_services3[$key]= $start_code.'-'.$end_code.'/'.$start_time;
          }
      }
        return [$r_weekarray3,$r_x_axis3,$array_services3];
    }
    public static function last6months($r_services=[])
    {
      $r_x_axis4 = [];
      $r_weekarray4 = [];
      $array_services4=[];
      $r_startperiod = \Carbon\Carbon::now()->startOfMonth();
      $r_endperiod = \Carbon\Carbon::now()->startOfMonth()->subMonths(5);
      $r_daterange = CarbonPeriod::create($r_endperiod, '1 month', $r_startperiod);
      $r_monthStartDate = $r_startperiod->startOfMonth()->format('Y-m-d');
      $r_monthEndDate = $r_endperiod->endOfMonth()->format('Y-m-d');
      //best Routes
      $service_6month=[];
      foreach($r_services as $r_route_departure){
        // Main Routes Bookings
        $r_stopover_services_ids =RoutesStopoversDepartureTime::where(self::$RoutesTimesId1,$r_route_departure->id)->pluck('id');
        $Psales_main_6m = Booking::whereBetween(self::$CreatedAt1, [$r_monthStartDate.self::$StartDayTime1, $r_monthEndDate .self::$EndDayTime1])
        ->where(self::$RoutesDepartureTimeId1,$r_route_departure->id)
        ->where(self::$route_type1,self::$main_route1)
        ->where(self::$Status1, 1)->sum(self::$Amount1);
        // Stop Overs Routes Bookings
        $Psales_stover_6m = Booking::whereBetween(self::$CreatedAt1, [$r_monthStartDate.self::$StartDayTime1, $r_monthEndDate.self::$EndDayTime1])
        ->whereIn(self::$RoutesDepartureTimeId1,$r_stopover_services_ids)
        ->where(self::$route_type1,self::$stop_over_route1)
        ->where(self::$Status1, 1)->sum(self::$Amount1);
          $service_6month[$r_route_departure->id] = $Psales_main_6m+$Psales_stover_6m;
      }
      arsort($service_6month);
      $best_5_services =array_slice($service_6month,0,5,true);
      foreach ($r_daterange as $month) {
          $r_x_axis4[] = $month->format('M-y');
          $r_monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
          $r_monthStartDate = $r_monthdatestring->startOfMonth()->format('Y-m-d');
          $r_monthEndDate = $r_monthdatestring->endOfMonth()->format('Y-m-d');
            foreach($best_5_services as $key=> $r_route_departure){
              // Main Routes Bookings
              $route_time =RoutesDepartureTime::find($key);
              $r_stopover_services_ids =RoutesStopoversDepartureTime::where(self::$RoutesTimesId1,$key)->pluck('id');
              $Psales_main_6months = Booking::whereBetween(self::$CreatedAt1, [$r_monthStartDate.self::$StartDayTime1, $r_monthEndDate.self::$EndDayTime1])
              ->where(self::$RoutesDepartureTimeId1,$key)
              ->where(self::$route_type1,self::$main_route1)
              ->where(self::$Status1, 1)->sum(self::$Amount1);
              // Stop Overs Routes Bookings
              $Psales_stover_6months = Booking::whereBetween(self::$CreatedAt1, [$r_monthStartDate.self::$StartDayTime1, $r_monthEndDate.self::$EndDayTime1])
              ->whereIn(self::$RoutesDepartureTimeId1,$r_stopover_services_ids)
              ->where(self::$route_type1,self::$stop_over_route1)
              ->where(self::$Status1, 1)->sum(self::$Amount1);
                $routeday[$key] = $Psales_main_6months+$Psales_stover_6months;
              $r_weekarray4[$month->format('M-y')] =$routeday;
              $start_code =$route_time->route->start->code??"None";
              $end_code= $route_time->route->end->code??"None";
              $start_time=str_replace(' ', '', $route_time->departure_time);
              $array_services4[$key]= $start_code.'-'.$end_code.'/'.$start_time;
            }
      }
        return [$r_weekarray4,$r_x_axis4,$array_services4];
    }

    public static function ThisYear($r_services=[])
    {
      $r_x_axis5 = [];
      $r_weekarray5 = [];
      $array_services5=[];
      $yearStartDate = \Carbon\Carbon::parse('first day of January');
      $yearEndDate = \Carbon\Carbon::parse('last day of December');
      $r_monthStartDate = $yearStartDate->startOfMonth()->format('Y-m-d');
      $r_monthEndDate = $yearEndDate->endOfMonth()->format('Y-m-d');
      //best route
      $service_year=[];
      foreach($r_services as $r_route_departure){
        // Main Routes Bookings

        $r_stopover_services_ids =RoutesStopoversDepartureTime::where(self::$RoutesTimesId1,$r_route_departure->id)->pluck('id');
        $Psales_main_year = Booking::whereBetween(self::$CreatedAt1, [$r_monthStartDate.self::$StartDayTime1, $r_monthEndDate .self::$EndDayTime1])
        ->where(self::$RoutesDepartureTimeId1,$r_route_departure->id)
        ->where(self::$route_type1,self::$main_route1)
        ->where(self::$Status1, 1)->sum(self::$Amount1);
        // Stop Overs Routes Bookings
        $Psales_stover_year = Booking::whereBetween(self::$CreatedAt1, [$r_monthStartDate.self::$StartDayTime1, $r_monthEndDate.self::$EndDayTime1])
        ->whereIn(self::$RoutesDepartureTimeId1,$r_stopover_services_ids)
        ->where(self::$route_type1,self::$stop_over_route1)
        ->where(self::$Status1, 1)->sum(self::$Amount1);
          $service_year[$r_route_departure->id] = $Psales_main_year+$Psales_stover_year;
      }
      arsort($service_year);
      $best_5_services =array_slice($service_year,0,5,true);
      $daterange = CarbonPeriod::create($yearStartDate, '1 month', $yearEndDate);
      foreach ($daterange as $month) {
          $r_x_axis5[] = $month->format('M-y');
          $r_monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
          $r_startdate[] = $r_monthdatestring;
          $r_monthStartDate = $r_monthdatestring->startOfMonth()->format('Y-m-d');
          $r_monthEndDate = $r_monthdatestring->endOfMonth()->format('Y-m-d');
            foreach($best_5_services as $key=> $r_route_departure){
              // Main Routes Bookings
              $route_time =RoutesDepartureTime::find($key);
              $r_stopover_services_ids =RoutesStopoversDepartureTime::where(self::$RoutesTimesId1,$key)->pluck('id');
              $Psales_main_year = Booking::whereBetween(self::$CreatedAt1, [$r_monthStartDate.self::$StartDayTime1, $r_monthEndDate.self::$EndDayTime1])
              ->where(self::$RoutesDepartureTimeId1,$key)
              ->where(self::$route_type1,self::$main_route1)
              ->where(self::$Status1, 1)->sum(self::$Amount1);
              // Stop Overs Routes Bookings
              $Psales_stover_year = Booking::whereBetween(self::$CreatedAt1, [$r_monthStartDate.self::$StartDayTime1, $r_monthEndDate.self::$EndDayTime1])
              ->whereIn(self::$RoutesDepartureTimeId1,$r_stopover_services_ids)
              ->where(self::$route_type1,self::$stop_over_route1)
              ->where(self::$Status1, 1)->sum(self::$Amount1);
                $routeday[$key] = $Psales_main_year+$Psales_stover_year;
              $r_weekarray5[$month->format('M-y')] =$routeday;
              $start_code =$route_time->route->start->code??"None";
              $end_code= $route_time->route->end->code??"None";
              $start_time=str_replace(' ', '', $route_time->departure_time);
              $array_services5[$key]= $start_code.'-'.$end_code.'/'.$start_time;
            }
      }
        return [$r_weekarray5,$r_x_axis5,$array_services5];
    }
}
