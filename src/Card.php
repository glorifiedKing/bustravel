<?php

namespace glorifiedking\BusTravel;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $guarded = [];

    public static $rules = [
        'identifier'     => 'required|unique:cards',
        'balance' => 'required|integer',
    ];
}
