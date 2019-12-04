<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use glorifiedking\BusTravel\Route;

$factory->define(Route::class, function (Faker $faker) {
    return [
        'operator_id'   => 1,
        'start_station' => 1,
        'end_station'   => 2,
        'price'         => '25000',
        'return_price'  => '25000',
        'status'        => 1,
    ];
});
