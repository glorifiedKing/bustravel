<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class StopoverRoute extends Model
{
    protected $guarded = [];

    public function stopover_route()
    {
        return $this->belongsTo(Route::class, 'stopover_id');
    }
}
