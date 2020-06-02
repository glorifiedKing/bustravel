<?php

namespace glorifiedking\BusTravel\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class CreateServicesRequest extends FormRequest
{

    public function authorize()
    {
      return true;
    }

    public function rules()
    {
      $rules = [
        'route_id'       => 'required',
        'departure_time' => 'required',
        'arrival_time' => 'required',
        "days_of_week"    => "required|array",
        'days_of_week.*' => 'required',
   ];
   if ($this->has_stover == 1) {
       $rules1=[
       "stopover_arrival_time3"    => "required|array",
       'stopover_arrival_time.*' => 'required',
       "stopover_departure_time3"    => "required|array",
       'stopover_departure_time.*' => 'required'
     ];
      $data= array_merge($rules,$rules1);
    }else{
      $data =$rules;
    }
      return $data;
    }
}
