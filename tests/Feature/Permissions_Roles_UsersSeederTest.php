<?php

namespace glorifiedking\BusTravel\Tests;

use Artisan;
use glorifiedking\BusTravel\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Permissions_Roles_UsersSeederTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testPermissions()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $response = $this->actingAs($user, 'web')->get('/transit/users/permissions');
        $response->assertSee('Manage BT Stations');
        // ...
    }

    public function testRole()
    {
       Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
       $user = factory(User::class)->create();
       $user->assignRole('BT Super Admin');
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $response = $this->actingAs($user, 'web')->get('/transit/users/roles');
        $response->assertSee('BT Super Admin');
        // ...
    }

    public function testuserhasrole()
    {
       Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
       $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $response = $this->actingAs($user, 'web')->get('/transit/users');
        $response->assertSee('BT Super Admin');
        // ...
    }
}
