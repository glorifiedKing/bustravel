<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */


use Spatie\Permission\Models\Permission;

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

$factory->define(Permission::class, function () {
    return [
        'name'       => 'Edit_User',
        'guard_name' => 'web',
    ];
});
