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
                        @endphp
                        @foreach($route_departures as $index => $route)
                        <div class="row">
                            @php                                
                                $key = array_search($route->id,array_column($cart,'id'));
                                $date_of_travel = $cart[$key]['date_of_travel'];
                                $total_amount += $cart[$key]['amount'];
                            @endphp
                            <div class="col-md-12 ticket-card cart">
                                <ul class="top-adjust-txt">
                                    <li class="ticket-number">Ticket {{$index+1}}/{{count($route_departures)}}</li>
                                    
                                </ul>
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title">{{$route->departure_time}} hrs</h3>
                                        <h5 class="card-text">Est. Duration - 2hrs 30mins</h5>
                                        <h3 class="price">
                                            <span> RWF {{$route->route->price}}</span>
                                            <span class="date">{{\Carbon\Carbon::parse($date_of_travel)->format('D M j Y')}} </span>
                                        </h3>
                                        <ul class="list-inline">
                                            <li class="list-inline-item">From: {{$route->route->start->name}}</li>
                                            <li class="list-inline-item">To: {{$route->route->end->name}}</li>
                                            <li class="list-inline-item">Operator: {{$route->route->operator->name}}</li>
                                            <li class="list-inline-item add-btn"><a href="{{route('bustravel.cart.remove.item',$key)}}"> Remove ticket</a></li>
                                        </ul>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        @endforeach
                        <div class="row">
                            <div class="col-md-12 ticket-card cart">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>Trip total</td>
                                        <td>RWF {{$total_amount}}</td>
                                        </tr>
                                        <tr>
                                            <td>Reserved seat charge</td>
                                            <td>RWF {{$reserve_fee}}</td>
                                        </tr>
                                        <tr>
                                            <td>Booking fee</td>
                                            <td>RWF {{$booking_fee}}</td>
                                        </tr>
                                        <tr class="total-area">
                                            <td>Total to pay</td>
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
                        <div>AREA FOR ANY FUTURE INCLUSIONS</div>
                    </div>
                </div>
@endsection