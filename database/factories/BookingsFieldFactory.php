<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use glorifiedking\BusTravel\BookingsField;

$factory->define(BookingsField::class, function () {
    return [
        'booking_id'  => 1,
        'field_id'    => 1,
        'field_value' => 'Musana Joseph',
    ];
});
