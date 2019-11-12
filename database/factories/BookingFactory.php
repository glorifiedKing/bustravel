<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use glorifiedking\BusTravel\Booking;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Booking::class, function (Faker $faker) {
    return [
        "routes_departure_time_id" => 1,
        "amount" => "25000",
        "date_paid" => $faker->date($format = 'Y-m-d'),
        "date_of_travel" => $faker->date($format = 'Y-m-d'),
        "time_of_travel" => $faker->time($format = 'H:i:s', $max = 'now'),
        "ticket_number" => "RAT/001",
        "user_id" => 1,
        "status"=>1,
    ];
});
