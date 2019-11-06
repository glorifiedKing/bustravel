<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use glorifiedking\BusTravel\Station;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Station::class, function (Faker $faker) {
    return [
        "name" => $faker->name,
        "code" => strtoupper(Str::random(3)),
    ];
});
