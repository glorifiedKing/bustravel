<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class RoutesStopoversDepartureTime extends Model
{
    protected $guarded = [];

    public function route_stopover()
    {
        return $this->belongsTo(StopoverStation::class, 'route_stopover_id');
    }

    public function route()
    {
        return $this->belongsTo(StopoverStation::class, 'route_stopover_id');
    }

    public function main_route_departure_time()
    {
        return $this->belongsTo(RoutesDepartureTime::class,'routes_times_id');
    }
}
