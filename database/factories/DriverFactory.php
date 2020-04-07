<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use glorifiedking\BusTravel\Driver;
use Illuminate\Support\Str;

$factory->define(Driver::class, function (Faker $faker) {
    return [
            'user_id'       => 1,
            'operator_id'       => 1,
            'name'              => $faker->name,
            'nin'               => strtoupper(Str::random(13)),
            'date_of_birth'     => $faker->date($format = 'Y-m-d'),
            'driving_permit_no' => strtoupper(Str::random(8)),
            'picture'           => $faker->imageUrl,
            'phone_number'      => $faker->phoneNumber,
            'address'           => $faker->address,
            'status'            => 1,
    ];
});
