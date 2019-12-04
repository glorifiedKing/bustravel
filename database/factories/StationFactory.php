<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use glorifiedking\BusTravel\Station;
use Illuminate\Support\Str;

$factory->define(Station::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'code' => strtoupper(Str::random(3)),
    ];
});
