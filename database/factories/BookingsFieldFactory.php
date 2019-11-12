<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use glorifiedking\BusTravel\BookingsField;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(BookingsField::class, function (Faker $faker) {
    return [
        "booking_id" => 1,
        "field_id" => 1,
        "field_value" => "Musana Joseph",
    ];
});
