@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')
@section('navigaton-bar')
@endsection


            @section('content')
                <div class="row">
                    <div class="col-md-12 bus-time-headings">
                    <h4 class="h4-bottom">Bus Times  for {{date('D M j Y')}} Station: {{$selected_station->name}}</h4>
                        <form method="GET"> 
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Select Station</label>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control select_search" name="start_station">
                                        @foreach ($stations as $item)
                                            <option value="{{$item->id}}" @if($selected_station->id == $item->id) selected @endif>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn" style="background-color:#fccc04">Reload Routes</button>
                                </div>
                            </div>
                        </form>
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
                                                <th scope="col">Operator</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                            <tr>
                                                <th scope="col">From </th>
                                                <th scope="col">To</th>
                                                <th scope="col">Due</th>
                                                <th scope="col">Arrival</th>
                                                <th scope="col">Seats Left</th>                                                
                                                <th scope="col">Operator</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                      </tr>
                                    </thead>
                                    <tbody>

                                      @foreach($departure_times as $index => $route_time)
                                      <tr>
                                        <td>{{$route_time->route->start->name??'None'}}  </td>
                                        <td> {{$route_time->route->end->name??'None'}} </td>
                                        <td>{{$route_time->departure_time}}</td>
                                        <td>{{$route_time->arrival_time}}</td>
                                        <td>{{$route_time->number_of_seats_left(date('Y-m-d')) ?? 0}}</td>                                       
                                        
                                        <td>{{$route_time->route->operator->name??""}}</td>
                                        <td><a class="btn" href="{{route('bustravel.add_to_basket',[$route_time->id,date('Y-m-d'),'main_route',1])}}">Book</a></td>
                                      </tr>
                                      @endforeach

                                      @foreach($departure_times_stop_over as $index => $route_time)
                                      <tr>
                                        <td>{{$route_time->route->start_stopover_station->name??'None'}}  </td>
                                        <td> {{$route_time->route->end_stopover_station->name??'None'}} </td>
                                        <td>{{$route_time->departure_time}}</td>
                                        <td>{{$route_time->arrival_time}}</td>
                                        <td>{{$route_time->main_route_departure_time->number_of_seats_left(date('Y-m-d')) ?? 0}}</td>                                     
                                        
                                        <td>{{$route_time->route->route->operator->name??""}}</td>
                                        <td><a class="btn" href="{{route('bustravel.add_to_basket',[$route_time->id,date('Y-m-d'),'stop_over_route',1])}}">Book</a></td>
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
    $('.select_search').select2({
                        width: 'resolve' // need to override the changed default
                    });
    $("#route_table thead tr:eq(1) th").each( function () {
                    var title = $("#route_table thead tr:eq(0) th").eq( $(this).index() ).text();
                    $(this).html( '<input size="5" type="text" placeholder="Search.." >' );
                } );
                var table =  $("#route_table").DataTable({
                    "order": [[ 2, "asc" ]]
              	});


            // Apply the search
              table.columns().every(function (index) {
                  $("#route_table thead tr:eq(1) th:eq(" + index + ") input").on("keyup change", function () {
                     if(index == 0)
                     {

                         table.search(this.value, false, false, true)
                             .draw();


                    }
                    else
                    {
                      table.column($(this).parent().index() + ":visible")
                          .search(this.value)
                          .draw();
                    }
                  });
              });
});

</script>

@endsection
