<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use glorifiedking\BusTravel\BookingCustomField;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(BookingCustomField::class, function (Faker $faker) {
    return [
        "operator_id" => $faker->company,
        "field_prefix" => "name",
        "field_name" => "name",
        "is_required" => 1,
        "status"=>1,
    ];
});
