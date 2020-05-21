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
                    <form id="add-stations-form" method="GET">
                        <div class="row">
                            <div class="col-md-3 mb-4">
                                <select class="form-control" name="station" required>
                                    <option value="" selected>Choose station...</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-4">
                                <input type="time" class="form-control" name="time" value="" placeholder="00:00:00" required>
                            </div>
                            <div class="col-md-2 mb-4">
                                <input type="submit" class="btn btn-block btn-primary" id="add" value="Add">
                            </div>
                            <div class="col-md-2 mb-4">
                                <input type="button" class="btn btn-block btn-danger" id="remove" value="Remove">
                            </div>
                            <div class="col-md-2 mb-4">
                                <input type="button" class="btn btn-block btn-success" id="generate" value="Generate">
                            </div>
                        </div>
                    </form>
                    <div class="row" id="stations"></div>
                    <form method="POST" action="">
                        <div class="responsive" id="routes">
                            <table class="table" id="routes-tbl">
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
                    url: 'http://localhost:8000/transit/stations/suggest',
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
