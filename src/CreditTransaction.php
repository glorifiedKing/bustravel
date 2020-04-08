<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class CreditTransaction extends Model
{
    protected $guarded = [];
    //
    public function payment_transaction()
    {
        return $this->belongsTo(PaymentTransaction::class,'transaction_id');
    }
}