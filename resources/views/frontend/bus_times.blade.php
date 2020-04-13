@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')
@section('navigaton-bar')


@endsection
            @section('content')
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="h3-bottom">Bus Times </h3>
                        @php
                            $cart = session()->get('cart.items');
                            $total_amount = 0;
                            $reserve_fee = 0;
                            $booking_fee = 0;
                        @endphp

                        <div class="row">
                            <div class="col-md-12 ticket-card cart">
                              {!! $routes_times->links() !!}
                                <table class="table">
                                    <tbody>
                                      <tr>
                                      <th>Route </th>
                                      <th>Due</th>
                                      <th>Arrival</th>
                                      <th>Stop Overs</th>
                                      <th>Operator</th>
                                      </tr>
                                      @foreach($routes_times as $index => $route_time)
                                      <tr>
                                      <td>{{$route_time->route->start->name??'None'}} ( {{$route_time->route->start->code??'None'}} ) - {{$route_time->route->end->name??'None'}} ( {{$route_time->route->end->code??'None'}} )</td>
                                      <td>{{$route_time->departure_time}}</td>
                                      <td>{{$route_time->arrival_time}}</td>
                                      @php $stopoverstimes =$route_time->stopovers_times()->orderBy('id','ASC')->get(); @endphp
                                      <td>
                                         @foreach($stopoverstimes as $times)
                                          {{$times->route_stopover->end_stopover_station->name??""}} ( {{$times->route_stopover->end_stopover_station->code??""}} ) - {{$times->arrival_time??""}}<br>
                                          <hr>
                                         @endforeach
                                      </td>
                                      <td>{{$route_time->route->operator->name??""}}</td>
                                      </tr>

                                      @endforeach
                                    </tbody>
                                </table>
                                {!! $routes_times->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
@endsection
