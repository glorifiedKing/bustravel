@extends('bustravel::backend.layouts.app')

@section('title', 'Bookings')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">My Routes -  {{$driver->name??""}}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">bookings</li>
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
            <h5 class="card-title">My Routes</h5>

            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
               <div class="col-md-12">
                 <table id="example1" class="table table-bordered table-hover table-striped dataTable" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr>
                                <th scope="col">Status</th>
                                <th scope="col">Operator</th>
                                <th scope="col">Route</th>
                                <th scope="col">Price</th>
                                <th scope="col">Bus </th>
                                <th scope="col">times</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach ($driver_routes as $route_departure_time)
                        @php
                        $manage =$route_departure_time->route_times_tracking()->where('date_of_travel',date('Y-m-d'))->count();
                        @endphp
                            <tr>
                              <td>@if($route_departure_time->status==1)
                                    <span class="badge badge-success"> <i class="fas fa-check" aria-hidden="true"></i></span>
                                  @else
                                  <span class="badge badge-danger"> <i class="fas fa-times" aria-hidden="true"></i></a></span>

                                  @endif
                               </td>
                                <td>{{$route_departure_time->route->operator->name}}</td>
                                <td>{{$route_departure_time->route->start->code??'None'}} - {{$route_departure_time->route->end->code??'None'}}</td>
                                <td>{{number_format($route_departure_time->route->price,2)}} - {{number_format($route_departure_time->route->return_price,2)}}</td>
                                <td>{{$route_departure_time->bus->number_plate??'NONE'}} - {{$route_departure_time->bus->seating_capacity??''}}</td>
                                <td>{{$route_departure_time->departure_time}} - {{$route_departure_time->arrival_time}}</td>
                                <td>
                                  @if($manage==0)
                                    @if(auth()->user()->hasAnyRole('BT Driver'))
                                      <a title="Manage" onclick="return confirm('Are you sure you want to Manage this Route')" href="{{route('bustravel.bookings.route.tracking',$route_departure_time->id)}}"><i class="fas fa-edit" aria-hidden="true"></i> Manage</a>
                                    @else
                                    <span class="badge badge-warning">No passanger on the bus Yet</span>
                                     <a title="View" href="{{route('bustravel.routes.departures.edit',$route_departure_time->id)}}"><i class="fas fa-edit" aria-hidden="true"></i></a>
                                    @endif
                                  @else
                                    <a title="Manage" onclick="return confirm('Are you sure you want to Manage this Route')" href="{{route('bustravel.bookings.route.tracking',$route_departure_time->id)}}"><i class="fas fa-edit" aria-hidden="true"></i> Manage</a>
                                  @endif


                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                    </table>
               </div>
            </div>
            <!-- /.row -->
            </div>
            <!-- ./card-body -->
            <div class="card-footer">
            <div class="row">
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-success"><i class="fas fa-caret-up" aria-hidden="true"></i> 17%</span>
                    <h5 class="description-header">$35,210.43</h5>
                    <span class="description-text">TOTAL NUMBER OF BOOKINGS</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-warning"><i class="fas fa-caret-left" aria-hidden="true"></i> 0%</span>
                    <h5 class="description-header">$10,390.90</h5>
                    <span class="description-text">TOTAL NUMBER OF BOOKINGS</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-success"><i class="fas fa-caret-up" aria-hidden="true"></i> 20%</span>
                    <h5 class="description-header">$24,813.53</h5>
                    <span class="description-text">TOTAL NUMBER OF BOOKINGS</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block">
                    <span class="description-percentage text-danger"><i class="fas fa-caret-down" aria-hidden="true"></i> 18%</span>
                    <h5 class="description-header">1200</h5>
                    <span class="description-text">TOTAL NUMBER OF BOOKINGS</span>
                </div>
                <!-- /.description-block -->
                </div>
            </div>
            <!-- /.row -->
            </div>
            <!-- /.card-footer -->
        </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
</div>
@stop

@section('css')

@stop

@section('js')
    @parent
    <script>
        $(function () {
var table = $('#example1').DataTable({
      responsive: false,
      dom: 'Blfrtip',
      buttons: [
        {
          extend: 'excelHtml5',
          exportOptions: {
            columns: ':visible'
          }
        },
        {
          extend: 'pdfHtml5',
          exportOptions: {
            columns: ':visible'
          }
        },
      'colvis',
        //'selectAll',
          //	'selectNone'
      ],
            });
  $('div.alert').not('.alert-danger').delay(5000).fadeOut(350);
})
</script>

@stop
