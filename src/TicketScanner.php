<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;


class TicketScanner extends Model
{ 
    protected $casts=[
        'description' => 'array'
    ];   
    public function operator()
    {
        return $this->belongsTo(Operator::class,'operator_id');
    }

    public function logs()
    {
        return $this->hasMany(DeviceScanLog::class,'device_id');
    }
}