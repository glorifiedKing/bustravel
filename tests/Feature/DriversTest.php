<?php

namespace glorifiedking\BusTravel\Tests;

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
        $user = factory(User::class)->create();
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
        $user = factory(User::class)->create();
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
        $user = factory(User::class)->create();
        $operator = factory(Operator::class)->create();
        $driver = factory(Driver::class)->create(['operator_id' => $operator->id]);
        $driver->name = 'Bagonza Gerald';
        $this->actingAs($user)->patch('/transit/drivers/'.$driver->id.'/update', $driver->toArray()); // your route to update Driver
        //The operator should be updated in the database.
        $this->assertDatabaseHas('drivers', ['id'=> $driver->id, 'name' => 'Bagonza Gerald']);
    }

    // testing Driver Delete
    public function testDeleteBus()
    {
        $user = factory(User::class)->create();
        $operator = factory(Operator::class)->create();
        $driver = factory(Driver::class)->create(['operator_id' => $operator->id]);
        $this->actingAs($user)->delete('/transit/drivers/'.$driver->id.'/delete'); // your route to delete Bus
        //The Bus should be deleted from the database.
        $this->assertDatabaseMissing('drivers', ['id'=> $driver->id]);
    }
}
