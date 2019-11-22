<?php
namespace glorifiedking\BusTravel\Http\Controllers;
use Illuminate\Routing\Controller;
use glorifiedking\BusTravel\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use File;
use Carbon\CarbonPeriod;
class ReportsController extends Controller
{
  public function __construct()
  {
      $this->middleware('web');
      $this->middleware('auth');
  }
 //fetching buses route('bustravel.buses')
  public function sales()
  {
    if(request()->isMethod('post'))
     {

     }
     $period =request()->input('period')??1;

     if($period ==1)
     {
       $now = \Carbon\Carbon::now();
       $weekStartDate = $now->startOfWeek()->format('Y-m-d ');
       $weekEndDate = $now->endOfWeek()->format('Y-m-d');

       $daterange = CarbonPeriod::create($weekStartDate, $weekEndDate);
       $weekdates =[];
       $x_axis =[];
       foreach ($daterange as $weekdate) {
        $weekdates[] = $weekdate->format('Y-m-d');
        $x_axis[] = $weekdate->format('D');
       }
        $y_axis =[];
        foreach($weekdates as $wdates)
        {
           $daysales =Booking::whereBetween('created_at', array($wdates." 00:00:00", $wdates." 23:59:59"))->where('status',1)->sum('amount');
        $y_axis[] = $daysales;
        }
      }elseif($period==2)
      {
        $now = \Carbon\Carbon::now();
        $monthStartDate = $now->startOfMonth()->format('Y-m-d ');
        $monthEndDate = $now->endOfMonth()->format('Y-m-d');

        $daterange = CarbonPeriod::create($monthStartDate, $monthEndDate);
        $monthdates =[];
        $x_axis =[];
        foreach ($daterange as $monthdate) {
         $monthdates[] = $monthdate->format('Y-m-d');
         $x_axis[] = $monthdate->format('d');
        }

         $y_axis =[];
         foreach($monthdates as $mdates)
         {
            $daysales =Booking::whereBetween('created_at', array($mdates." 00:00:00", $mdates." 23:59:59"))->where('status',1)->sum('amount');
         $y_axis[] = $daysales;
         }
       }elseif($period==3)
       {
         $now = \Carbon\Carbon::now();
         $monthStartDatestring = $now->startOfMonth()->subMonth();
         $monthStartDate = $monthStartDatestring->startOfMonth()->format('Y-m-d');
         $monthEndDate = $monthStartDatestring->endOfMonth()->format('Y-m-d');

         $daterange = CarbonPeriod::create($monthStartDate, $monthEndDate);
         $monthdates =[];
         $x_axis =[];
         foreach ($daterange as $monthdate) {
          $monthdates[] = $monthdate->format('Y-m-d');
          $x_axis[] = $monthdate->format('d');
         }
          $y_axis =[];
          foreach($monthdates as $mdates)
          {
             $daysales =Booking::whereBetween('created_at', array($mdates." 00:00:00", $mdates." 23:59:59"))->where('status',1)->sum('amount');
          $y_axis[] = $daysales;
          }
       }elseif($period==4)
       {
         $x_axis =[];
         $y_axis =[];
         $now = \Carbon\Carbon::now();
         //$monthStartDatestring = $now->startOfMonth();
         $startperiod = \Carbon\Carbon::now()->endOfMonth();
         $endperiod = \Carbon\Carbon::now()->endOfMonth()->subMonths(3);
         $daterange = CarbonPeriod::create($endperiod ,'1 month', $startperiod);
         foreach ($daterange as $month) {
         $x_axis[] =$month->format('M-y');
         $monthdatestring =  \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
         $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
         $monthEndDate =   $monthdatestring->endOfMonth()->format('Y-m-d');
         $monthsales =Booking::whereBetween('created_at', array($monthStartDate." 00:00:00", $monthEndDate." 23:59:59"))->where('status',1)->sum('amount');
         $y_axis[] =$monthsales;
         }

       }elseif($period==5)

        {

          $x_axis =[];
          $y_axis =[];
          $now = \Carbon\Carbon::now();
          //$monthStartDatestring = $now->startOfMonth();
          $startperiod = \Carbon\Carbon::now()->endOfMonth();
          $endperiod = \Carbon\Carbon::now()->endOfMonth()->subMonths(6);
          $daterange = CarbonPeriod::create($endperiod ,'1 month', $startperiod);
          foreach ($daterange as $month) {
          $x_axis[] =$month->format('M-y');
          $monthdatestring =  \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
          $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
          $monthEndDate =   $monthdatestring->endOfMonth()->format('Y-m-d');
          $monthsales =Booking::whereBetween('created_at', array($monthStartDate." 00:00:00", $monthEndDate." 23:59:59"))->where('status',1)->sum('amount');
          $y_axis[] =$monthsales;
          }
        }elseif($period==6)
          {
            $x_axis =[];
            $y_axis =[];
            $yearStartDate = \Carbon\Carbon::parse('first day of January');
            $yearEndDate = \Carbon\Carbon::parse('last day of December');
            dd($yearEndDate );

            $daterange = CarbonPeriod::create($yearStartDate,'1 month', $yearEndDate);
            foreach ($daterange as $month) {
            $x_axis[] =$month->format('M-y');
            $monthdatestring =  \Carbon\Carbon::createFromDate($month->format('Y'), $month->format('m'), 01);
            $startdate[] =$monthdatestring;
            $monthStartDate = $monthdatestring->startOfMonth()->format('Y-m-d');
            $monthEndDate =   $monthdatestring->endOfMonth()->format('Y-m-d');
            $monthsales =Booking::whereBetween('created_at', array($monthStartDate." 00:00:00", $monthEndDate." 23:59:59"))->where('status',1)->sum('amount');
            $y_axis[] =$monthsales;
            }

           }

      return view('bustravel::backend.reports.sales',compact('x_axis','y_axis','period'));
  }

}
