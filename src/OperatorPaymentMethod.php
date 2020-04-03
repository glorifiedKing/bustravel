<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class OperatorPaymentMethod extends Model
{
    //
    public function operator()
    {
        return $this->belongsTo(Operator::class,'operator_id');
    }
}
