@extends('bustravel::backend.layouts.app')

@section('title', 'Routes')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small><a href="{{route('bustravel.routes')}}" class="btn btn-info">Back</a></small> Buses </h1>
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
            <h5 class="card-title">Edit {{$route->start->name}} ( {{$route->start->code}} )  - {{$route->end->name}} ( {{$route->end->code}} ) Route</h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.routes.update',$route->id)}}" method="POST">
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <div class="form-group col-md-6">
                         <label> Operator</label>
                         <select class="form-control select2 {{ $errors->has('operator_id') ? ' is-invalid' : '' }}" name="operator_id"  placeholder="Select Operator">
                           <option value="">Select Operator</option>
                           @foreach($bus_operators as $operator)
                               <option value="{{$operator->id}}" @php echo $route->operator_id == $operator->id ? 'selected' :  "" @endphp>{{$operator->name}} - {{$operator->code}}</option>
                           @endforeach
                         </select>
                         @if ($errors->has('operator_id'))
                             <span class="invalid-feedback">
                                 <strong>{{ $errors->first('operator_id') }}</strong>
                             </span>
                         @endif
                    </div>
                    <div class="form-group col-md-3 ">
                      <label>Start Station</label>
                      <select class="form-control select2 {{ $errors->has('start_station') ? ' is-invalid' : '' }}" name="start_station"  placeholder="Select Operator">
                        <option value="">Select Station</option>
                        @foreach($stations as $station)
                            <option value="{{$station->id}}" @php echo $route->start_station == $station->id ? 'selected' :  "" @endphp>{{$station->name}} - {{$station->code}}</option>
                        @endforeach
                      </select>
                      @if ($errors->has('start_station'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('start_station') }}</strong>
                          </span>
                      @endif
                    </div>
                    <div class="form-group col-md-3 ">
                      <label>End Station</label>
                      <select class="form-control select2 {{ $errors->has('end_station') ? ' is-invalid' : '' }}" name="end_station"  placeholder="Select Operator">
                        <option value="">Select Station</option>
                        @foreach($stations as $station)
                            <option value="{{$station->id}}" @php echo $route->end_station == $station->id ? 'selected' :  "" @endphp>{{$station->name}} - {{$station->code}}</option>
                        @endforeach
                      </select>
                      @if ($errors->has('end_station'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('end_station') }}</strong>
                          </span>
                      @endif
                    </div>
                    <div class="form-group col-md-3 ">
                      <label>Price</label>
                      <input type="text"  name="price" value="{{number_format($route->price,2)}}" class="form-control {{ $errors->has('price') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Price" >
                      @if ($errors->has('price'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('price') }}</strong>
                          </span>
                      @endif
                    </div>
                    <div class="form-group col-md-3 ">
                      <label>Return Price</label>
                      <input type="text"  name="return_price" value="{{number_format($route->return_price,2)}}" class="form-control {{ $errors->has('price') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Return Price" >
                      @if ($errors->has('return_price'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('return_price') }}</strong>
                          </span>
                      @endif
                    </div>
                    <div class="form-group col-md-3 ">
                      <label>Departure Time</label>
                      <input type="text"  name="departure_time" value="{{$route->departure_time}}" class="form-control {{ $errors->has('departure_time') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Departure Time" >
                      @if ($errors->has('departure_time'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('departure_time') }}</strong>
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
