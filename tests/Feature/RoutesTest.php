<?php

namespace glorifiedking\BusTravel\Tests;

use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\Station;
use glorifiedking\BusTravel\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    //testing getting Routes list
    public function testGetRoutes()
    {
        $user = factory(User::class)->create();
        //  create operator
        $operator = factory(Operator::class)->create();
        $station1 = factory(Station::class)->create();
        $station2 = factory(Station::class)->create();
        //  create Route
        $route = factory(Route::class)->create([
        'operator_id'   => $operator->id,
        'start_station' => $station1->id,
        'end_station'   => $station2->id,
      ]);
        //When user visit the buses page
      $response = $this->actingAs($user)->get('/transit/routes'); // your route to get Buses
      //$this->assertTrue(true);
      $response->assertStatus(200);
        // should be able to read the Bus's Number Plate
        $response->assertSee($route->start->name);
    }

    //testing create Route
    public function testCreateRoute()
    {
        $user = factory(User::class)->create();
        //  create operator
        $operator = factory(Operator::class)->create();
        $station1 = factory(Station::class)->create();
        $station2 = factory(Station::class)->create();
        $data = [
        'operator_id'   => $operator->id,
        'start_station' => $station1->id,
        'end_station'   => $station2->id,
        'price'         => '25000',
        'return_price'  => '25000',
        'status'        => 1,
      ];
        //When user submits Bus request to create endpoint
      $this->actingAs($user)->post('/transit/routes', $data); // your route to create Route
      //It gets stored in the database
      $this->assertEquals(1, Route::all()->count());
    }

    //testing Route Update
    public function testUpdateRoute()
    {
        $user = factory(User::class)->create();
        $operator = factory(Operator::class)->create();
        $station1 = factory(Station::class)->create();
        $station2 = factory(Station::class)->create();
        $route = factory(Route::class)->create([
        'operator_id'   => $operator->id,
        'start_station' => $station1->id,
        'end_station'   => $station2->id,
      ]);
        $route->price = '26000';
        $this->actingAs($user)->patch('/transit/routes/'.$route->id.'/update', $route->toArray()); // your route to update Route
        //The operator should be updated in the database.
        $this->assertDatabaseHas('routes', ['id'=> $route->id, 'price' => '26000']);
    }

    // testing Route Delete
    public function testDeleteRoute()
    {
        $user = factory(User::class)->create();
        $operator = factory(Operator::class)->create();
        $station1 = factory(Station::class)->create();
        $station2 = factory(Station::class)->create();
        $route = factory(Route::class)->create([
        'operator_id'   => $operator->id,
        'start_station' => $station1->id,
        'end_station'   => $station2->id,
      ]);
        $this->actingAs($user)->delete('/transit/routes/'.$route->id.'/delete'); // your route to delete Route
        //The Bus should be deleted from the database.
        $this->assertDatabaseMissing('routes', ['id'=> $route->id]);
    }
}
