<?php

namespace glorifiedking\BusTravel\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class CreateBookingRequest extends FormRequest
{

    public function authorize()
    {
      return true;
    }

    public function rules()
    {
      $rules = [
        'route_id' => 'required',
        'route_type' => 'required',
        'amount'     => 'required',
        'printer'    =>   'required',
   ];

      return $rules;
    }
}
