<?php

namespace glorifiedking\BusTravel;
use glorifiedking\BusTravel\Booking;
use glorifiedking\BusTravel\RoutesDepartureTime;
use glorifiedking\BusTravel\RoutesStopoversDepartureTime;
use glorifiedking\BusTravel\BookingsField;
use glorifiedking\BusTravel\VoidTicket;
use glorifiedking\BusTravel\ToastNotification;
use Illuminate\Support\Facades\Auth;
class NewBooking
{


    public static function new($id=0,$route_id=0,$route_type="",$date_of_travel="",$booking=[])
    {

      //$route_id = request()->input('service');
      //$route_type = request()->input('route_type');

      $departure_time = ($route_type == 'main_route') ? RoutesDepartureTime::find($route_id) : RoutesStopoversDepartureTime::find($route_id);
    //  $date_of_travel=request()->input('date_of_travel')??date('Y-m-d');

      $operator = $departure_time->route->operator ?? $departure_time->route->route->operator;
      $route_time_id =$departure_time->id??$departure_time->routes_times_id;

      $day_of_travel =\Carbon\Carbon::parse($date_of_travel)->format('l');
      $avialable_service= RoutesDepartureTime::where('id',$route_time_id)
      ->where('days_of_week', 'like', "%$day_of_travel%")->first();
      if(!$avialable_service){
        //dd($day_of_travel);
        $error ='failed';
        return $error;
        //return redirect()->route('bustravel.bookings.edit', $id)->with(ToastNotification::toast('This service is not avialable on this date, '.$date_of_travel,'Change Service','error'));
      }
          $pad_length = 6;
          $pad_char = 0;
          $str_type = 'd'; // treats input as integer, and outputs as a (signed) decimal number
          $pad_format = "%{$pad_char}{$pad_length}{$str_type}"; // or "%04d"
          $new_booking = new Booking();
          $new_booking->routes_departure_time_id = $departure_time->id;
          $new_booking->amount = $departure_time->route->price;
          $new_booking->date_paid = $booking->date_paid;
          $new_booking->date_of_travel = $date_of_travel;
          $new_booking->time_of_travel = $departure_time->departure_time;
          $new_ticket_number = $operator->code.date('y').sprintf($pad_format, $booking->getNextId());
          $new_booking->ticket_number = $new_ticket_number;
          $new_booking->user_id = Auth::user()->id;
          $new_booking->status = $booking->status;
          $new_booking->route_type = $route_type;
          $new_booking->payment_source = $booking->payment_source;
          $new_booking->save();

          $fields_values = request()->input('field_value') ?? 0;
          $fields_id = request()->input('field_id') ?? 0;

          if ($fields_values != 0) {
              foreach ($fields_values as $index =>  $fields_value) {
                  $custom_fields = new BookingsField();
                  $custom_fields->booking_id = $new_booking->id;
                  $custom_fields->field_id = $fields_id[$index];
                  $custom_fields->field_value = $fields_values[$index];
                  $custom_fields->save();
              }
          }
          $void_new1 = new VoidTicket();
          $void_new1->booking_id=$booking->id;
          $void_new1->void_reason ='Canceled Ticket , New Ticket: '.$new_booking->ticket_number;
          $void_new1->user_id=auth()->user()->id;
          $void_new1->save();

          $booking->status=2;
          $booking->save();
  return $new_booking->id;
}

}
