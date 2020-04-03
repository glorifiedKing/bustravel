<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class StopoverStation extends Model
{
    protected $guarded = [];

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    public function start_stopover_station()
    {
        return $this->belongsTo(Station::class, 'start_station');
    }

    public function end_stopover_station()
    {
        return $this->belongsTo(Station::class, 'end_station');
    }

    public function departure_times()
    {
        return $this->hasMany(RoutesStopoversDepartureTime::class, 'route_stopover_id');
    }

    public function start()
    {
        return $this->belongsTo(Station::class, 'start_station');
    }

    public function end()
    {
        return $this->belongsTo(Station::class, 'end_station');
    }
}
