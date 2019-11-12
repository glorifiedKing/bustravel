<?php
 namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
  protected $guarded = [];
  // validation
  public static $rules = array(
    'routes_departure_time_id' => 'required',
    'amount' => 'required',
    'user_id' => 'required',
    'date_of_travel' => 'required',
  );

  public function route_departure_time()
   {
       return $this->belongsTo(RoutesDepartureTime::class,'routes_departure_time_id');
   }
}
