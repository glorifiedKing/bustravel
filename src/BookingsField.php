<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class BookingsField extends Model
{
    protected $guarded = [];
    // validation
    public static $rules = [
    'booking_id' => 'required',
    'field_id'   => 'required',
  ];
}
