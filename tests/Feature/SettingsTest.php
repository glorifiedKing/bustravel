<?php

namespace glorifiedking\BusTravel\Tests;
use Artisan;
use glorifiedking\BusTravel\BookingCustomField;
use glorifiedking\BusTravel\Operator;
use glorifiedking\BusTravel\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    //testing getting Fields list
    public function testGetBookingCustomFields()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $operator = factory(Operator::class)->create();
        $field = factory(BookingCustomField::class)->create([
       'operator_id'=> $operator->id,
      ]);
        //When user visit the operator page
      $response = $this->actingAs($user)->get('/transit/company_settings/fields'); // your route to get Operator
      //$this->assertTrue(true);
      $response->assertStatus(200);
        // should be able to read the operator
        $response->assertSee($field->field_name);
    }

    //testing create Field
    public function testCreateField()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $operator = factory(Operator::class)->create();
        $data = [
        'operator_id'  => $operator->id,
        'field_prefix' => 'name',
        'field_name'   => 'name',
        'field_order'  => '0',
        'is_required'  => 1,
        'status'       => 1,

      ];
        //When user submits Feilds request to create endpoint
      $this->actingAs($user)->post('/transit/company_settings/fields', $data); // your route to create operator
      //It gets stored in the database
      $this->assertEquals(1, BookingCustomField::all()->count());
    }

    //testing Feild Update
    public function testUpdateField()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $operator = factory(Operator::class)->create();
        $field = factory(BookingCustomField::class)->create([
      'operator_id' => $operator->id,
      ]);
        $field->field_name = 'Email';
        $this->actingAs($user)->patch('/transit/company_settings/fields/'.$field->id.'/update', $field->toArray()); // your route to update Field
        //The field should be updated in the database.
        $this->assertDatabaseHas('booking_custom_fields', ['id'=> $field->id, 'field_name' => 'Email']);
    }

    // testing Operator Delete
    public function testDeleteField()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $operator = factory(Operator::class)->create();
        $field = factory(BookingCustomField::class)->create([
      'operator_id' => $operator->id,
      ]);
        $this->actingAs($user)->delete('/transit/company_settings/fields/'.$field->id.'/delete'); // your route to delete Field
        //The Field should be deleted from the database.
        $this->assertDatabaseMissing('booking_custom_fields', ['id'=> $field->id]);
    }
}
