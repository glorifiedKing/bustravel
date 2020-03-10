<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use glorifiedking\BusTravel\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => 'password', // password
        'remember_token' => Str::random(10),
        'phone_number' => $faker->phoneNumber,
        'status' => 1,
        'operator_id' => 1,


    //    'api_token' => Str::random(60),
    //    'is_admin' => false,
    ];
});

$factory->state(User::class, 'admin', [
    'is_admin' => true,
]);
