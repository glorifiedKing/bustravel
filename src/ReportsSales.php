<?php

namespace glorifiedking\BusTravel;
use Carbon\CarbonPeriod;
use glorifiedking\BusTravel\Booking;
class ReportsSales
{
   public static $CreatedAt="created_at",$Status="status",
   $RoutesDepartureTimeId='routes_departure_time_id',
   $StartDayTime=' 00:00:00', $EndDayTime=' 23:59:59',
   $route_type ="route_type", $main_route ="main_route",
   $stop_over_route="stop_over_route",$Amount='amount'

   ;


    public static function week($main_services=[],$stover_services=[])
    {
      $x_axis = [];
      $y_axis = [];
      $y_axis1 = [];
      $weekStartDate = \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d ');
      $weekEndDate = \Carbon\Carbon::now()->endOfWeek()->format('Y-m-d');

      $daterange = CarbonPeriod::create($weekStartDate, $weekEndDate);
      $weekdates = [];
      foreach ($daterange as $weekdate) {
          $weekdates[] = $weekdate->format('Y-m-d');
          $x_axis[] = $weekdate->format('D');
      }
      foreach ($weekdates as $wdates) {
            // Main Routes Bookings
            $weeksales_main = Booking::whereBetween(self::$CreatedAt, [$wdates.self::$StartDayTime, $wdates.self::$EndDayTime])->whereIn(self::$RoutesDepartureTimeId,$main_services)
            ->where(self::$route_type,self::$main_route)
            ->where(self::$Status, 1)->sum(self::$Amount);
            $weeksalescount_main = Booking::whereBetween(self::$CreatedAt, [$wdates.self::$StartDayTime, $wdates.self::$EndDayTime])
            ->where(self::$route_type,self::$main_route)
            ->whereIn(self::$RoutesDepartureTimeId,$main_services)->where(self::$Status, 1)->count();
            // Stop Overs Routes Bookings
            $weeksales_stover = Booking::whereBetween(self::$CreatedAt, [$wdates.self::$StartDayTime, $wdates.self::$EndDayTime])
            ->whereIn(self::$RoutesDepartureTimeId,$stover_services)
            ->where(self::$route_type,self::$stop_over_route)
            ->where(self::$Status, 1)->sum(self::$Amount);
            $weeksalescount_stover = Booking::whereBetween(self::$CreatedAt, [$wdates.self::$StartDayTime, $wdates.self::$EndDayTime])
            ->where(self::$route_type,self::$stop_over_route)
            ->whereIn(self::$RoutesDepartureTimeId,$stover_services)->where(self::$Status, 1)->count();
           $daysales = $weeksales_main +$weeksales_stover;
           $daysalescount = $weeksalescount_main +$weeksalescount_stover;
             $y_axis[] = $daysales;
          $y_axis1[] = $daysalescount;
      }
        return [$y_axis,$y_axis1,$x_axis];
    }

