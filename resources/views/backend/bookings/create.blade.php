@extends('bustravel::backend.layouts.app')

@section('title', 'Bookings')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small><a href="{{route('bustravel.bookings')}}" class="btn btn-info">Back</a></small> Routes </h1>
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
            <h5 class="card-title">Add Booking </h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.bookings.store')}}" method="POST" >
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <div class="form-group col-md-6">
                         <label> Routes</label>
                         <select class="form-control select2 {{ $errors->has('routes_departure_time_id') ? ' is-invalid' : '' }}" name="routes_departure_time_id"  placeholder="Select Operator">
                           <option value="">Select Route</option>
                           @foreach($routes_times as $route_course)
                               <option value="{{$route_course->id}}" @php echo old('routes_departure_time_id') == $route_course->id ? 'selected' :  "" @endphp>{{$route_course->route->start->name}} ( {{$route_course->route->start->code}} ) - {{$route_course->route->end->name}} ( {{$route_course->route->end->code}} ) / {{$route_course->departure_time}}</option>
                           @endforeach
                         </select>
                         @if ($errors->has('routes_departure_time_id'))
                             <span class="invalid-feedback">
                                 <strong>{{ $errors->first('routes_departure_time_id') }}</strong>
                             </span>
                         @endif
                    </div>
                    <div class="form-group col-md-6 ">
                      <label>Start Bus</label>
                      <select class="form-control select2 {{ $errors->has('user_id') ? ' is-invalid' : '' }}" name="user_id"  placeholder="Select Users">
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{$user->id}}" @php echo old('user_id') == $user->id ? 'selected' :  "" @endphp>{{$user->name}}</option>
                        @endforeach
                      </select>
                      @if ($errors->has('user_id'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('user_id') }}</strong>
                          </span>
                      @endif
                    </div>
                    <div class="form-group col-md-3 ">
                      <label>Amount</label>
                      <input type="text"  name="amount" value="{{old('amount')}}" class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Amount" >
                      @if ($errors->has('amount'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('amount') }}</strong>
                          </span>
                      @endif
                    </div>
                    <div class="form-group col-md-3 ">
                      <label>Date Paid</label>
                      <input type="date"  name="date_paid" value="{{old('date_paid')}}" class="form-control {{ $errors->has('date_paid') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Date Paid" >
                      @if ($errors->has('date_paid'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('date_paid') }}</strong>
                          </span>
                      @endif
                    </div>
                    <div class="form-group col-md-3 ">
                      <label>Travel Date</label>
                      <input type="date"  name="date_of_travel" value="{{old('date_of_travel')}}" class="form-control {{ $errors->has('date_of_travel') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Travel Date" >
                      @if ($errors->has('date_of_travel'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('date_of_travel') }}</strong>
                          </span>
                      @endif
                    </div>
                    <div class=" col-md-12 form-group">
                      <h4>Custom Fields</h4>
                    </div>
                    @foreach($custom_fields as $fields)
                       <div class="form-group col-md-3 ">
                         <label>{{$fields->field_name}}</label>
                         <input type=hidden name="field_id[]" value="{{$fields->id}}">
                         <input type="text"  name="field_value[]" value="" class="form-control {{ $errors->has('date_paid') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="{{$fields->field_name}}" @php echo $fields->is_required == 1? 'required' :  "" @endphp >
                       </div>
                    @endforeach
                    <div class=" col-md-12 form-group">
                    </div>
                    <div class=" col-md-12 form-group">
                    </div>
                    <div class=" col-md-3 form-group">
                        <label for="signed" class=" col-md-12 control-label">Status</label>
                        <label class="radio-inline">
                          <input type="radio" id="Active" name="status" value="1" >  Paid </label>
                        </label>
                       <label class="radio-inline">
                          <input type="radio" id="Deactive" name="status" value="0" checked>  Not Paid</label>
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
