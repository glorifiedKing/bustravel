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
}
