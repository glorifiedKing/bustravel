<?php

namespace glorifiedking\BusTravel\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class CreateRouteRequest extends FormRequest
{

    public function authorize()
    {
      return true;
    }

    public function rules()
    {
      $rules = [
        'routes' => 'required|array',
        'routes.*.from' => 'required|exists:stations,id',
        'routes.*.to' => 'required|exists:stations,id',
        'routes.*.in' => 'required|date_format:H:i',
        'routes.*.out' => 'required|date_format:H:i,after:in',
        'routes.*.price' => 'required|numeric',
        'routes.*.order' => 'required|integer',
        "days_of_week"    => "required|array",
        'days_of_week.*' => 'required',
   ];

      return $rules;
    }
}
