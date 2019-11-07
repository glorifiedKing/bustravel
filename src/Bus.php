<?php
 namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
  protected $guarded = [];
  // validation
  public static $rules = array(
    'operator_id' => 'required',
    'number_plate' => 'required',
    'seating_capacity' => 'required|integer',
  );
  public function operator()
   {
       return $this->belongsTo(Operator::class);
   }

}