    public static function Thismonth($main_services=[],$stover_services=[])
    {
      $x_axis1 = [];
      $y_axis1 = [];
      $y_axis11 = [];
      $monthStartDate = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d ');
      $monthEndDate = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');

      $daterange = CarbonPeriod::create($monthStartDate, $monthEndDate);
      $monthdates = [];
      foreach ($daterange as $monthdate) {
          $monthdates[] = $monthdate->format('Y-m-d');
          $x_axis1[] = $monthdate->format('d');
      }
      foreach ($monthdates as $mdates) {
          // Main Routes Bookings
          $monthsales_main = Booking::whereBetween(self::$CreatedAt, [$mdates.self::$StartDayTime, $mdates.self::$EndDayTime])->whereIn(self::$RoutesDepartureTimeId,$main_services)
          ->where(self::$route_type,self::$main_route)
          ->where(self::$Status, 1)->sum(self::$Amount);
          $monthsalescount_main = Booking::whereBetween(self::$CreatedAt, [$mdates.self::$StartDayTime, $mdates.self::$EndDayTime])
          ->where(self::$route_type,self::$main_route)
          ->whereIn(self::$RoutesDepartureTimeId,$main_services)->where(self::$Status, 1)->count();
          // Stop Overs Routes Bookings
          $monthsales_stover = Booking::whereBetween(self::$CreatedAt, [$mdates.self::$StartDayTime, $mdates.self::$EndDayTime])
          ->whereIn(self::$RoutesDepartureTimeId,$stover_services)
          ->where(self::$route_type,self::$stop_over_route)
          ->where(self::$Status, 1)->sum(self::$Amount);
          $monthsalescount_stover = Booking::whereBetween(self::$CreatedAt, [$mdates.self::$StartDayTime, $mdates.self::$EndDayTime])
          ->where(self::$route_type,self::$stop_over_route)
          ->whereIn(self::$RoutesDepartureTimeId,$stover_services)->where(self::$Status, 1)->count();
         $monthsales = $monthsales_main +$monthsales_stover;
         $monthsalescount = $monthsalescount_main +$monthsalescount_stover;

          $y_axis1[] = $monthsales;
          $y_axis11[] = $monthsalescount;
      }
        return [$y_axis1,$y_axis11,$x_axis1];
    }
    public static function Lastmonth($main_services=[],$stover_services=[])
    {
      $x_axis2 = [];
      $y_axis2 = [];
      $y_axis12 = [];
      $monthStartDatestring = \Carbon\Carbon::now()->startOfMonth()->subMonth();
      $monthStartDate = $monthStartDatestring->startOfMonth()->format('Y-m-d');
      $monthEndDate = $monthStartDatestring->endOfMonth()->format('Y-m-d');

      $daterange = CarbonPeriod::create($monthStartDate, $monthEndDate);
      $monthdates = [];
      foreach ($daterange as $monthdate) {
          $monthdates[] = $monthdate->format('Y-m-d');
          $x_axis2[] = $monthdate->format('d');
      }
      foreach ($monthdates as $mdates) {
          // Main Routes Bookings
          $sales_last_month_main = Booking::whereBetween(self::$CreatedAt, [$mdates.self::$StartDayTime, $mdates.self::$EndDayTime])->whereIn(self::$RoutesDepartureTimeId,$main_services)
          ->where(self::$route_type,self::$main_route)
          ->where(self::$Status, 1)->sum(self::$Amount);
          $sales_last_month_count_main = Booking::whereBetween(self::$CreatedAt, [$mdates.self::$StartDayTime, $mdates.self::$EndDayTime])
          ->where(self::$route_type,self::$main_route)
          ->whereIn(self::$RoutesDepartureTimeId,$main_services)->where(self::$Status, 1)->count();
          // Stop Overs Routes Bookings
          $sales_last_month_stover = Booking::whereBetween(self::$CreatedAt, [$mdates.self::$StartDayTime, $mdates.self::$EndDayTime])
          ->whereIn(self::$RoutesDepartureTimeId,$stover_services)
          ->where(self::$route_type,self::$stop_over_route)
          ->where(self::$Status, 1)->sum(self::$Amount);
          $sales_last_month_count_stover = Booking::whereBetween(self::$CreatedAt, [$mdates.self::$StartDayTime, $mdates.self::$EndDayTime])
          ->where(self::$route_type,self::$stop_over_route)
          ->whereIn(self::$RoutesDepartureTimeId,$stover_services)->where(self::$Status, 1)->count();
         $sales_last_month = $sales_last_month_main +$sales_last_month_stover;
         $sales_last_month_count = $sales_last_month_count_main +$sales_last_month_count_stover;

        $y_axis2[] = $sales_last_month;
          $y_axis12[] = $sales_last_month_count;
      }
        return [$y_axis2,$y_axis12,$x_axis2];
    }
    public static function last3months($main_services=[],$stover_services=[])
    {
      $x_axis3 = [];
      $y_axis3 = [];
      $y_axis13 = [];

      $startperiod = \Carbon\Carbon::now()->startOfMonth();
      $endperiod = \Carbon\Carbon::now()->startOfMonth()->subMonths(2);
      $daterange = CarbonPeriod::create($endperiod, '1 month', $startperiod);
      foreach ($daterange as $month) {
          $x_axis3[] = $month->format('M-y');
          $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
          $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
          $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
            // Main Routes Bookings
            $sales_3months_main = Booking::whereBetween(self::$CreatedAt, [$monthStartDate.self::$StartDayTime, $monthEndDate.self::$EndDayTime])->whereIn(self::$RoutesDepartureTimeId,$main_services)
            ->where(self::$route_type,self::$main_route)
            ->where(self::$Status, 1)->sum(self::$Amount);
            $sales_3months_count_main = Booking::whereBetween(self::$CreatedAt, [$monthStartDate.self::$StartDayTime, $monthEndDate.self::$EndDayTime])
            ->where(self::$route_type,self::$main_route)
            ->whereIn(self::$RoutesDepartureTimeId,$main_services)->where(self::$Status, 1)->count();
            // Stop Overs Routes Bookings
            $sales_3months_stover = Booking::whereBetween(self::$CreatedAt, [$monthStartDate.self::$StartDayTime, $monthEndDate.self::$EndDayTime])
            ->whereIn(self::$RoutesDepartureTimeId,$stover_services)
            ->where(self::$route_type,self::$stop_over_route)
            ->where(self::$Status, 1)->sum(self::$Amount);
            $sales_3months_count_stover = Booking::whereBetween(self::$CreatedAt, [$monthStartDate.self::$StartDayTime, $monthEndDate.self::$EndDayTime])
            ->where(self::$route_type,self::$stop_over_route)
            ->whereIn(self::$RoutesDepartureTimeId,$stover_services)->where(self::$Status, 1)->count();
           $sales_3months = $sales_3months_main +$sales_3months_stover;
           $sales_3months_count = $sales_3months_count_main +$sales_3months_count_stover;

          $y_axis3[] = $sales_3months;
          $y_axis13[] = $sales_3months_count;
      }
        return [$y_axis3,$y_axis13,$x_axis3];
    }
    public static function last6months($main_services=[],$stover_services=[])
    {
      $x_axis4 = [];
      $y_axis4 = [];
      $y_axis14 = [];
      $startperiod = \Carbon\Carbon::now()->startOfMonth();
      $endperiod = \Carbon\Carbon::now()->startOfMonth()->subMonths(5);
      $daterange = CarbonPeriod::create($endperiod, '1 month', $startperiod);
      foreach ($daterange as $month) {
          $x_axis4[] = $month->format('M-y');
          $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
          $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
          $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
            // Main Routes Bookings
            $sales_6months_main = Booking::whereBetween(self::$CreatedAt, [$monthStartDate.self::$StartDayTime, $monthEndDate.self::$EndDayTime])->whereIn(self::$RoutesDepartureTimeId,$main_services)
            ->where(self::$route_type,self::$main_route)
            ->where(self::$Status, 1)->sum(self::$Amount);
            $sales_6months_count_main = Booking::whereBetween(self::$CreatedAt, [$monthStartDate.self::$StartDayTime, $monthEndDate.self::$EndDayTime])
            ->where(self::$route_type,self::$main_route)
            ->whereIn(self::$RoutesDepartureTimeId,$main_services)->where(self::$Status, 1)->count();
            // Stop Overs Routes Bookings
            $sales_6months_stover = Booking::whereBetween(self::$CreatedAt, [$monthStartDate.self::$StartDayTime, $monthEndDate.self::$EndDayTime])
            ->whereIn(self::$RoutesDepartureTimeId,$stover_services)
            ->where(self::$route_type,self::$stop_over_route)
            ->where(self::$Status, 1)->sum(self::$Amount);
            $sales_6months_count_stover = Booking::whereBetween(self::$CreatedAt, [$monthStartDate.self::$StartDayTime, $monthEndDate.self::$EndDayTime])
            ->where(self::$route_type,self::$stop_over_route)
            ->whereIn(self::$RoutesDepartureTimeId,$stover_services)->where(self::$Status, 1)->count();
           $sales_6months = $sales_6months_main +$sales_6months_stover;
           $sales_6months_count = $sales_6months_count_main +$sales_6months_count_stover;
          $y_axis4[] = $sales_6months;
          $y_axis14[] = $sales_6months_count;
      }
        return [$y_axis4,$y_axis14,$x_axis4];
    }

