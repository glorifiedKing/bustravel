<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;


class Printer extends Model
{ 
    protected $table='operator_printers';   
    public function operator()
    {
        return $this->belongsTo(Operator::class,'operator_id');
    }
}