<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $guarded = [];
    // validation
    public static $rules = [
    'operator_id'   => 'required',
    'start_station' => 'required',
    'end_station'   => 'required',
    'start_station' => 'required',
    'price'         => 'required',
  ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function start()
    {
        return $this->belongsTo(Station::class, 'start_station');
    }

    public function end()
    {
        return $this->belongsTo(Station::class, 'end_station');
    }

    public function departure_times()
    {
        return $this->hasMany(RoutesDepartureTime::class, 'route_id');
    }

    public function stopovers()
    {
        return $this->hasMany(StopoverRoute::class, 'route_id');
    }
}
