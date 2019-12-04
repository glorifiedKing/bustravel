<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use glorifiedking\BusTravel\RoutesDepartureTime;

$factory->define(RoutesDepartureTime::class, function (Faker $faker) {
    return [
        'route_id'                           => 1,
        'departure_time'                     => $faker->time($format = 'H:i:s', $max = 'now'),
        'bus_id'                             => 1,
        'driver_id'                          => 1,
        'restricted_by_bus_seating_capacity' => 1,
        'status'                             => 1,
    ];
});
