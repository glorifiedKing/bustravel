<?php

namespace glorifiedking\BusTravel\Tests;
use Artisan;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    //testing getting permissions list
    public function testGetPermissions()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $permission = factory(Permission::class)->create();
        //When user visit the operator page
      //dd($permission);
      $response = $this->actingAs($user, 'web')->get('/transit/users/permissions'); // your route to get Operator
      //$this->assertTrue(true);
      $response->assertStatus(200);
        // should be able to read the operator
        $response->assertSee($permission->name);
    }

    //testing create Operator
    public function testCreatePermission()
    {
        $data = [
        'name'       => 'Edit User',
        'guard_name' => 'web',

      ];
      Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
      $user = factory(User::class)->create();
      $user->assignRole('BT Super Admin');
        //When user submits operator request to create endpoint
      $this->actingAs($user)->post('/transit/users/permissions', $data); // your route to create operator
      //It gets stored in the database
        $this->assertDatabaseHas('permissions', [ 'name' => 'Edit User']);
    }

    //testing operator Update
    public function testUpdatePermissions()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $permission = factory(Permission::class)->create();
        $permission->name = 'Create User';
        $this->actingAs($user)->patch('/transit/users/permissions/'.$permission->id.'/update', $permission->toArray()); // your route to update Operator
        //The operator should be updated in the database.
        $this->assertDatabaseHas('permissions', ['id'=> $permission->id, 'name' => 'Create User']);
    }

    // testing Operator Delete
    public function testDeletePermissions()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $permission = factory(Permission::class)->create();
        $this->actingAs($user)->delete('/transit/users/permissions/'.$permission->id.'/delete'); // your route to delete Operator
        //The operator should be deleted from the database.
        $this->assertDatabaseMissing('permissions', ['id'=> $permission->id]);
    }

    //testing getting roles list
    public function testGetRoles()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();
        $role->givePermissionTo($permission);
        //When user visit the role page
      $response = $this->actingAs($user)->get('/transit/users/roles'); // your route to get Role
      //$this->assertTrue(true);
      $response->assertStatus(200);
        // should be able to read the role
        $response->assertSee($role->name);
    }

    //testing create Role
    public function testCreateRole()
    {
        $permission = factory(Permission::class)->create();
        $data = [
        'name'       => 'Edit User',
        'guard_name' => 'web',
        'permissions'=> [$permission->id],

      ];
      Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
      $user = factory(User::class)->create();
      $user->assignRole('BT Super Admin');
        //When user submits operator request to create endpoint
      $this->actingAs($user)->post('/transit/users/roles', $data); // your route to create operator
      //It gets stored in the database
      $this->assertDatabaseHas('roles', [ 'name' => 'Edit User']);
    }

    //testing Role Update
    public function testUpdateRole()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $permission = factory(Permission::class)->create();
        $role = factory(Role::class)->create();
        $role->givePermissionTo($permission);
        $role->name = 'Guest User';
        $role_array = $role->toArray();
        $permission_array = [
      'permissions'=> [$permission->id],
      ];
        $data = array_merge($role_array, $permission_array);
        $this->actingAs($user)->patch('/transit/users/roles/'.$role->id.'/update', $data); // your route to update Operator
        //The operator should be updated in the database.
        $this->assertDatabaseHas('roles', ['id'=> $role->id, 'name' => 'Guest User']);
    }

    // testing Role Delete
    public function testDeleteRole()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();
        $role->givePermissionTo($permission);
        $this->actingAs($user)->delete('/transit/users/roles/'.$role->id.'/delete'); // your route to delete Operator
        //The operator should be deleted from the database.
        $this->assertDatabaseMissing('roles', ['id'=> $role->id]);
    }

    //testing getting roles list
    public function testGetUsers()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $permission = factory(Permission::class)->create();
        $role = factory(Role::class)->create();
        $user1 = factory(User::class)->create();
        $role->givePermissionTo($permission);
        $user1->assignRole($role->name);
        //When user visit the role page
      $response = $this->actingAs($user)->get('/transit/users/'); // your route to get Role
      //$this->assertTrue(true);
      $response->assertStatus(200);
        // should be able to read the role
        $response->assertSee($user1->name);
    }

    //testing create Role
    public function testCreateUser()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();
        $role->givePermissionTo($permission);
        $data = [
        'name'                  => 'Kapere James',
        'email'                 => 'info@link.com',
        'email_verified_at'     => now(),
        'password'              => 'password',
        'password_confirmation' => 'password',
        'remember_token'        => Str::random(10),
        'phone_number'          => '25671999000',
        'status'                => 1,
        'operator_id'           => 1,
        'role'                  => $role->name,
      ];

        //When user submits operator request to create endpoint
      $this->actingAs($user)->post('/transit/users', $data); // your route to create operator
      //It gets stored in the database
      $this->assertEquals(3, User::all()->count());
    }

    //testing Role Update
    public function testUpdateUser()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $permission = factory(Permission::class)->create();
        $role = factory(Role::class)->create();
        $user1 = factory(User::class)->create();
        $role->givePermissionTo($permission);
        $user1->assignRole($role->name);

        $user1->name = 'Bagoza James';
        $user_array = $user1->toArray();
        $role_array = [
      'role'=> $role->id,

      ];
        $data = array_merge($user_array, $role_array);
        $this->actingAs($user)->patch('/transit/users/'.$user1->id.'/update', $data); // your route to update Operator
        //The operator should be updated in the database.
        $this->assertDatabaseHas('users', ['id'=> $user1->id, 'name' => 'Bagoza James']);
    }

    // testing Role Delete
    public function testDeleteUser()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $permission = factory(Permission::class)->create();
        $role = factory(Role::class)->create();
        $user1 = factory(User::class)->create();
        $role->givePermissionTo($permission);
        $user1->assignRole($role->name);
        $this->actingAs($user)->delete('/transit/users/'.$user1->id.'/delete'); // your route to delete Operator
        //The operator should be deleted from the database.
        $this->assertDatabaseMissing('users', ['id'=> $user1->id]);
    }
}
