@extends('bustravel::backend.layouts.app')

@section('title', 'Driver Manifest')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"> Services{{$driver->name??""}}</h1>
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
                  <form action="{{route('bustravel.bookings.manifest.search')}}" method="post" >
                    {{ csrf_field() }}
                    <div class="row">
                      <div class="form-group col-md-3 ">
                        <label>Start Station</label>
                        <select class="form-control select2 {{ $errors->has('bus') ? ' is-invalid' : '' }}" name="bus"  placeholder="Select Station">
                          <option value="">Select Bus</option>
                          @foreach($buses as $bus)
                              <option value="{{$bus->id}}" @php echo $bus->id == $bus_no ? 'selected' :  "" @endphp>{{$bus->number_plate}}</option>
                          @endforeach
                        </select>
                      </div>
                    <div class="form-group col-md-3">
                      <label>From</label>

                      <div class="input-group date timepicker" id="from"  data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input {{ $errors->has('from') ? ' is-invalid' : '' }}" data-target="#from"  name="from" value="{{$from??''}}" required/>
                          <div class="input-group-append" data-target="#from" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-clock" aria-hidden="true"></i></div>
                          </div>
                          @if ($errors->has('from'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('from') }}</strong>
                            </span>
                        @endif
                      </div>

                      </div>
                    <div class="form-group col-md-3">
                      <label>To</label>
                      <div class="input-group date timepicker" id="to"  data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input {{ $errors->has('to') ? ' is-invalid' : '' }}" data-target="#to"  name="to" value="{{$to??''}}" required/>
                          <div class="input-group-append" data-target="#to" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-clock" aria-hidden="true"></i></div>
                          </div>
                    </div>
                  </div>
                    <div class="form-group col-md-6">
                      <label><br></label>
                      <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                  </div>
                  </form>
                  </div>
              </div>
            <div class="row">
               <div class="col-md-12">
                 <table id="example1" class="table table-bordered table-hover table-striped dataTable" role="grid" aria-describedby="example1_info" summary="My Routes">
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
                                <td>{{number_format($route_departure_time->route->price,2)}} </td>
                                <td>{{$route_departure_time->bus->number_plate??'NONE'}} - {{$route_departure_time->bus->seating_capacity??''}}</td>
                                <td>{{$route_departure_time->departure_time}}</td>
                                <td>  <a title="Manage" onclick="return confirm('Are you sure you want to Manage this Route')" href="{{route('bustravel.bookings.route.tracking',$route_departure_time->id)}}"><i class="fas fa-edit" aria-hidden="true"></i> Manage</a>


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
  $('.timepicker').datetimepicker({
             format: 'HH:mm'
         });
})
</script>

@stop
