<?php
namespace glorifiedking\BusTravel\Tests;
use glorifiedking\BusTravel\Tests\TestCase;
use glorifiedking\BusTravel\Bus;
use glorifiedking\BusTravel\Operator;

use Illuminate\Foundation\Testing\RefreshDatabase;
class BusesTest extends TestCase
{
   use RefreshDatabase;
   //testing getting operators list
    public function testGetBuses()
    {
      //  create operator
      $operator = factory(Operator::class)->create();
      //  create Bus
      $bus =factory(Bus::class)->create(['operator_id' => $operator->id]);
      //When user visit the buses page
      $response = $this->get('/transit/buses'); // your route to get Buses
      //$this->assertTrue(true);
      $response->assertStatus(200);
      // should be able to read the Bus's Number Plate
      $response->assertSee($bus->number_plate);
    }
    //testing create Bus
    public function testCreateBus()
    {
    //  create operator
      $operator = factory(Operator::class)->create();
      $data =[
        "operator_id" => $operator->id,
        "number_plate" => "UAB201",
        "seating_capacity" => 60,
        "description" => "Scania Model, AC",
        "seating_arrangement" => "",
        "status" => 1,
      ];
      //When user submits Bus request to create endpoint
      $this->post('/transit/buses',$data); // your route to create Bus
      //It gets stored in the database
      $this->assertEquals(1,Bus::all()->count());
   }
   //testing Bus Update
   public function testUpdateBus()
   {
      $operator = factory(Operator::class)->create();
      $bus =factory(Bus::class)->create(['operator_id' => $operator->id]);
      $bus->number_plate = "UBE200";
      $this->patch('/transit/buses/'.$bus->id.'/update', $bus->toArray()); // your route to update Bus
      //The operator should be updated in the database.
      $this->assertDatabaseHas('buses',['id'=> $bus->id , 'number_plate' => 'UBE200']);
   }
    // testing Bus Delete
     public function testDeleteBus()
   {
      $operator = factory(Operator::class)->create();
      $bus =factory(Bus::class)->create(['operator_id' => $operator->id]);
      $this->delete('/transit/buses/'.$bus->id.'/delete'); // your route to delete Bus
      //The Bus should be deleted from the database.
      $this->assertDatabaseMissing('buses',['id'=> $bus->id]);
   }
}
