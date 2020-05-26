@extends('bustravel::backend.docs.layout')

@section('title', 'PalmKash Routes Documentation')



@section('content') 
    <h4 id="account_login" class="mb-4">Operator routes list</h4>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
            <p>
                <ol>
                    <li>In the lefthand sidebar menu click <strong>Operations</strong> then select <strong>Routes</strong>.</li>
                    <li>In this section, you can view a list of the routes plied by the bus operator including their respective stop over stations.
                    Use the Column visibility dropdown button to select and unselect details to be shown in the table.</li>
                    <li>Use the search form on the righthand side (top of the table) to search for routes using any of the available fields.</li>
                    <li>Use the edit or delete links in the <strong>Action</strong> column to change listed route details or remove routes from the list.</li>
                </ol>
            </p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
            <h6>Routes list page</h6>
            <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/routes_listing.png') }}"/>
            </div>
        </div>
    </div>


    <div class="container-fluid">
        <h4 id="change_my_password" class="mb-4 mt-5 pt-5">How to add routes</h4>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
                <p>
                <ol>
                    <li>To add a route to the list, click the plus (+) button and select <strong>New Route</strong>.</li>
                    <li>On the Add Route page start by selecting and adding route stations in their respective order along the route,
                    including the time the bus would arrive at each station/stopover. Repeat this procedure untill you have added all stopovers for the route.</li>
                    <li>Click <strong>Generate</strong> to create all possible combinations of sub-routes.</li>
                    <li>Add prices, choose days of the week the service operates (See example in "Add route prices image" below) and submit/save.</li>
                    <li>The route is now added to the list of available routes and can be booked by travellers.</li>
                </ol>
                </p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
            <h6>Add route stations page</h6>
            <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/add_route_stations.png') }}"/>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <h4 id="sign_me_out" class="mb-4 mt-5 pt-5"></h4>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
                <p>
                <ol>

                </ol>
                </p>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-4">
            <h6>Add route prices page</h6>
            <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/add_route_prices.png') }}"/>
            </div>
        </div>
    </div>


@stop  
