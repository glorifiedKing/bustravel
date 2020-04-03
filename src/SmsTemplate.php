<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    //
    public function operator()
    {
        return $this->belongsTo(Operator::class,'operator_id');
    }
}