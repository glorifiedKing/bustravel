<?php

namespace glorifiedking\BusTravel;
use glorifiedking\BusTravel\Traits\OperatorTrait;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
  use OperatorTrait;
    protected $guarded = [];
    // validation
    public static $rules = [
    'email'              => 'required|unique:users',
    'name'              => 'required',
    'nin'               => 'required|unique:drivers',
    'date_of_birth'     => 'required',
    'driving_permit_no' => 'required|unique:drivers',
    'phone_number'      => 'required',
    'address'           => 'required',
  ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
