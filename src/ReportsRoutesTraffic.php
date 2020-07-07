<?php

namespace glorifiedking\BusTravel;
use Carbon\CarbonPeriod;
use glorifiedking\BusTravel\Booking;
class ReportsRoutesTraffic
{
   public static $CreatedAt="created_at",$Status="status",
   $RoutesDepartureTimeId='routes_departure_time_id',
   $StartDayTime=' 00:00:00', $EndDayTime=' 23:59:59',
   $route_type ="route_type", $main_route ="main_route",
   $stop_over_route="stop_over_route"

   ;


    public static function week($main_services=[],$stover_services=[])
    {
      $t_x_axis = [];
      $t_y_axis = [];
      $t_weekStartDate = \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d ');
      $t_weekEndDate = \Carbon\Carbon::now()->endOfWeek()->format('Y-m-d');
      $t_daterange = CarbonPeriod::create($t_weekStartDate, $t_weekEndDate);
      $t_weekdates = [];
      foreach ($t_daterange as $weekdate) {
          $t_weekdates[] = $weekdate->format('Y-m-d');
          $t_x_axis[] = $weekdate->format('D');
      }
      foreach ($t_weekdates as $wdates) {
        // Main Routes Bookings
        $traffic_count_main = Booking::whereBetween(self::$CreatedAt, [$wdates.self::$StartDayTime, $wdates.self::$EndDayTime])
        ->where(self::$route_type,self::$main_route)
        ->whereIn(self::$RoutesDepartureTimeId,$main_services)->where(self::$Status, 1)->count();
        // Stop Overs Routes Bookings
        $traffic_count_stover = Booking::whereBetween(self::$CreatedAt, [$wdates.self::$StartDayTime, $wdates.self::$EndDayTime])
        ->where(self::$route_type,self::$stop_over_route)
        ->whereIn(self::$RoutesDepartureTimeId,$stover_services)->where(self::$Status, 1)->count();
       $day_traffic= $traffic_count_main +$traffic_count_stover;
          $t_y_axis[] = $day_traffic;
        }
        return [$t_y_axis,$t_x_axis];
    }

