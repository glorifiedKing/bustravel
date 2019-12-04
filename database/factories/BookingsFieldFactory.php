<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use glorifiedking\BusTravel\BookingsField;

$factory->define(BookingsField::class, function (Faker $faker) {
    return [
        'booking_id'  => 1,
        'field_id'    => 1,
        'field_value' => 'Musana Joseph',
    ];
});
