<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];
    // validation
    public static $rules = [
    'routes_departure_time_id' => 'required',
    'amount'                   => 'required',
    'user_id'                  => 'required',
    'date_of_travel'           => 'required',
  ];

    public function route_departure_time()
    {
        return $this->belongsTo(RoutesDepartureTime::class, 'routes_departure_time_id');
    }
    public function stop_over_route_departure_time()
    {
        return $this->belongsTo(RoutesStopOversDepartureTime::class, 'routes_departure_time_id');
    }
    

    public function getNextId() 
   {

     $statement = \DB::select("show table status like 'bookings'");

     return $statement[0]->Auto_increment;
   }
}
