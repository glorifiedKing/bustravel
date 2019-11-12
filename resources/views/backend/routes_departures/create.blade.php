@extends('bustravel::backend.layouts.app')

@section('title', 'Routes Departure Times')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small><a href="{{route('bustravel.routes.departures')}}" class="btn btn-info">Back</a></small> Routes </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">routes departure times</li>
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
            <h5 class="card-title">Add Route Departure Time </h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.routes.departures.store')}}" method="POST" >
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <div class="form-group col-md-6">
                         <label> Routes</label>
                         <select class="form-control select2 {{ $errors->has('route_id') ? ' is-invalid' : '' }}" name="route_id"  placeholder="Select Operator">
                           <option value="">Select Route</option>
                           @foreach($routes as $route_course)
                               <option value="{{$route_course->id}}" @php echo old('route_id') == $route_course->id ? 'selected' :  "" @endphp>{{$route_course->start->name}} ( {{$route_course->start->code}} ) - {{$route_course->end->name}} ( {{$route_course->end->code}} )</option>
                           @endforeach
                         </select>
                         @if ($errors->has('route_id'))
                             <span class="invalid-feedback">
                                 <strong>{{ $errors->first('route_id') }}</strong>
                             </span>
                         @endif
                    </div>
                    <div class="form-group col-md-6 ">
                      <label>Start Bus</label>
                      <select class="form-control select2 {{ $errors->has('bus_id') ? ' is-invalid' : '' }}" name="bus_id"  placeholder="Select Operator">
                        <option value="">Select Bus</option>
                        @foreach($buses as $bus)
                            <option value="{{$bus->id}}" @php echo old('bus_id') == $bus->id ? 'selected' :  "" @endphp>{{$bus->number_plate}} - {{$bus->operator->name}} / Seating Capacity - {{$bus->seating_capacity}}</option>
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
                            <option value="{{$driver->id}}" @php echo old('driver_id') == $driver->id ? 'selected' :  "" @endphp>{{$driver->name}} - {{$driver->operator->name}}</option>
                        @endforeach
                      </select>
                      @if ($errors->has('driver_id'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('driver_id') }}</strong>
                          </span>
                      @endif
                    </div>
                    <div class="form-group col-md-3 ">
                      <label>Departure Time</label>
                      <input type="text"  name="departure_time" value="{{old('departure_time')}}" class="form-control {{ $errors->has('departure_time') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Departure Time" >
                      @if ($errors->has('departure_time'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('departure_time') }}</strong>
                          </span>
                      @endif
                    </div>
                    <div class=" col-md-12 form-group">
                    </div>
                    <div class=" col-md-6 form-group">
                        <label for="signed" class=" col-md-12 control-label">Booking Restricted By Bus Seating Capacity</label>
                        <label class="radio-inline">
                          <input type="radio" id="Active" name="restricted_by_bus_seating_capacity" value="1" checked> Yes</label>
                        </label>
                       <label class="radio-inline">
                          <input type="radio" id="Deactive" name="restricted_by_bus_seating_capacity" value="0" > No</label>
                       </label>
                    </div>
                    <div class=" col-md-12 form-group">
                    </div>
                    <div class=" col-md-3 form-group">
                        <label for="signed" class=" col-md-12 control-label">Status</label>
                        <label class="radio-inline">
                          <input type="radio" id="Active" name="status" value="1" checked> Active</label>
                        </label>
                       <label class="radio-inline">
                          <input type="radio" id="Deactive" name="status" value="0" > Deactive</label>
                       </label>
                    </div>
                  </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
              <div class="form-group col-md-12">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
              </div>
            </form>
            </div>

            <!-- /.row -->
            </div>
            <!-- ./card-body -->

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
         $('div.alert').not('.alert-danger').delay(5000).fadeOut(350);
         $('.select2').select2();
       })
</script>
@stop
