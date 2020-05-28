@extends('bustravel::backend.docs.layout')

@section('title', 'PalmKash User Manual')



@section('content')
    <h4 id="account_login" class="mb-4">Operator bookings list</h4>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
            <p>The bookings section shows bookings made under a particular transit operator.</p>
                <ol>
                    <li>In the lefthand sidebar menu click <strong>Operations</strong> then select <strong>Bookings</strong>.</li>
                    <li>In this section, you can view a list all bookings made under a particular transit operator.
                    Use the Column visibility dropdown button to filter details to be shown in the table.</li>
                    <li>Use the search form on the righthand side (top of the table) to search for bookings using any of the available fields.</li>
                    <li>Use the edit or delete links in the <strong>Action</strong> column to change listed booking's details or remove bookings.</li>
                </ol>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
            <h6>Bookings page</h6>
            <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/bookings_listing.png') }}" alt="demo_image"/>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <h4 id="change_my_password" class="mb-4 mt-5 pt-5">How to make bookings from the admin backend</h4>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
                <p>
                <ol>
                    <li>To make a new booking, click the plus (+) button and select <strong>New Booking</strong>.</li>
                    <li>On the Add Booking page start by selecting the <strong>From</strong> and <strong>To</strong> Stations. This will list available
                    bus services that match the selected stations.</li>
                    <li>Select the prefered bus service route and time. This will automatically show the fee for the selected service in the <strong>Amount</strong> field.</li>
                    <li>Enter the passenger's phone number and email then select a payment method.</li>
                    <li>Confirm payment has been made before concluding the booking.</li>
                </ol>
                </p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
            <h6>Make new booking page</h6>
            <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/make_booking_backoffice.png') }}" alt=demo_image/>
            </div>
        </div>
    </div>
@stop
