<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $guarded = [];
    // validation
    public static $rules = [
    'operator_id'       => 'required',
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
