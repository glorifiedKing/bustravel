<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use glorifiedking\BusTravel\Driver;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Driver::class, function (Faker $faker) {
    return [
            "operator_id" => 1,
            "name" => $faker->name,
            "nin" => strtoupper(Str::random(13)),
            "date_of_birth" => $faker->date($format = 'Y-m-d'),
            "driving_permit_no" => strtoupper(Str::random(8)),
            "picture"=>$faker->imageUrl,
            "phone_number" => $faker->phoneNumber,
            "address" => $faker->address,
            "status" => 1,
    ];
});
