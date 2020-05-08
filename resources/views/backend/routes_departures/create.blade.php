@extends('bustravel::backend.layouts.app')

@section('title', 'Routes Departure Times')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small><a href="{{route('bustravel.routes.edit',$route->id)}}" class="btn btn-info">Back</a></small> Routes  </h1>
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
          @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <ul>
             @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
             @endforeach
        </ul>
       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
       <span aria-hidden="true">&times;</span>
       </button>
      </div>
      @endif
        <div class="card">
            <div class="card-header">
            <h5 class="card-title">Add Route {{$route->start->name}} [ {{$route->start->code}} ] - {{$route->end->name}} [ {{$route->end->code}} ]  Departure/Arrival  Time</h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="box-body">
              <form role="form" action="{{route('bustravel.routes.departures.store')}}" method="POST" >
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <input type="hidden"  name="route_id" value="{{$route->id}}">
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
                    <div class="form-group col-md-3   ">
                      <label>Departure Time</label>
                      <div class="input-group date timepicker" id="departuretime"  data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input {{ $errors->has('departure_time') ? ' is-invalid' : '' }}" data-target="#departuretime"  name="departure_time" value="{{old('departure_time')}}"/>
                          <div class="input-group-append" data-target="#departuretime" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-clock" aria-hidden="true"></i></div>
                          </div>
                          @if ($errors->has('departure_time'))
                              <span class="invalid-feedback">
                                  <strong>{{ $errors->first('departure_time') }}</strong>
                              </span>
                          @endif
                      </div>

                    </div>
                    <div class="form-group col-md-3   ">
                      <label>Arrival Time</label>
                      <div class="input-group date timepicker" id="arrival_time" data-target-input="nearest">
                         <input type="text" class="form-control datetimepicker-input {{ $errors->has('arrival_time') ? ' is-invalid' : '' }}" data-target="#arrival_time"  name="arrival_time" value="{{old('arrival_time')}}"/>
                         <div class="input-group-append" data-target="#arrival_time" data-toggle="datetimepicker">
                             <div class="input-group-text"><i class="fa fa-clock" aria-hidden="true"></i></div>
                         </div>
                         @if ($errors->has('arrival_time'))
                           <span class="invalid-feedback">
                               <strong>{{ $errors->first('arrival_time') }}</strong>
                           </span>
                       @endif
                      </div>

                    </div>
                    <div class=" col-md-12 form-group"><h4>StopOvers</h4></div>
                    <div class=" col-md-12 form-group">
                      @php $stopovers =$route->stopovers()->orderBy('order')->get(); @endphp
                      <table id="new-table" class="table table-striped table-hover">
                           <thead>
                             <tr>
                               <th scope="col" style="width: 30px"></th>
                               <th scope="col"> Stop Over</th>
                               <th scope="col">Arrival Time</th>
                               <th scope="col">Departure Time</th>
                             </tr>
                           </thead>

                           <tbody>
                             @foreach($stopovers as $stoverstation)
                            <tr item-id='{{$stoverstation->stopover_id}}'>
                              <td><input type='checkbox' name='checkeditem[]'></td>
                              <td >
                                   {{$stoverstation->start_stopover_station->name}} - {{$stoverstation->end_stopover_station->name}}
                                  <input type='hidden' value='{{$stoverstation->id}}' name='stopover_routeid[]'>
                              </td>
                              <td >
                                <div class="form-group">
                                 <div class="input-group date timepicker" id="arrival_time{{$stoverstation->id}}" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input {{ $errors->has('stopover_arrival_time') ? ' is-invalid' : '' }}" data-target="#arrival_time{{$stoverstation->id}}"  name="stopover_arrival_time[]" value="" required/>
                                    <div class="input-group-append" data-target="#arrival_time{{$stoverstation->id}}" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-clock" aria-hidden="true"></i></div>
                                    </div>
                                    @if ($errors->has('stopover_arrival_time'))
                                      <span class="invalid-feedback">
                                          <strong>{{ $errors->first('stopover_arrival_time') }}</strong>
                                      </span>
                                  @endif
                                 </div>
                               </div>
                              </td>
                              <td>
                                <div class="form-group">
                                 <div class="input-group date timepicker" id="departure_time{{$stoverstation->id}}" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#departure_time{{$stoverstation->id}}"  name="stopover_departure_time[]" value="" required/>
                                    <div class="input-group-append" data-target="#departure_time{{$stoverstation->id}}" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-clock" aria-hidden="true"></i></div>
                                    </div>
                                 </div>
                                </div>
                              </td>
                            </tr>
                             @endforeach


                           </tbody>
                        </table>
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
                    <div class="form-group col-md-12">
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
              <input type="hidden" name="has_stover" value="{{$stopovers->count()}}">
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
         $('.timepicker').datetimepicker({
                    format: 'HH:mm'
                });
       })
</script>
@stop
