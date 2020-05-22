@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')
@section('navigaton-bar')
@endsection


            @section('content')
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="h3-bottom">Bus Times  for {{date('D M j Y')}} [<a  href="">Reload Routes</a>]</h3>
                        @php
                            $cart = session()->get('cart.items');
                            $total_amount = 0;
                            $reserve_fee = 0;
                            $booking_fee = 0;
                        @endphp
                </div>
            </div>
                        <div class="row">
                            <div class="col-md-12">

                                <table style="width:100%" id="route_table" class="table table-striped table-hover table-responsive" summary="Routes Details">
                                    <thead>
                                        <tr>
                                            <tr>
                                                <th scope="col">From </th>
                                                <th scope="col">To</th>
                                                <th scope="col">Due</th>
                                                <th scope="col">Arrival</th>
                                                <th scope="col">Seats Left</th>
                                                <th scope="col">Stop Overs</th>
                                                <th scope="col">Operator</th>
                                            </tr>
                                            <tr>
                                                <th scope="col">From </th>
                                                <th scope="col">To</th>
                                                <th scope="col">Due</th>
                                                <th scope="col">Arrival</th>
                                                <th scope="col">Seats Left</th>
                                                <th scope="col">Stop Overs</th>
                                                <th scope="col">Operator</th>
                                            </tr>
                                      </tr>
                                    </thead>
                                    <tbody>

                                      @foreach($routes_times as $index => $route_time)
                                      <tr>
                                    <td>{{$route_time->route->start->name??'None'}} ( {{$route_time->route->start->code??'None'}} ) </td>
                                      <td> {{$route_time->route->end->name??'None'}} ( {{$route_time->route->end->code??'None'}} ) <a class="btn" href="{{route('bustravel.add_to_basket',[$route_time->id,date('Y-m-d'),'main_route',1])}}">Book main route</a></td>
                                      <td>{{$route_time->departure_time}}</td>
                                      <td>{{$route_time->arrival_time}}</td>
                                      <td>{{$route_time->number_of_seats_left(date('Y-m-d')) ?? 0}}</td>
                                      @php $stopoverstimes =$route_time->stopovers_times()->orderBy('id','ASC')->get(); @endphp
                                      <td>
                                         @foreach($stopoverstimes as $times)
                                         {{$times->route_stopover->start_stopover_station->name??""}} to {{$times->route_stopover->end_stopover_station->name??""}}  - {{$times->departure_time??""}} <a class="btn" href="{{route('bustravel.add_to_basket',[$times->id,date('Y-m-d'),'stop_over_route',1])}}">Book</a><br>
                                          <hr>
                                         @endforeach
                                      </td>
                                      <td>{{$route_time->route->operator->name??""}}</td>
                                      </tr>

                                      @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>

@endsection
@section('js')
<script>
$(document).ready(function(){
    $("#route_table thead tr:eq(1) th").each( function () {
                    var title = $("#route_table thead tr:eq(0) th").eq( $(this).index() ).text();
                    $(this).html( '<input size="5" type="text" placeholder="Search.." >' );
                } );
                var table =  $("#route_table").DataTable({
              	});


            // Apply the search
              table.columns().every(function (index) {
                  $("#route_table thead tr:eq(1) th:eq(" + index + ") input").on("keyup change", function () {
                     if(index == 100)
                     {
                       if(this.value.length < 1){
                         table.column($(this).parent().index() + ":visible")
                             .search("")
                             .draw();
                       }
                       else {
                         table.column($(this).parent().index() + ":visible")
                             .search("^" + this.value + "$", true, false, true)
                             .draw();
                       }

                     }
                     else {
                      table.column($(this).parent().index() + ":visible")
                          .search(this.value)
                          .draw();
                        }
                  });
              });
});

</script>

@endsection