    public static function ThisYear($main_services=[],$stover_services=[])
    {
      $x_axis5 = [];
      $y_axis5 = [];
      $y_axis15 = [];
      $yearStartDate = \Carbon\Carbon::parse('first day of January');
      $yearEndDate = \Carbon\Carbon::parse('last day of December');
      $daterange = CarbonPeriod::create($yearStartDate, '1 month', $yearEndDate);
      foreach ($daterange as $month) {
          $x_axis5[] = $month->format('M-y');
          $monthdatestring = \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
          $startdate[] = $monthdatestring;
          $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
          $monthEndDate = $monthdatestring->endOfMonth()->format('Y-m-d');
            // Main Routes Bookings
            $year_sales_main = Booking::whereBetween(self::$CreatedAt, [$monthStartDate.self::$StartDayTime, $monthEndDate.self::$EndDayTime])->whereIn(self::$RoutesDepartureTimeId,$main_services)
            ->where(self::$route_type,self::$main_route)
            ->where(self::$Status, 1)->sum(self::$Amount);
            $year_sales_count_main = Booking::whereBetween(self::$CreatedAt, [$monthStartDate.self::$StartDayTime, $monthEndDate.self::$EndDayTime])
            ->where(self::$route_type,self::$main_route)
            ->whereIn(self::$RoutesDepartureTimeId,$main_services)->where(self::$Status, 1)->count();
            // Stop Overs Routes Bookings
            $year_sales_stover = Booking::whereBetween(self::$CreatedAt, [$monthStartDate.self::$StartDayTime, $monthEndDate.self::$EndDayTime])
            ->whereIn(self::$RoutesDepartureTimeId,$stover_services)
            ->where(self::$route_type,self::$stop_over_route)
            ->where(self::$Status, 1)->sum(self::$Amount);
            $year_sales_count_stover = Booking::whereBetween(self::$CreatedAt, [$monthStartDate.self::$StartDayTime, $monthEndDate.self::$EndDayTime])
            ->where(self::$route_type,self::$stop_over_route)
            ->whereIn(self::$RoutesDepartureTimeId,$stover_services)->where(self::$Status, 1)->count();
           $year_sales = $year_sales_main +$year_sales_stover;
           $year_sales_count = $year_sales_count_main +$year_sales_count_stover;
          $y_axis5[] = $year_sales;
          $y_axis15[] = $year_sales_count;
      }
        return [$y_axis5,$y_axis15,$x_axis5];
    }
}
