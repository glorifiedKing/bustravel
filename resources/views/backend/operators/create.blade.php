@extends('bustravel::backend.layouts.app')

@section('title', 'Bus Operators')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small><a href="{{route('bustravel.operators')}}" class="btn btn-info">Back</a></small> Bus Operators </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">bus Operators</li>
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
            <h5 class="card-title">Add Bus Operators</h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.operators.store')}}" method="POST" enctype="multipart/form-data">
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <div class="form-group col-md-12  ">
                        <label for="exampleInputEmail1">Operator Name</label>
                        <input type="text"  name="name" value="{{old('name')}}" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Name" >
                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-4 ">
                        <label for="exampleInputEmail1">Operator Code</label>
                        <input type="text" name="code" value="{{old('code')}}"  class="form-control" id="exampleInputEmail1" placeholder="Enter Code">
                    </div>
                    <div class="form-group col-md-4 ">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" name="email"  value="{{old('email')}}" class="form-control" id="exampleInputEmail1" placeholder="Enter Email">
                    </div>

                    <div class="form-group col-md-6 ">
                        <label for="exampleInputEmail1">Contact Person</label>
                        <input type="text" name="contact_person_name" value="{{old('contact_person_name')}}"  class="form-control {{ $errors->has('contact_person_name') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Contact Person" >
                        @if ($errors->has('contact_person_name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('contact_person_name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-6 ">
                        <label for="exampleInputEmail1">Phone Number</label>
                        <input type="text"  name ="phone_number" value="{{old('phone_number')}}" class="form-control {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Phone Number" >
                        @if ($errors->has('phone_number'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('phone_number') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-6 ">
                        <label for="exampleInputEmail1">Address</label>
                        <textarea class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" rows="3" placeholder="Enter Address" name="address" >{{old('address')}}</textarea>
                        @if ($errors->has('address'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('address') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group col-md-6">
                       <label for="exampleInputFile">Logo</label><br>
                       <input type="file" id="exampleInputFile" name="logo">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputFile">Logo for Printer</label><br>
                        <input type="file" name="logo_printer">
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
@stop
