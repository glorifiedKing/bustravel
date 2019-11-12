<?php
 namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class RoutesDepartureTime extends Model
{
  protected $guarded = [];
  // validation
  public static $rules = array(
    'route_id' => 'required',
    'departure_time' => 'required',
  );
  public function route()
   {
       return $this->belongsTo(Route::class);
   }
   public function driver()
    {
        return $this->belongsTo(Driver::class,'driver_id');
    }
    public function bus()
     {
         return $this->belongsTo(Bus::class,'bus_id');
     }

}