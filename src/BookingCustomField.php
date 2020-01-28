<?php

namespace glorifiedking\BusTravel;
use glorifiedking\BusTravel\Traits\OperatorTrait;
use Illuminate\Database\Eloquent\Model;

class BookingCustomField extends Model
{
     use OperatorTrait;
    protected $guarded = [];
    // validation
    public static $rules = [
    'field_name'  => 'required',
  ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
