<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use glorifiedking\BusTravel\Route;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Route::class, function (Faker $faker) {
    return [
        "operator_id" => 1,
        "start_station" => 1,
        "end_station" => 2,
        "price" => "25000",
        "return_price" => "25000",
        "status"=>1,
    ];
});
