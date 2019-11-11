<?php
 namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
  protected $guarded = [];
  // validation
  public static $rules = array(
    'operator_id' => 'required',
    'start_station' => 'required',
    'end_station' => 'required',
    'start_station' => 'required',
    'price' => 'required',
  );
  public function operator()
   {
       return $this->belongsTo(Operator::class);
   }
   public function start()
    {
        return $this->belongsTo(Station::class,'start_station');
    }
   public function end()
    {
        return $this->belongsTo(Station::class,'end_station');
    }

}
