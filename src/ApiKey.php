<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $guarded = [];
    //
    protected $casts = [
        'ip_addresses' => 'array',
    ];
}