    public static function Thismonth($main_services=[],$stover_services=[])
    {
      $t_x_axis1 = [];
      $t_y_axis1 = [];
      $t_monthStartDate = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d ');
      $t_monthEndDate = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');

      $t_daterange = CarbonPeriod::create($t_monthStartDate, $t_monthEndDate);
      $t_monthdates = [];
      foreach ($t_daterange as $monthdate) {
          $t_monthdates[] = $monthdate->format('Y-m-d');
          $t_x_axis1[] = $monthdate->format('d');
      }
      foreach ($t_monthdates as $mdates) {
        // Main Routes Bookings
          $month_traffic_count_main = Booking::whereBetween(self::$CreatedAt, [$mdates.self::$StartDayTime, $mdates.self::$EndDayTime])
          ->where(self::$route_type,self::$main_route)
          ->whereIn(self::$RoutesDepartureTimeId,$main_services)->where(self::$Status, 1)->count();
          // Stop Overs Routes Bookings
          $month_traffic_count_stover = Booking::whereBetween(self::$CreatedAt, [$mdates.self::$StartDayTime, $mdates.self::$EndDayTime])
          ->where(self::$route_type,self::$stop_over_route)
          ->whereIn(self::$RoutesDepartureTimeId,$stover_services)->where(self::$Status, 1)->count();
         $month_traffic = $month_traffic_count_main +$month_traffic_count_stover;
          $t_y_axis1[] = $month_traffic;
      }
        return [$t_y_axis1,$t_x_axis1];
    }
    public static function Lastmonth($main_services=[],$stover_services=[])
    {
      $t_x_axis2 = [];
      $t_y_axis2 = [];
      $t_monthStartDatestring = \Carbon\Carbon::now()->startOfMonth()->subMonth();
      $t_monthStartDate = $t_monthStartDatestring->startOfMonth()->format('Y-m-d');
      $t_monthEndDate = $t_monthStartDatestring->endOfMonth()->format('Y-m-d');

      $t_daterange = CarbonPeriod::create($t_monthStartDate, $t_monthEndDate);
      $t_monthdates = [];
      foreach ($t_daterange as $monthdate) {
          $t_monthdates[] = $monthdate->format('Y-m-d');
          $t_x_axis2[] = $monthdate->format('d');
      }
      foreach ($t_monthdates as $mdates) {
        // Main Routes Bookings
          $lastmonth_traffic_count_main = Booking::whereBetween(self::$CreatedAt, [$mdates.self::$StartDayTime, $mdates.self::$EndDayTime])
          ->where(self::$route_type,self::$main_route)
          ->whereIn(self::$RoutesDepartureTimeId,$main_services)->where(self::$Status, 1)->count();
          // Stop Overs Routes Bookings
          $lastmonth_traffic_count_stover = Booking::whereBetween(self::$CreatedAt, [$mdates.self::$StartDayTime, $mdates.self::$EndDayTime])
          ->where(self::$route_type,self::$stop_over_route)
          ->whereIn(self::$RoutesDepartureTimeId,$stover_services)->where(self::$Status, 1)->count();
         $last_month_traffic = $lastmonth_traffic_count_main +$lastmonth_traffic_count_stover;
          $t_y_axis2[] = $last_month_traffic;
      }
        return [$t_y_axis2,$t_x_axis2];
    }
    public static function last3months($main_services=[],$stover_services=[])
    {
      $t_x_axis3 = [];
      $t_y_axis3 = [];
      $t_startperiod = \Carbon\Carbon::now()->startOfMonth();
      $t_endperiod = \Carbon\Carbon::now()->startOfMonth()->subMonths(2);
      $t_daterange = CarbonPeriod::create($t_endperiod, '1 month', $t_startperiod);
      foreach ($t_daterange as $month) {
          $t_x_axis3[] = $month->format('M-y');
          $t_monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
          $t_monthStartDate = $t_monthdatestring->startOfMonth()->format('Y-m-d');
          $t_monthEndDate = $t_monthdatestring->endOfMonth()->format('Y-m-d');
          // Main Routes Bookings
            $traffic_3months_count_main = Booking::whereBetween(self::$CreatedAt, [$t_monthStartDate.self::$StartDayTime, $t_monthEndDate.self::$EndDayTime])
            ->where(self::$route_type,self::$main_route)
            ->whereIn(self::$RoutesDepartureTimeId,$main_services)->where(self::$Status, 1)->count();
            // Stop Overs Routes Bookings
            $traffic_3months_count_stover = Booking::whereBetween(self::$CreatedAt, [$t_monthStartDate.self::$StartDayTime, $t_monthEndDate.self::$EndDayTime])
            ->where(self::$route_type,self::$stop_over_route)
            ->whereIn(self::$RoutesDepartureTimeId,$stover_services)->where(self::$Status, 1)->count();
           $month_traffic = $traffic_3months_count_main +$traffic_3months_count_stover;

          $t_y_axis3[] = $month_traffic;
        }
        return [$t_y_axis3,$t_x_axis3];
    }
    public static function last6months($main_services=[],$stover_services=[])
    {
      $t_x_axis4 = [];
      $t_y_axis4 = [];
      $t_startperiod = \Carbon\Carbon::now()->startOfMonth();
      $t_endperiod = \Carbon\Carbon::now()->startOfMonth()->subMonths(5);
      $t_daterange = CarbonPeriod::create($t_endperiod, '1 month', $t_startperiod);
      foreach ($t_daterange as $month) {
          $t_x_axis4[] = $month->format('M-y');
          $t_monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
          $t_monthStartDate = $t_monthdatestring->startOfMonth()->format('Y-m-d');
          $t_monthEndDate = $t_monthdatestring->endOfMonth()->format('Y-m-d');

          // Main Routes Bookings
            $traffic_6months_main = Booking::whereBetween(self::$CreatedAt, [$t_monthStartDate.self::$StartDayTime, $t_monthEndDate.self::$EndDayTime])
            ->where(self::$route_type,self::$main_route)
            ->whereIn(self::$RoutesDepartureTimeId,$main_services)->where(self::$Status, 1)->count();
            // Stop Overs Routes Bookings
            $traffic_6months_stover = Booking::whereBetween(self::$CreatedAt, [$t_monthStartDate.self::$StartDayTime, $t_monthEndDate.self::$EndDayTime])
            ->where(self::$route_type,self::$stop_over_route)
            ->whereIn(self::$RoutesDepartureTimeId,$stover_services)->where(self::$Status, 1)->count();
           $traffic_6month_count = $traffic_6months_main +$traffic_6months_stover;
          $t_y_axis4[] = $traffic_6month_count;
      }
        return [$t_y_axis4,$t_x_axis4];
    }

    public static function ThisYear($main_services=[],$stover_services=[])
    {
      $t_x_axis5 = [];
      $t_y_axis5 = [];
      $yearStartDate = \Carbon\Carbon::parse('first day of January');
      $yearEndDate = \Carbon\Carbon::parse('last day of December');
      $daterange = CarbonPeriod::create($yearStartDate, '1 month', $yearEndDate);
      foreach ($daterange as $month) {
          $t_x_axis5[] = $month->format('M-y');
          $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
          $startdate[] = $monthdatestring;
          $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
          $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');

          // Main Routes Bookings
            $year_traffic_main = Booking::whereBetween(self::$CreatedAt, [$monthStartDate.self::$StartDayTime, $monthEndDate.self::$EndDayTime])
            ->where(self::$route_type,self::$main_route)
            ->whereIn(self::$RoutesDepartureTimeId,$main_services)->where(self::$Status, 1)->count();
            // Stop Overs Routes Bookings
            $year_traffic_stover = Booking::whereBetween(self::$CreatedAt, [$monthStartDate.self::$StartDayTime, $monthEndDate.self::$EndDayTime])
            ->where(self::$route_type,self::$stop_over_route)
            ->whereIn(self::$RoutesDepartureTimeId,$stover_services)->where(self::$Status, 1)->count();
           $year_traffic = $year_traffic_main +$year_traffic_stover;
          $t_y_axis5[] = $year_traffic;
      }
        return [$t_y_axis5,$t_x_axis5];
    }
}
