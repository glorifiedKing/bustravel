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
    

    public function number_of_seats_left($date_of_travel)
    {
        $bus_capacity = $this->bus->seating_capacity ?? 61;
       $bookings = Booking::where([
            ['routes_departure_time_id','=',$this->id],
            ['route_type','=','main_route'],
            ['date_of_travel','=',$date_of_travel]
            ])->count();
           
        $stops = $this->stopovers_times();
        foreach($stops as $stop)
        {
            $stop_booking_count = Booking::where([
                ['routes_departure_time_id','=',$stop->id],
                ['route_type','=','stop_over_route'],
                ['date_of_travel','=',$date_of_travel]
                ])->count();
            $bookings += $stop_booking_count;    
        }
        $seats_left = $bus_capacity - $bookings ?? 0;
        return $seats_left;

    }
}
