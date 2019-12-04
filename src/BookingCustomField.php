<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class BookingCustomField extends Model
{
    protected $guarded = [];
    // validation
    public static $rules = [
    'operator_id' => 'required',
    'field_name'  => 'required',
  ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
