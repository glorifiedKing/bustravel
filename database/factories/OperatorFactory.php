<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use glorifiedking\BusTravel\Operator;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Operator::class, function (Faker $faker) {
    return [
        "name" => $faker->company,
        "address" => $faker->address,
        "code" => strtoupper(Str::random(5)),
        "logo" => $faker->imageUrl,
        "email" => $faker->unique()->safeEmail,
        "contact_person_name" => $faker->name,
        "phone_number" => $faker->phoneNumber,
        "status"=>1,
    ];
});
