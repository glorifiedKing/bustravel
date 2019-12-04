<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $guarded = [];
    // validation
    public static $rules = [
    'operator_id'      => 'required',
    'number_plate'     => 'required|unique:buses',
    'seating_capacity' => 'required|integer',
  ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
