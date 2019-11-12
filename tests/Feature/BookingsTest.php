<?php
namespace glorifiedking\BusTravel\Tests;
use glorifiedking\BusTravel\Tests\TestCase;
use glorifiedking\BusTravel\RoutesDepartureTime;
use glorifiedking\BusTravel\BookingCustomField;
use glorifiedking\BusTravel\BookingsField;
use glorifiedking\BusTravel\Driver;
use glorifiedking\BusTravel\Booking;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\Bus;
use glorifiedking\BusTravel\Station;
use glorifiedking\BusTravel\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
class BookingsTest extends TestCase
{
   use RefreshDatabase;
   //testing getting Bookings list
    public function testGetBookings()
    {

      $user = factory(User::class)->create();
      //  create operator
      $operator = factory(Operator::class)->create();
      $station1 = factory(Station::class)->create();
      $station2 = factory(Station::class)->create();
      $driver = factory(Driver::class)->create([
        'operator_id' => $operator->id,
      ]);
      $bus =factory(Bus::class)->create(['operator_id' => $operator->id]);
      //  create Route
      $route =factory(Route::class)->create([
        'operator_id' => $operator->id,
        'start_station' => $station1->id,
        'end_station' => $station2->id,
      ]);
      $route_departure_time =factory(RoutesDepartureTime::class)->create([
        'route_id' => $route->id,
        'bus_id' => $bus->id,
        'driver_id' => $driver->id,
      ]);

      $booking =factory(Booking::class)->create([
        'routes_departure_time_id' => $route->id,
      ]);
      $field = factory(BookingCustomField::class)->create([
       'operator_id'=>$operator->id,
      ]);
      $bookings_fields =factory(BookingsField::class)->create([
        'booking_id' => $booking->id,
        'field_id' => $field->id,
      ]);

      //When user visit the Bookings page
      $response = $this->actingAs($user)->get('/transit/bookings'); // your route to get Bookings
      //$this->assertTrue(true);
      $response->assertStatus(200);
      // should be able to read the Route Departure Time
      $response->assertSee($booking->ticket_number);

    }
    //testing create Route Departure Time
    public function testCreateBookings()
    {
      $user = factory(User::class)->create();
    //  create operator
      $operator = factory(Operator::class)->create();
      $station1 = factory(Station::class)->create();
      $station2 = factory(Station::class)->create();
      $driver = factory(Driver::class)->create([
        'operator_id' => $operator->id,
      ]);
      $route =factory(Route::class)->create([
        'operator_id' => $operator->id,
        'start_station' => $station1->id,
        'end_station' => $station2->id,
      ]);
      $bus =factory(Bus::class)->create(['operator_id' => $operator->id]);
      $field = factory(BookingCustomField::class)->create([
       'operator_id'=>$operator->id,
      ]);
      $route_departure_time =factory(RoutesDepartureTime::class)->create([
        'route_id' => $route->id,
        'bus_id' => $bus->id,
        'driver_id' => $driver->id,
      ]);


      $data =[
        "routes_departure_time_id" => $route_departure_time->id,
        'amount' =>"30000",
        'date_paid' => "2019-11-12",
        "date_of_travel" => "2019-11-12",
        "time_of_travel" => $route_departure_time->departure_time ,
        "ticket_number" => "RAT/001",
        "field_id" => [$field->id],
        "field_value" => ['Bagonza Edward'],
        "user_id"=>1,
        "status" => 1,
      ];
      //When user submits Route Departure Time request to create endpoint
      $this->actingAs($user)->post('/transit/bookings',$data); // your route to create Route Departure Time
      //It gets stored in the database
      $this->assertEquals(1,Booking::all()->count());
      $this->assertEquals(1,BookingsField::all()->count());
        $this->assertDatabaseHas('bookings',['id'=> 1 , 'amount' => '30000']);

   }
   //testing Route Departure Time  Update
   public function testUpdateBooking()
   {   $this->withoutExceptionHandling();
      $user = factory(User::class)->create();
      $operator = factory(Operator::class)->create();
      $station1 = factory(Station::class)->create();
      $station2 = factory(Station::class)->create();
      $route =factory(Route::class)->create([
        'operator_id' => $operator->id,
        'start_station' => $station1->id,
        'end_station' => $station2->id,
      ]);
      $driver = factory(Driver::class)->create([
        'operator_id' => $operator->id,
      ]);
      $bus =factory(Bus::class)->create(['operator_id' => $operator->id]);
      $route_departure_time =factory(RoutesDepartureTime::class)->create([
        'route_id' => $route->id,
        'bus_id' => $bus->id,
        'driver_id' => $driver->id,
      ]);

      $booking =factory(Booking::class)->create([
        'routes_departure_time_id' => $route->id,
      ]);
      $field = factory(BookingCustomField::class)->create([
       'operator_id'=>$operator->id,
      ]);
      $bookings_fields =factory(BookingsField::class)->create([
        'booking_id' => $booking->id,
        'field_id' => $field->id,
      ]);
      $booking->amount = "40000";
      $booking_array = $booking->toArray();
      $field_array =[
      'field_id'=> [$bookings_fields->field_id],
      'field_value'=>[$bookings_fields->field_value],
      ];
      $data =array_merge($booking_array,$field_array);
      $this->actingAs($user)->patch('/transit/bookings/'.$booking->id.'/update', $data); // your route to update Route Departure Time
      //The Departure Time should be updated in the database.
      $this->assertDatabaseHas('bookings',['id'=> $booking->id , 'amount' => '40000']);
   }
    // testing Route Departure Time Delete
     public function testDeleteBooking()
   {
      $user = factory(User::class)->create();
      $operator = factory(Operator::class)->create();
      $station1 = factory(Station::class)->create();
      $station2 = factory(Station::class)->create();
      $route =factory(Route::class)->create([
        'operator_id' => $operator->id,
        'start_station' => $station1->id,
        'end_station' => $station2->id,
      ]);
      $driver = factory(Driver::class)->create([
        'operator_id' => $operator->id,
      ]);
      $bus =factory(Bus::class)->create(['operator_id' => $operator->id]);
      $route_departure_time =factory(RoutesDepartureTime::class)->create([
        'route_id' => $route->id,
        'bus_id' => $bus->id,
        'driver_id' => $driver->id,
      ]);
      $booking =factory(Booking::class)->create([
        'routes_departure_time_id' => $route->id,
      ]);
      $field = factory(BookingCustomField::class)->create([
       'operator_id'=>$operator->id,
      ]);
      $bookings_fields =factory(BookingsField::class)->create([
        'booking_id' => $booking->id,
        'field_id' => $field->id,
      ]);
      $this->actingAs($user)->delete('/transit/bookings/'.$booking->id.'/delete'); // your route to delete Route
      //The Route Departure Time  should be deleted from the database.
      $this->assertDatabaseMissing('bookings',['id'=> $booking->id]);
   }
}
