<?php
 namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
  protected $guarded = [];
  // validation
  public static $rules = array(
    'name' => 'required',
    'phone_number' => 'required',
    'contact_person_name' => 'required',
    'address' => 'required',
  );

}
