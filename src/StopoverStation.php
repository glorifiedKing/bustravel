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
}
