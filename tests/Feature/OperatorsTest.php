<?php
namespace glorifiedking\BusTravel\Tests;
use glorifiedking\BusTravel\Tests\TestCase;
use glorifiedking\BusTravel\Operator;

use Illuminate\Foundation\Testing\RefreshDatabase;
class OperatorsTest extends TestCase
{
   use RefreshDatabase;
   //testing getting operators list
    public function testGetOperators()
    {
      $this->withoutExceptionHandling();
      $operator = factory(Operator::class)->create();
      //When user visit the operator page
      $response = $this->get('/transit/operators'); // your route to get Operator
      //$this->assertTrue(true);
      $response->assertStatus(200);
      // should be able to read the operator
      $response->assertSee($operator->name);
    }
    //testing create Operator
    public function testCreateOperator()
    {
      $data =[
        "name" => "Link Bus",
        "address" => "Kampala Kibubooo",
        "code" => "7ABSU",
        "logo" => "logo.png",
        "email" => "info@link.com",
        "contact_person_name" => "Man",
        "phone_number" => "0751933985",
        "status" => 1,

      ];
      //When user submits operator request to create endpoint
      $this->post('/transit/operators',$data); // your route to create operator
      //It gets stored in the database
      $this->assertEquals(1,Operator::all()->count());
   }
   //testing operator Update
   public function testUpdateOperator()
   {
      $operator = factory(Operator::class)->create();
      $operator->name = "Link Bus";
      $this->patch('/transit/operators/'.$operator->id.'/update', $operator->toArray()); // your route to update Operator
      //The operator should be updated in the database.
      $this->assertDatabaseHas('operators',['id'=> $operator->id , 'name' => 'Link Bus']);
   }
    // testing Operator Delete
   public function testDeleteOperator()
   {
      $operator = factory(Operator::class)->create();
      $this->delete('/transit/operators/'.$operator->id.'/delete'); // your route to delete Operator
      //The operator should be deleted from the database.
      $this->assertDatabaseMissing('operators',['id'=> $operator->id]);
   }
}
