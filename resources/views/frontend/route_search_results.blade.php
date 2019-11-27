@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')        
@section('navigaton-bar')


@endsection
            @section('content')
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="h3-bottom">{{(count($route_results) > 0) ? 'Available Routes' : 'No Routes for Your search'}}</h3>
                       @foreach($route_results as $route)
                        @foreach($route->departure_times as $result)
                            <div class="row">
                                <div class="col-md-12 ticket-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="card-title">departure : {{$date_of_travel}} at {{$result->departure_time}} hrs</h3>
                                            <h5 class="card-text">Est. Duration - 2hrs 30mins</h5>
                                            <h3 class="price">price: RWF {{$route->price}}</h3>
                                            <ul class="list-inline">
                                                <li class="list-inline-item">From: {{$route->start->name}}</li>
                                                <li class="list-inline-item">To: {{$route->end->name}}</li>
                                                <li class="list-inline-item">Operator: {{$route->operator->name}}</li>
                                                <li class="list-inline-item add-btn"><a href="{{route('bustravel.add_to_basket',[$result->id,$date_of_travel])}}">Add to basket</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @endforeach
                    </div>
                    <div class="offset-1 col-md-3 area-extras">
                        <div>AREA FOR ANY FUTURE INCLUSIONS</div>
                    </div>
                </div>
@endsection