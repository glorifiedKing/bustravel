<?php

namespace glorifiedking\BusTravel;
use glorifiedking\BusTravel\Traits\OperatorTrait;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
  use OperatorTrait;
    protected $guarded = [];
    // validation
    public static $rules = [
    'number_plate'     => 'required|unique:buses',
    'seating_capacity' => 'required|integer',
    'seating_format' => 'required',
    'first_row_count' => 'required',
  ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
