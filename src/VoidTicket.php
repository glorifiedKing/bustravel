<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class VoidTicket extends Model
{
    protected $guarded = [];
    // validation


    public function void_booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
