<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $guarded = [];
    //
    protected $casts = [
        'main_routes' => 'array',
        'stop_over_routes' => 'array'
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class,'transport_operator_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class,'payment_transaction_id');
    }

    public function services()
    {
        $services = array();
        if(!is_null($this->main_routes))
        {
            
            for($i=0;$i<count($this->main_routes);$i++)
            {
                $service = RoutesDepartureTime::find($this->main_routes[$i]);
                if($service) {
                    $service_array = [
                        'from' => $service->route->start->name,
                        'to' => $service->route->end->name,
                        'time' => $service->departure_time,
                    ];
                    $services[] = $service_array;
                }
            }
        }

        if(!is_null($this->stop_over_routes))
        {
            for($i=0;$i<count($this->stop_over_routes);$i++)
            {
                $service = RoutesStopoversDepartureTime::find($this->stop_over_routes[$i]);
                if($service) {
                    $service_array = [
                        'from' => $service->route->route->start->name,
                        'to' => $service->route->route->end->name,
                        'time' => $service->departure_time,
                    ];
                    $services[] = $service_array;
                }
            }
        }

        return $services;

    }
}
