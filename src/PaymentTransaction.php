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
}
