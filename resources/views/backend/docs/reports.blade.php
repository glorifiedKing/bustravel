@extends('bustravel::backend.docs.layout')

@section('title', 'PalmKash User Manual')



@section('content')
    <h4 id="account_login" class="mb-4">Operator sales report</h4>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
            <p>The reports section provides transit operators with data statistics and analysis of their operatons.</p>
                <ol>
                    <li>In the lefthand sidebar menu click <strong>Reports</strong>, from the options select <strong>Sales</strong>.</li>
                    <li>The sales report compares operator revenues and ticket sales against - Days of the current week, months or years. This data is
                    based on the start and end stations</li>
                    <li>To change the chart variables use the dropdown lists at the top of the chart to show statistics by week days, months and by start to end station.</li>
                </ol>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
            <h6>Sample sales report</h6>
            <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/sales_report.png') }}" alt="demo_image"/>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <h4 id="change_my_password" class="mb-4 mt-5 pt-5">Route performance report</h4>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
                <p>
                <ol>
                    <li>Select <strong>Route performance</strong> from the <strong>Reports</strong> section in the left sidebar menu.</li>
                    <li>The route performance report compares operator revenues against - Days of the current week, months or years. This data is
                    based on the start and end stations with consideration of the different transit service times.</li>
                    <li>Click the service times displayed above the chart to enable or disable display of a service in the chart.</li>
                    <li>To change the chart variables use the dropdown lists at the top of the chart to show statistics by week days, months and by start to end station.</li>
                </ol>
                </p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
            <h6>Sample route performance report</h6>
            <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/route_performance_report.png') }}" alt="demo_image"/>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <h4 id="change_my_password" class="mb-4 mt-5 pt-5">Passenger traffic report</h4>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
                <p>
                <ol>
                    <li>Select <strong>Passenger traffic</strong> from the <strong>Reports</strong> section in the left sidebar menu.</li>
                    <li>The passenger traffic report compares operator passenger numbers against - Days of the current week, months or years. This data is
                    based on the start and end stations of a route.</li>
                    <li>To change the chart variables use the dropdown lists at the top of the chart to show statistics by week days, months and by start to end station.</li>
                </ol>
                </p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
            <h6>Sample passenger traffic report</h6>
            <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/passenger_traffic_report.png') }}" alt="demo_image"/>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <h4 id="change_my_password" class="mb-4 mt-5 pt-5">Bookings report</h4>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
                <p>
                <ol>
                    <li>Select the <strong>Bookings</strong> link from the <strong>Reports</strong> section in the left sidebar menu.</li>
                    <li>The Bookings report lists bookings made for or by passengers under a particualr transit operator.</li>
                    <li>Booking report results can be filtered using the fields in the search section - Ticket Number, Start Station, and date ranges.</li>
                </ol>
                </p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
            <h6>Sample bookings report</h6>
            <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/bookings_report.png') }}" alt="demo_image"/>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <h4 id="change_my_password" class="mb-4 mt-5 pt-5">Cashier report</h4>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
                <p>
                <ol>
                    <li>Select the <strong>Cashier Report</strong> link from the <strong>Reports</strong> section in the left sidebar menu.</li>
                    <li>The Cashier report lists bookings made or tickets sold by a particular cashier through their account.</li>
                    <li>These booking/ticket results can be filtered using the fields in the search section - Ticket Number, Start Station, and date ranges.</li>
                </ol>
                </p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
            <h6>Sample cashier report</h6>
            <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/cashier_report.png') }}" alt="demo_image"/>
            </div>
        </div>
    </div>
@stop
