<?php

namespace glorifiedking\BusTravel;

class ListBookings
{


    public static function list($main_bookings=[],$stop_over_bookings=[])
    {
     $bookings= [];
     //mainroute bookings
      foreach($main_bookings as $booking) {
        $result_array = array(
            'id' => $booking->id,
            'ticket_number' => $booking->ticket_number,
            'boarded' => $booking->boarded,
            'date_paid' => $booking->date_paid,
            'date_of_travel' => $booking->date_of_travel,
            'boarding_station'=>$booking->route_departure_time->route->start->name??'',
            'comingoff_station'=>$booking->route_departure_time->route->end->name??'',
            'status' => $booking->status,
            'created_at' => $booking->created_at,
            'amount'=>$booking->amount,
            'time'=>$booking->route_departure_time->departure_time??'',
            'operator'=>$booking->route_departure_time->route->operator->name??'',
            'start'=>$booking->route_departure_time->route->start->name??'',
            'end'=>$booking->route_departure_time->route->end->name??'',
        );
        $bookings[] = $result_array;
      }
      //stopovers bookings
      foreach($stop_over_bookings as $booking) {
        $result_array = array(
            'id' => $booking->id,
            'ticket_number' => $booking->ticket_number,
            'boarded' => $booking->boarded,
            'boarding_station'=>$booking->stop_over_route_departure_time->route->start_stopover_station->name??'',
            'comingoff_station'=>$booking->stop_over_route_departure_time->route->end_stopover_station->name??'',
            'date_paid' => $booking->date_paid,
            'date_of_travel' => $booking->date_of_travel,
            'status' => $booking->status,
            'created_at' => $booking->created_at,
            'amount'=>$booking->amount,
            'time'=>$booking->stop_over_route_departure_time->departure_time??'',
            'operator'=>$booking->stop_over_route_departure_time->route->route->operator->name??'',
            'start'=>$booking->stop_over_route_departure_time->route->start_stopover_station->name??'',
            'end'=>$booking->stop_over_route_departure_time->route->end_stopover_station->name??'',
        );
        $bookings[] = $result_array;
      }

        return $bookings;
    }
}
