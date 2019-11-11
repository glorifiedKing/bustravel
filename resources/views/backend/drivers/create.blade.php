@extends('bustravel::backend.layouts.app')

@section('title', 'Drivers')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small><a href="{{route('bustravel.drivers')}}" class="btn btn-info">Back</a></small> Bus Drivers </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">drivers</li>
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
            <h5 class="card-title">Add Driver </h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.drivers.store')}}" method="POST" enctype="multipart/form-data" >
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <div class="form-group col-md-6">
                         <label>Select  Operator</label>
                         <select class="form-control select2 {{ $errors->has('operator_id') ? ' is-invalid' : '' }}" name="operator_id"  placeholder="Select Operator">
                           <option value="">Select Operator</option>
                           @foreach($bus_operators as $operator)
                               <option value="{{$operator->id}}">{{$operator->name}} - {{$operator->code}}</option>
                           @endforeach
                         </select>
                         @if ($errors->has('operator_id'))
                             <span class="invalid-feedback">
                                 <strong>{{ $errors->first('operator_id') }}</strong>
                             </span>
                         @endif
                    </div>
                    <div class="form-group col-md-6 ">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="text"  name="name" value="{{old('name')}}" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Name" >
                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-3 ">
                        <label for="exampleInputEmail1">NIN</label>
                        <input type="text"  name="nin" value="{{old('nin')}}" class="form-control {{ $errors->has('nin') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter NIN" >
                        @if ($errors->has('nin'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('nin') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-3 ">
                        <label for="exampleInputEmail1">Date Of Birth *</label>
                        <input type="date" name="date_of_birth" value="{{old('date_of_birth')}}"  class="form-control {{ $errors->has('date_of_birth') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Date Of Birth">
                        @if ($errors->has('date_of_birth'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('date_of_birth') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-3 ">
                        <label for="exampleInputEmail1">Driving Permit No *</label>
                        <input type="text" name="driving_permit_no" value="{{old('driving_permit_no')}}"  class="form-control {{ $errors->has('driving_permit_no') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Driving Permit No">
                        @if ($errors->has('driving_permit_no'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('driving_permit_no') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-3 ">
                        <label for="exampleInputEmail1">Phone Number *</label>
                        <input type="text" name="phone_number" value="{{old('phone_number')}}"  class="form-control {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Phone Number">
                        @if ($errors->has('phone_number'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('phone_number') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-6 ">
                        <label for="exampleInputEmail1">Address *</label>
                        <textarea class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" rows="3" placeholder="Enter Description" name="address" >{{old('address')}}</textarea>
                        @if ($errors->has('address'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('address') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                       <label for="exampleInputFile">Picture</label><br>
                       <input type="file" id="exampleInputFile" name="picture">
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
