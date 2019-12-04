<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use glorifiedking\BusTravel\Bus;
use Illuminate\Support\Str;

$factory->define(Bus::class, function (Faker $faker) {
    return [
        'operator_id'         => 1,
        'number_plate'        => strtoupper(Str::random(8)),
        'seating_capacity'    => 60,
        'description'         => $faker->realText,
        'seating_arrangement' => $faker->realText,
        'status'              => 1,
    ];
});
