@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')        
@section('navigaton-bar')


@endsection
            @section('content')
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="h3-bottom">Search Results</h3>
                       @foreach($route_results as $route)
                        @foreach($route->departure_times as $result)
                        @php
                            $start_time = Carbon\Carbon::parse($result->departure_time);
                            $end_time = Carbon\Carbon::parse($result->arrival_time);
                            $duration = $end_time->diffInMinutes($start_time,true);
                        @endphp
                            <div class="row">
                                <div class="col-md-12 ticket-card">
                                    <div class="card">
                                        <div class="card-body">
                                            
                                            <ul class="list-inline">
                                                <li class="list-inline-item">From: {{$route->start->name}}</li>
                                                <li class="list-inline-item">To: {{$route->end->name}}</li>
                                                <li class="list-inline-item">Operator: {{$route->operator->name}}</li>
                                                <li class="list-inline-item add-btn"><a href="{{route('bustravel.add_to_basket',[$result->id,$date_of_travel,'main_route',$no_of_tickets])}}">Add[{{$no_of_tickets}}]</a></li>
                                            </ul>
                                            <h3 class="card-title">departure : {{\Carbon\Carbon::parse($date_of_travel)->format('D M j Y')}} at {{$result->departure_time}} hrs</h3>
                                            <h3 class="card-title">Arrival : {{\Carbon\Carbon::parse($date_of_travel)->format('D M j Y')}} at {{$result->arrival_time}} hrs</h3>
                                            <h5 class="card-text">Est. Duration - {{$duration/60}} hrs</h5>
                                            <h3 class="card-title">price: RWF {{$route->price}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @endforeach
                        <!-- stop over routes -->
                        @foreach($stop_over_routes as $route)
                        @foreach($route->departure_times as $result)
                        @php
                            $start_time = Carbon\Carbon::parse($result->departure_time);
                            $end_time = Carbon\Carbon::parse($result->arrival_time);
                            $duration = $end_time->diffInMinutes($start_time,true);
                        @endphp
                            <div class="row">
                                <div class="col-md-12 ticket-card">
                                    <div class="card">
                                        <div class="card-body">
                                            
                                            <ul class="list-inline">
                                                <li class="list-inline-item">From: {{$route->start_stopover_station->name}}</li>
                                                <li class="list-inline-item">To: {{$route->end_stopover_station->name}}</li>
                                                <li class="list-inline-item">Operator: {{$route->route->operator->name}}</li>
                                            <li class="list-inline-item add-btn"><a href="{{route('bustravel.add_to_basket',[$result->id,$date_of_travel,'stop_over_route',$no_of_tickets])}}">Add[{{$no_of_tickets}}]</a></li>
                                            </ul>
                                            <h3 class="card-title">departure : {{\Carbon\Carbon::parse($date_of_travel)->format('D M j Y')}} at {{$result->departure_time}} hrs</h3>
                                            <h3 class="card-title">Arrival : {{\Carbon\Carbon::parse($date_of_travel)->format('D M j Y')}} at {{$result->arrival_time}} hrs</h3>
                                            <h5 class="card-text">Est. Duration - {{$duration/60}} hrs</h5>
                                            <h3 class="card-title">price: RWF {{$route->price}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @endforeach
                    </div>
                    <div class="offset-1 col-md-3 area-extras">
                        <div>WE ACCEPT MTN MOMO</div>
                    </div>
                </div>
@endsection