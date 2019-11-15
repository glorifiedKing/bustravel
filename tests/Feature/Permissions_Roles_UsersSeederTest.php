<?php
namespace glorifiedking\BusTravel\Tests;
use glorifiedking\BusTravel\Tests\TestCase;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use glorifiedking\BusTravel\User;
use Artisan;
class Permission_Roles_UsersSeederTest extends TestCase
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
         $user = factory(User::class)->create();
      Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
          $response = $this->actingAs($user,'web')->get('/transit/users/permissions');
          $response->assertSee('Manage BT Stations');
        // ...
    }

    public function testRole()
    {
         $user = factory(User::class)->create();
      Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
          $response = $this->actingAs($user,'web')->get('/transit/users/roles');
          $response->assertSee('BT Super Admin');
        // ...
    }

    public function testuserhasrole()
    {
         $user = factory(User::class)->create();
      Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
          $response = $this->actingAs($user,'web')->get('/transit/users');
          $response->assertSee('BT Super Admin');
        // ...
    }
}
