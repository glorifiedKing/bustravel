@extends('bustravel::backend.docs.layout')

@section('title', 'PalmKash User Manual')



@section('content') 
    <h4 id="account_login" class="mb-4">Operator buses list</h4>
    <div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
        <p>
            <ol>
                <li>In the lefthand sidebar menu click <strong>Operations</strong> then select <strong>Buses</strong>.</li>
                <li>In this section, a listing of buses available to the bus company is listed. Use the various buttons to filter by preference.</li>
                <li>Use the search form on the righthand side (top of the table) to search for buses using any of the available fields.</li>
                <li>Use the edit or delete links in the <strong>Action</strong> column to change listed bus details or remove buses from the list.</li>
            </ol>
        </p>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
        <h6>Bus list page</h6>
        <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/buses_listing.png') }}"/>
        </div>
    </div>
    </div>

    <div class="container-fluid">
        <h4 id="change_my_password" class="mb-4 mt-5 pt-5">How to add buses</h4>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
                <p>
                <ol>
                    <li>To add a bus to the list, click the plus (+) button and select <strong>New Bus</strong>.</li>
                    <li>On the Add bus page fill in the information fields including Bus number plate and seating capacity. Activate the bus before submit.</li>
                    <li>The bus will now appear in the list of available operator buses.</li>
                </ol>
                </p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
            <h6>Add buses page</h6>
            <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/add_buses_image.png') }}"/>
            </div>
        </div>
    </div>
  @stop  
