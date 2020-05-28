@extends('bustravel::backend.docs.layout')

@section('title', 'PalmKash User Manual')



@section('content')
    <h4 id="account_login" class="mb-4">Operator drivers list</h4>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
            <p>
                <ol>
                    <li>In the lefthand sidebar menu click <strong>Operations</strong> then select <strong>Drivers</strong>.</li>
                    <li>In this section, a listing of available drivers attached to the bus company is listed. Use the various buttons to filter by preference.</li>
                    <li>Use the search form on the righthand side (top of the table) to search for drivers using any of the available fields.</li>
                    <li>Use the edit or delete links in the <strong>Action</strong> column to change listed driver details or remove drivers from the list.</li>
                </ol>
            </p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
            <h6>Drivers list page</h6>
            <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/drivers_listing.png') }}" alt="demo_image"/>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <h4 id="change_my_password" class="mb-4 mt-5 pt-5">How to add drivers</h4>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
                <p>
                <ol>
                    <li>To add a driver to the list, click the plus (+) button and select <strong>New Driver</strong>.</li>
                    <li>On the Add Driver page fill in the information fields. All fields marked with an asterix (*) are mandatory. Activate the bus driver before submit.</li>
                    <li>The bus driver will now appear in the list of available drivers.</li>
                </ol>
                </p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
            <h6>Add drivers page</h6>
            <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/add_drivers_image.png') }}" alt="demo_image"/>
            </div>
        </div>
    </div>


  @stop
