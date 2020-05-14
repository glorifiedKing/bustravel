<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;

class EmailTemplate extends Model
{
    use HasTrixRichText;
    protected $guarded = [];
    public function operator()
    {
        return $this->belongsTo(Operator::class,'operator_id');
    }
}