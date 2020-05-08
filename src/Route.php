<?php

namespace glorifiedking\BusTravel;
use glorifiedking\BusTravel\Traits\OperatorTrait;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
  use OperatorTrait;
    protected $guarded = [];
    // validation
    public static $rules = [
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
        return $this->hasMany(StopoverStation::class, 'route_id');
    }
    

    public function delete()
    {
        // delete all related departure times
        $this->departure_times()->delete();
        // delete all related stop overs
        $this->stopovers()->delete();

        return parent::delete();
    }
}
