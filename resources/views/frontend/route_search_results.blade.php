@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')
@section('navigaton-bar')


@endsection
            @section('content')
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="h3-bottom">Search Results</h4>                       
                       
                        @foreach($departure_times as $result)
                        @php
                            $start_time = Carbon\Carbon::parse($result->departure_time);
                            $end_time = Carbon\Carbon::parse($result->arrival_time);
                            $full_duration = $end_time->diffForHumans($start_time,['parts'=>2]);
                            $duration = str_replace('after','',$full_duration);
                            $seats_left = $result->number_of_seats_left($date_of_travel);
                        @endphp
                           @if($seats_left > 0)
                            <div class="row">
                                <div class="col-md-12 ticket-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="list-inline">
                                                <li class="list-inline-item">From: {{$result->route->start->name}}</li>
                                                <li class="list-inline-item">To: {{$result->route->end->name}}</li><br>
                                                <li class="list-inline-item">Operator: <span class="operator_name">{{$result->route->operator->name}}</span></li>
                                            </ul>
                                            <h3 class="card-title">Departure : {{\Carbon\Carbon::parse($date_of_travel)->format('D M j Y')}} at {{$result->departure_time}} hrs</h3>
                                            <h3 class="card-title">Arrival : {{\Carbon\Carbon::parse($date_of_travel)->format('D M j Y')}} at {{$result->arrival_time}} hrs</h3>

                                            <h5 class="card-text">Est. Duration - {{$duration}} </h5>
                                            <h5 class="card-text">Seats Left - {{$seats_left}} </h5>

                                            <div class="col-md-9">
                                              <h3 class="card-title"><span class="list_price">Price:</span> RWF {{$result->route->price}}</h3>
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
                        
                        <!-- stop over routes -->                        
                        
                        @foreach($departure_times_stop_over as $result)
                        @php
                            $start_time = Carbon\Carbon::parse($result->departure_time);
                            $end_time = Carbon\Carbon::parse($result->arrival_time);
                            $full_duration = $end_time->diffForHumans($start_time,['parts'=>2]);
                            $duration = str_replace('after','',$full_duration);
                            $seats_left = $result->main_route_departure_time->number_of_seats_left($date_of_travel);
                        @endphp
                            @if($seats_left > 0)
                            <div class="row">
                                <div class="col-md-12 ticket-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="list-inline">
                                                <li class="list-inline-item">From: {{$result->route->start_stopover_station->name}}</li>
                                                <li class="list-inline-item">To: {{$result->route->end_stopover_station->name}}</li><br>
                                                <li class="list-inline-item">Via : {{$result->main_route_departure_time->route->start->name}} to {{$result->main_route_departure_time->route->end->name}}</li><br>
                                                <li class="list-inline-item">Operator: <span class="operator_name">{{$result->route->route->operator->name}}</span> </li>
                                            </ul>
                                            <h3 class="card-title">Departure : {{\Carbon\Carbon::parse($date_of_travel)->format('D M j Y')}} at {{$result->departure_time}} hrs</h3>
                                            <h3 class="card-title">Arrival : {{\Carbon\Carbon::parse($date_of_travel)->format('D M j Y')}} at {{$result->arrival_time}} hrs</h3>

                                            <h5 class="card-text">Est. Duration - {{$duration}} </h5>
                                            <h5 class="card-text">Seats Left - {{$seats_left}} </h5>
                                            <div class="col-md-9">
                                              <h3 class="card-title"><span class="list_price">Price:</span> RWF {{$result->route->price}}</h3>
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
                        
                    </div>
                    <div class="offset-1 col-md-3 area-extras">
                        <div>WE ACCEPT MTN MOMO</div>
                    </div>
                </div>
@endsection
