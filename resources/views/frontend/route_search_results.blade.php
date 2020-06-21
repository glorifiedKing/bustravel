@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')
@section('navigaton-bar')


@endsection
            @section('content')
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="h3-bottom">Search Results</h4>
                       @foreach($route_results as $route)
                        @foreach($route->departure_times as $result)
                        @php
                            $start_time = Carbon\Carbon::parse($result->departure_time);
                            $end_time = Carbon\Carbon::parse($result->arrival_time);
                            $duration = $end_time->diffForHumans($start_time,['parts'=>2]);
                            $seats_left = $result->number_of_seats_left($date_of_travel);
                        @endphp
                           @if($seats_left > 0)
                            <div class="row">
                                <div class="col-md-12 ticket-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="list-inline">
                                                <li class="list-inline-item">From: {{$route->start->name}}</li>
                                                <li class="list-inline-item">To: {{$route->end->name}}</li><br>
                                                <li class="list-inline-item">Operator: <span class="operator_name">{{$route->operator->name}}</span></li>
                                            </ul>
                                            <h3 class="card-title">Departure : {{\Carbon\Carbon::parse($date_of_travel)->format('D M j Y')}} at {{$result->departure_time}} hrs</h3>
                                            <h3 class="card-title">Arrival : {{\Carbon\Carbon::parse($date_of_travel)->format('D M j Y')}} at {{$result->arrival_time}} hrs</h3>

                                            <h5 class="card-text">Est. Duration - {{$duration}} </h5>

                                            <div class="col-md-9">
                                              <h3 class="card-title"><span class="list_price">Price:</span> RWF {{$route->price}}</h3>
                                            </div>
                                            <div class="col-md-3">
                                              <ul>
                                                <li class="list-inline-item btn add-btn"><a href="{{route('bustravel.add_to_basket',[$result->id,$date_of_travel,'main_route',$no_of_tickets])}}">Book</a></li>
                                              </ul>
                                                </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                        @endforeach
                        <!-- stop over routes -->
                        @foreach($stop_over_routes as $route)
                        @foreach($route->departure_times as $result)
                        @php
                            $start_time = Carbon\Carbon::parse($result->departure_time);
                            $end_time = Carbon\Carbon::parse($result->arrival_time);
                            $duration = $end_time->diffForHumans($start_time,['parts'=>2]);
                            $seats_left = $result->main_route_departure_time->number_of_seats_left($date_of_travel);
                        @endphp
                            @if($seats_left > 0)
                            <div class="row">
                                <div class="col-md-12 ticket-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="list-inline">
                                                <li class="list-inline-item">From: {{$route->start_stopover_station->name}}</li>
                                                <li class="list-inline-item">To: {{$route->end_stopover_station->name}}</li><br>
                                                <li class="list-inline-item">Operator: <span class="operator_name">{{$route->route->operator->name}}</span> </li>
                                            </ul>
                                            <h3 class="card-title">Departure : {{\Carbon\Carbon::parse($date_of_travel)->format('D M j Y')}} at {{$result->departure_time}} hrs</h3>
                                            <h3 class="card-title">Arrival : {{\Carbon\Carbon::parse($date_of_travel)->format('D M j Y')}} at {{$result->arrival_time}} hrs</h3>

                                            <h5 class="card-text">Est. Duration - {{$duration}} </h5>
                                            <div class="col-md-9">
                                              <h3 class="card-title"><span class="list_price">Price:</span> RWF {{$route->price}}</h3>
                                            </div>
                                            <div class="col-md-3">
                                              <ul>
                                                <li class="list-inline-item btn add-btn"><a href="{{route('bustravel.add_to_basket',[$result->id,$date_of_travel,'stop_over_route',$no_of_tickets])}}">Book</a></li>
                                              </ul>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                        @endforeach
                    </div>
                    <div class="offset-1 col-md-3 area-extras">
                        <div>WE ACCEPT MTN MOMO</div>
                    </div>
                </div>
@endsection
