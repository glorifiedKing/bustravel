<?php

namespace glorifiedking\BusTravel\Tests;
use Artisan;
use glorifiedking\BusTravel\Bus;
use glorifiedking\BusTravel\Driver;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\Route;
use glorifiedking\BusTravel\RoutesDepartureTime;
use glorifiedking\BusTravel\Station;
use glorifiedking\BusTravel\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoutesDepartureTimesTest extends TestCase
{
    use RefreshDatabase;

    //testing getting Routes Departure Times list
    public function testGetRoutesDepartureTimes()
    {
       Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
       $user = factory(User::class)->create();
       $user->assignRole('BT Super Admin');
        //  create operator
        $operator = factory(Operator::class)->create();
        $user->operator_id = $operator->id;
        $user->save();
        $station1 = factory(Station::class)->create();
        $station2 = factory(Station::class)->create();
        $driver = factory(Driver::class)->create();
        $bus = factory(Bus::class)->create();
        //  create Route
        $route = factory(Route::class)->create([
        'start_station' => $station1->id,
        'end_station'   => $station2->id,
      ]);
        $route_departure_time = factory(RoutesDepartureTime::class)->create([
        'route_id'  => $route->id,
        'bus_id'    => $bus->id,
        'driver_id' => $driver->id,
      ]);
        //When user visit the Routes Departure Times page
      $response = $this->actingAs($user)->get('/transit/routes/'.$route->id.'/edit'); // your route to get Routes Departure  Times
      //$this->assertTrue(true);
      $response->assertStatus(200);
        // should be able to read the Route Departure Time
        $response->assertSee($route_departure_time->departure_time);
    }

    //testing create Route Departure Time
    public function testCreateRouteDepartureTimes()
    {

       Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
       $user = factory(User::class)->create();
       $user->assignRole('BT Super Admin');
        //  create operator
        $operator = factory(Operator::class)->create();
        $user->operator_id = $operator->id;
        $user->save();
        $station1 = factory(Station::class)->create();
        $station2 = factory(Station::class)->create();
        $driver = factory(Driver::class)->create();
        $route = factory(Route::class)->create([
        'start_station' => $station1->id,
        'end_station'   => $station2->id,
      ]);
        $bus = factory(Bus::class)->create();
        $data = [
        'route_id'                           => $route->id,
        'departure_time'                     => '09:30:00',
        'arrival_time'                     => '12:30:00',
        'bus_id'                             => $bus->id,
        'driver_id'                          => $driver->id,
        'restricted_by_bus_seating_capacity' => 1,
        'status'                             => 1,
        'stopover_arrival_time'=> ['0'=>'9:00'] ,
        'stopover_departure_time'=>['0'=>'12:00'] ,
        'days_of_week'=>['Monday','Tuesday'] ,
        'has_stover'=>0,

      ];
        //When user submits Route Departure Time request to create endpoint
      $this->actingAs($user)->post('/transit/routes/departures', $data); // your route to create Route Departure Time
      //It gets stored in the database
      $this->assertEquals(1, RoutesDepartureTime::all()->count());
    }

    //testing Route Departure Time  Update
    public function testUpdateRoutesDepartureTimes()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $operator = factory(Operator::class)->create();
        $user->operator_id = $operator->id;
        $user->save();
        $station1 = factory(Station::class)->create();
        $station2 = factory(Station::class)->create();
        $route = factory(Route::class)->create([
        'start_station' => $station1->id,
        'end_station'   => $station2->id,
      ]);
        $driver = factory(Driver::class)->create([
        'operator_id' => $operator->id,
      ]);
        $bus = factory(Bus::class)->create();
        $route_departure_time = factory(RoutesDepartureTime::class)->create([
        'route_id'  => $route->id,
        'bus_id'    => $bus->id,
        'driver_id' => $driver->id,
      ]);
        $route_departure_time->departure_time = '09:45:00';

        $route_array = $route_departure_time->toArray();
        $field_array = [
          'arrival_time'=>'10:56:00',
          'stopover_arrival_time'=> ['0'=>'9:00'] ,
          'stopover_departure_time'=>['0'=>'12:00'] ,
          'days_of_week'=>['Monday','Tuesday'] ,
          'has_stover'=>0,
      ];
      $data = array_merge($route_array, $field_array);
        $this->actingAs($user)->patch('/transit/routes/departures/'.$route_departure_time->id.'/update', $data); // your route to update Route Departure Time
        //The Departure Time should be updated in the database.
        $this->assertDatabaseHas('routes_departure_times', ['id'=> $route_departure_time->id, 'departure_time' => '09:45:00']);
    }

    // testing Route Departure Time Delete
    public function testDeleteRoute()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $operator = factory(Operator::class)->create();
        $user->operator_id = $operator->id;
        $user->save();
        $station1 = factory(Station::class)->create();
        $station2 = factory(Station::class)->create();
        $route = factory(Route::class)->create([
        'start_station' => $station1->id,
        'end_station'   => $station2->id,
      ]);
        $driver = factory(Driver::class)->create();
        $bus = factory(Bus::class)->create();
        $route_departure_time = factory(RoutesDepartureTime::class)->create([
        'route_id'  => $route->id,
        'bus_id'    => $bus->id,
        'driver_id' => $driver->id,
      ]);
        $this->actingAs($user)->delete('/transit/routes/departures/'.$route_departure_time->id.'/delete'); // your route to delete Route
        //The Route Departure Time  should be deleted from the database.
        $this->assertDatabaseMissing('routes_departure_times', ['id'=> $route_departure_time->id]);
    }
}
