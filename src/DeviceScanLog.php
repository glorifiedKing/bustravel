<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;


class DeviceScanLog extends Model
{ 
    protected $casts=[
        'request_attributes' => 'array'
    ];  
    
    public function device()
    {
        return $this->belongsTo(TicketScanner::class,'device_id');
    }
    
}