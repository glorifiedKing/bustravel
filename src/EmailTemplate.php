<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    //
    public function operator()
    {
        return $this->belongsTo(Operator::class,'operator_id');
    }
}