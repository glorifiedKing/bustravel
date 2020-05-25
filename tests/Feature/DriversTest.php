<?php

namespace glorifiedking\BusTravel\Tests;
use Artisan;
use glorifiedking\BusTravel\Driver;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DriversTest extends TestCase
{
    use RefreshDatabase;

    //testing getting drivers list
    public function testGetDrivers()
    {
       Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
       $user = factory(User::class)->create();
       $user->assignRole('BT Super Admin');
        //  create operator
        $operator = factory(Operator::class)->create();
        //  create Driver
        $driver = factory(Driver::class)->create(['operator_id' => $operator->id]);
        //When user visit the driver page
      $response = $this->actingAs($user)->get('/transit/drivers'); // your route to get Drivers
      //$this->assertTrue(true);
      $response->assertStatus(200);
        // should be able to read the Driver's Number Plate
        $response->assertSee($driver->name);
    }

    //testing create Driver
    public function testCreateDriver()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        //  create operator
        $operator = factory(Operator::class)->create();
        $data = [
        'operator_id'       => $operator->id,
        'name'              => 'Kakebo John',
        'nin'               => 'DFRYUIII',
        'date_of_birth'     => '1987-09-09',
        'driving_permit_no' => 'CMYUIII',
        'picture'           => 'picture.jpg',
        'phone_number'      => '25678999999',
        'address'           => 'Makidye, Kampala Uganda',
        'email'           => 'info@gmail.com',
        'status'            => 1,
      ];
        //When user submits Driver request to create endpoint
      $this->actingAs($user)->post('/transit/drivers', $data); // your route to create Driver
      //It gets stored in the database
      $this->assertEquals(1, Driver::all()->count());
    }

    //testing Driver Update
    public function testUpdateDriver()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $operator = factory(Operator::class)->create();
        $user1 =factory(User::class)->create();
        $user1->syncRoles('BT Driver');
        $driver = factory(Driver::class)->create(['operator_id' => $operator->id,'user_id'=>$user1->id]);
        $driver->name = 'Bagonza Gerald';
        $driver_array = $driver->toArray();
        $user_array = [
        'email'=> $user1->email,
         ];

          $data = array_merge($driver_array, $user_array);
        $this->actingAs($user)->patch('/transit/drivers/'.$driver->id.'/update', $data); // your route to update Driver
        //The operator should be updated in the database.
        $this->assertDatabaseHas('drivers', ['id'=> $driver->id, 'name' => 'Bagonza Gerald']);
    }

    // testing Driver Delete
    public function testDeleteDriver()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        
        $operator = factory(Operator::class)->create();
        $driver = factory(Driver::class)->create(['operator_id' => $operator->id]);
        $this->actingAs($user)->delete('/transit/drivers/'.$driver->id.'/delete'); // your route to delete Bus
        //The Bus should be deleted from the database.
        $this->assertDatabaseMissing('drivers', ['id'=> $driver->id]);
    }
}
