<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class RouteTracking extends Model
{
    protected $guarded = [];
    // validation
  

    public function route_departure_time()
    {
        return $this->belongsTo(RoutesDepartureTime::class, 'routes_departure_time_id');
    }
}
