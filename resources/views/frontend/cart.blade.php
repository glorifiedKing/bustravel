@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')
@section('navigaton-bar')


@endsection
            @section('content')
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="h3-bottom">Your tickets <a class="btn btn-danger" href="{{route('bustravel.cart.clear')}}">Clear Cart</a></h3>
                        @php
                            $cart = session()->get('cart.items');
                            $total_amount = 0;
                            $reserve_fee = 0;
                            $booking_fee = 0;
                            $main_route_tickets = 0;
                            $stop_over_route_tickets = 0;
                            $total_tickets = count($main_route_departures) + count($stop_over_route_departures);
                        @endphp
                        @foreach($main_route_departures as $index => $route)
                        <div class="row">
                            @php
                                $key = array_search($route->id,array_column($cart,'id'));
                                $date_of_travel = $cart[$key]['date_of_travel'];
                                $total_amount += $cart[$key]['quantity']*$cart[$key]['amount'];
                                $start_time = Carbon\Carbon::parse($route->departure_time);
                                $end_time = Carbon\Carbon::parse($route->arrival_time);
                                $duration = $end_time->diffInMinutes($start_time,true);
                                $main_route_tickets += $cart[$key]['quantity'];
                            @endphp
                            <div class="col-md-12 ticket-card cart">
                                <ul class="top-adjust-txt">
                                <li class="ticket-number">Tickets {{$cart[$key]['quantity']}}</li>

                                </ul>
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title">Departure: {{$route->departure_time}} hrs</h3>
                                        <h5 class="card-text">Est. Duration - {{round($duration/60,1)}} hrs</h5>
                                        <h3 class="price">
                                            <span> RWF {{$cart[$key]['quantity']*$route->route->price}}</span>
                                            <span class="date">{{\Carbon\Carbon::parse($date_of_travel)->format('D M j Y')}} </span>
                                        </h3>
                                        <ul class="list-inline">
                                            <li class="list-inline-item">From: {{$route->route->start->name}}</li>
                                            <li class="list-inline-item">To: {{$route->route->end->name}}</li>
                                            <li class="list-inline-item">Operator: {{$route->route->operator->name}}</li>
                                            <li class="list-inline-item add-btn" ><a href="{{route('bustravel.add_to_basket',[$route->id,date('Y-m-d'),'main_route',-1])}}">- </a>Tickets {{$cart[$key]['quantity']}}<a href="{{route('bustravel.add_to_basket',[$route->id,date('Y-m-d'),'main_route',1])}}"> +</a></li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endforeach
                        @foreach($stop_over_route_departures as $index => $route)
                        <div class="row">
                            @php
                                $key = array_search($route->id,array_column($cart,'id'));
                                $date_of_travel = $cart[$key]['date_of_travel'];
                                $total_amount += $cart[$key]['quantity']*$cart[$key]['amount'];
                                $start_time = Carbon\Carbon::parse($route->departure_time);
                                $end_time = Carbon\Carbon::parse($route->arrival_time);
                                $duration = $end_time->diffInMinutes($start_time,true);
                                $stop_over_route_tickets += $cart[$key]['quantity'];
                            @endphp
                            <div class="col-md-12 ticket-card cart">
                                <ul class="top-adjust-txt">
                                    <li class="ticket-number">Tickets {{$cart[$key]['quantity']}}</li>

                                </ul>
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title">Departure: {{$route->departure_time}} hrs</h3>
                                        <h5 class="card-text">Est. Duration - {{round($duration/60,1)}} hrs</h5>
                                        <h3 class="price">
                                            <span> RWF {{$cart[$key]['quantity']*$route->route->price}}</span>
                                            <span class="date">{{\Carbon\Carbon::parse($date_of_travel)->format('D M j Y')}} </span>
                                        </h3>
                                        <ul class="list-inline">
                                            <li class="list-inline-item">From: {{$route->route->start_stopover_station->name}}</li>
                                            <li class="list-inline-item">To: {{$route->route->end_stopover_station->name}}</li>
                                            <li class="list-inline-item">Operator: {{$route->route->route->operator->name}}</li>
                                            
                                            <li class="list-inline-item add-btn" ><a href="{{route('bustravel.add_to_basket',[$route->id,date('Y-m-d'),'main_route',-1])}}">- </a>Tickets {{$cart[$key]['quantity']}}<a href="{{route('bustravel.add_to_basket',[$route->id,date('Y-m-d'),'main_route',1])}}"> +</a></li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endforeach
                        <div class="row">
                            <div class="col-md-12 ticket-card cart">
                                <table class="table" summary="Cart Details">
                                    <tbody>
                                        <tr>
                                            <th scope="row">No of Tickets</th>
                                        <td> {{$main_route_tickets+$stop_over_route_tickets}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Trip total</th>
                                        <td>RWF {{$total_amount}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Reserved seat charge</th>
                                            <td>RWF {{$reserve_fee}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Booking fee</th>
                                            <td>RWF {{$booking_fee}}</td>
                                        </tr>
                                        <tr class="total-area">
                                            <th scope="row">Total to pay</th>
                                            <td>RWF {{$total_amount+$reserve_fee+$booking_fee}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h3>Luggage Allowance</h3>
                                <p>One piece of luggage up to 20kg and one small piece of hand luggage. <a>Find Out More</a></p>
                                <button type="submit" class="btn btn-primary mb-2 btn-proceed"><a href="{{route('bustravel.cart.checkout')}}">Pay for Ticket</a></button>
                            </div>
                        </div>
                    </div>
                    <div class="offset-1 col-md-3 area-extras">
                        <div>WE ACCEPT MOMO PAY</div>
                    </div>
                </div>
@endsection
