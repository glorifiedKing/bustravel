<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class RoutesDepartureTime extends Model
{
    protected $guarded = [];
    // validation
    public static $rules = [
    'route_id'       => 'required',
    'departure_time' => 'required',
    'arrival_time' => 'required',
  ];
    protected $casts = [
        'days_of_week' => 'array'
    ];

    public function route()
    {
        return $this->belongsTo(Route::class,'route_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }
    public function stopovers_times()
    {
        return $this->hasMany(RoutesStopoversDepartureTime::class, 'routes_times_id');
    }
}
