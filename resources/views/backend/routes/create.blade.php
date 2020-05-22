@extends('bustravel::backend.layouts.app')

@section('title', 'Routes')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small><a href="{{route('bustravel.routes')}}" class="btn btn-info">Back</a></small> Routes </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">routes</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Add Route</h5>
                </div>
                <div class="card-body">
                    <div class="callout">
                        Please select your stations in order with the time at each station, add it to the route, repeat to add all stopovers,  then click generate to generate all possible combinations of sub-routes, add price, choose days of the week the service operates and save
                    </div>
                    <form id="add-stations-form" method="GET">
                        <div class="row">
                            <div class="col-md-3 mb-4">
                                <label>Station</label>
                                <select class="form-control" name="station" required>
                                    <option value="" selected>Choose station...</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-4">
                                <label>Time at this station</label>
                                <input type="time" class="form-control" name="time" value="" placeholder="00:00:00" required>
                            </div>
                            <div class="col-md-2 mb-4">
                                <label>.</label>
                                <input type="submit" class="btn btn-block btn-primary" id="add" value="Add">
                            </div>
                            <div class="col-md-2 mb-4">
                                <label>.</label>
                                <input type="button" class="btn btn-block btn-danger" id="remove" value="Remove">
                            </div>
                            <div class="col-md-2 mb-4">
                                <label>.</label>
                                <input type="button" class="btn btn-block btn-success" id="generate" value="Generate">
                            </div>
                        </div>
                    </form>
                    <div class="row" id="stations"></div>
                    <form method="POST" action="{{route('bustravel.routes.store')}}">
                        {{csrf_field() }}                    


                        <div class="responsive" id="routes">
                            <table class="table" id="routes-tbl">
                                <caption>generated routes</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">From</th>
                                        <th scope="col">To</th>
                                        <th scope="col">In</th>
                                        <th scope="col">Out</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Order</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 ">
                                <label>Bus To Use</label>
                                <select class="form-control select2 {{ $errors->has('bus_id') ? ' is-invalid' : '' }}" name="bus_id"  placeholder="Select bus">
                                  <option value="">Select Bus</option>
                                  @foreach($buses as $bus)
                                      <option value="{{$bus->id}}" {{ old('bus_id') == $bus->id ? 'selected' :  "" }}>{{$bus->number_plate}} - {{$bus->operator->name}} / Seating Capacity - {{$bus->seating_capacity}}</option>
                                  @endforeach
                                </select>
                                @if ($errors->has('bus_id'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('bus_id') }}</strong>
                                    </span>
                                @endif
                              </div>
                              <div class="form-group col-md-6 ">
                                <label>Drivers</label>
                                <select class="form-control select2 {{ $errors->has('end_station') ? ' is-invalid' : '' }}" name="driver_id"  placeholder="Select Operator">
                                  <option value="">Select Driver</option>
                                  @foreach($drivers as $driver)
                                      <option value="{{$driver->id}}" {{ old('driver_id') == $driver->id ? 'selected' :  "" }} >{{$driver->name}} - {{$driver->operator->name}}</option>
                                  @endforeach
                                </select>
                                @if ($errors->has('driver_id'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('driver_id') }}</strong>
                                    </span>
                                @endif
                              </div>
                            <div class="form-group col-md-6">
                                <label> Days of the week</label>
                                <select class="form-control select2 {{ $errors->has('days_of_week') ? ' is-invalid' : '' }}" name="days_of_week[]"  placeholder="Select Days of Week" multiple required>
                                <option value="Monday">Monday</option>
                                <option  value="Tuesday">Tuesday</option>
                                <option  value="Wednesday">Wednesday</option>
                                <option  value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                                <option value="Public">Public</option>
                                </select>
                                @if ($errors->has('days_of_week'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('days_of_week') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class=" col-md-3 form-group">
                                <label for="signed" class=" col-md-12 control-label">Auto Create Inverse</label>
                                <label class="radio-inline">
                                  <input type="radio"  name="has_inverse" value="1"> Yes</label>
                                </label>
                               <label class="radio-inline">
                                  <input type="radio"  name="has_inverse" value="0" checked> No</label>
                               </label>
                            </div>
                    </div>
                        <button class="btn btn-info">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@stop

@section('css')

@stop

@section('js')
   @parent
   <script type="text/javascript">
       'use strict';

        function generateRoutes(stations)
        {
            var routes = [];

            var x;

            for(x = 0; x<stations.length; x++) {
                var y
                for(y=(x+1); y<stations.length; y++) {
                    var route = {
                        "from": stations[x],
                        "to": stations[y]
                    };
                    routes.push(route);
                }
            }

            return routes;
        }

        $(document).ready(function() {
            var stations = [];

            $('select[name=station]').select2({
                width: '100%',
                allowClear: true,
                minimumInputLength: 2,
                placeholder: "Choose station...",
                ajax: {
                    type: 'GET',
                    url: "{{route('bustravel.stations.suggest')}}",
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    data: function (params) {
                        return {
                            station: params.term,
                            limit: 10
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.stations.map(function (station) {
                                return {
                                    id: station.id,
                                    text: station.name
                                };
                            })
                        };
                    },
                    error: function (xhr) {
                        console.error(xhr);
                    }
                }
            });

            $('#add-stations-form').on('submit', function(e) {
                e.preventDefault();

                var data = $('select[name=station]').select2('data')[0];

                var stationIds = stations.map(function(station) {
                    return station.id;
                });

                if (stationIds.indexOf(data.id) !== -1) {
                    return;
                }

                var chosenTime = $('input[name=time]').val();

                var noOfStations = stations.length;

                if(stations.length) {
                    var lastStation = stations[(noOfStations - 1)];

                    console.log(lastStation.time + ' is after ' + chosenTime + '?');

                    if(moment(lastStation.time, "HH:mm").isAfter(moment(chosenTime, "HH:mm"))) {
                        alert('Last station time was '+lastStation.time);
                        return;
                    }
                }

                stations.push({
                    id: data.id,
                    name: data.text,
                    time: chosenTime
                });

                console.log({stations: stations});

                var input = '<div class="col-md-3 mb-4"><input type="text" name="stations[]" class="form-control" value="'+data.text+'" readonly/><div>';

                var old_stations = $('div#stations').html();

                $('div#stations').html(old_stations+input);

                $('select[name=station]').val(null).trigger('change');

                $('input[name=time]').val('');
            });

            $('input[id=remove]').on('click', function() {
                $('input[name="stations[]"]:last').closest('div').remove();
                stations.pop();
            });

            $('input[id=generate]').on('click', function() {
                $('table[id=routes-tbl] tbody').html('');

                var routes = generateRoutes(stations);

                console.log({routes: routes});

                routes.forEach(function(route, index) {
                    var row = '';

                    row += '<tr>';
                    row += '<th><i class="far fa-trash-alt text-danger" id="del_route"></i></th>';
                    row += '<td><select class="form-control" name="routes['+index+'][from]" required><option value="'+route.from.id+'">'+route.from.name+'</option></select></td>';
                    row += '<td><select class="form-control" name="routes['+index+'][to]" required><option value="'+route.to.id+'">'+route.to.name+'</option></select></td>';
                    row += '<td><input type="time" class="form-control" name="routes['+index+'][in]" value="'+route.from.time+'" required></td>';
                    row += '<td><input type="time" class="form-control" name="routes['+index+'][out]" value="'+route.to.time+'" required></td>';
                    row += '<td><input type="text" class="form-control" name="routes['+index+'][price]" value="" required></td>';
                    row += '<td><input type="text" class="form-control" name="routes['+index+'][order]" value="'+index+'" required></td>';
                    row += '</tr>';

                    $('table[id=routes-tbl] tbody').append(row);
                });
            });

            $('table[id=routes-tbl] tbody').on('click', '#del_route', function() {
                if(confirm("Are you sure?")) {
                    $(this).closest('tr').remove();
                }
            });
        });
   </script>
@stop
