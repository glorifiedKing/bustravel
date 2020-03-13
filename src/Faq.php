<?php

namespace glorifiedking\BusTravel;
use glorifiedking\BusTravel\Traits\OperatorTrait;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $guarded = [];
    // validation
    public static $rules = [
    'question'  => 'required',
    'answer'  => 'required',
  ];

}
