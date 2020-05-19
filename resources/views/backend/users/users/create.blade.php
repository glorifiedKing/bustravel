@extends('bustravel::backend.layouts.app')

@section('title', 'Users')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small><a href="{{route('bustravel.users')}}" class="btn btn-info">Back</a></small> Users </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">users</li>
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
            <h5 class="card-title">Add User</h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.users.store')}}" method="POST" >
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <div class="form-group col-md-6  ">
                        <label for="exampleInputEmail1"> Name</label>
                        <input type="text"  name="name" value="{{old('name')}}" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Name"  >
                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                         <label>Select Role</label>
                         <select class="form-control select2 {{ $errors->has('role') ? ' is-invalid' : '' }}" name="role"  placeholder="Select Role" >
                           <option value="">Select Role</option>
                           @foreach($roles as $role)
                               <option value="{{$role->id}}">{{$role->name}}</option>
                           @endforeach
                         </select>
                         @if ($errors->has('role'))
                             <span class="invalid-feedback">
                                 <strong>{{ $errors->first('role') }}</strong>
                             </span>
                         @endif
                    </div>
                    <div class="form-group col-md-3 ">
                        <label for="exampleInputEmail1">E-Mail Address</label>
                        <input type="text"  name="email" value="{{old('email')}}" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Email"  >
                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-3  ">
                        <label for="exampleInputEmail1">Phone Number</label>
                        <input type="text"  name="phone_number" value="{{old('phone_number')}}" class="form-control {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Phone Number"  >
                        @if ($errors->has('phone_number'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('phone_number') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-3">
                         <label>Select Operator</label>
                         <select class="form-control select2 {{ $errors->has('operator_id') ? ' is-invalid' : '' }}" name="operator_id"  placeholder="Select Operator" >
                           <option value="">Select Operator</option>
                           @foreach($operators as $operator)
                               <option value="{{$operator->id}}">{{$operator->name}} ( {{$operator->code}} )</option>
                           @endforeach
                         </select>
                         @if ($errors->has('operator_id'))
                             <span class="invalid-feedback">
                                 <strong>{{ $errors->first('operator_id') }}</strong>
                             </span>
                         @endif
                    </div>
                    <div class="form-group col-md-3">
                        <label>Workstation[for cashiers]</label>
                        <select class="form-control select2 {{ $errors->has('workstation') ? ' is-invalid' : '' }}" name="workstation"  placeholder="Select Operator" >
                          <option value="">Select Station</option>
                          @foreach($stations as $station)
                              <option value="{{$station->id}}">{{$station->name}} ( {{$station->code}} )</option>
                          @endforeach
                        </select>
                        @if ($errors->has('workstation'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('workstation') }}</strong>
                            </span>
                        @endif
                   </div>
                    <div class="form-group col-md-12  ">
                    </div>
                    <div class="form-group col-md-3  ">
                        <label for="exampleInputEmail1">Password</label>
                        <input type="password"  name="password" value="{{old('password')}}" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Password"  >
                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-3  ">
                        <label for="exampleInputEmail1">Confirm Password</label>
                        <input type="password"  name="password_confirmation" value="{{old('password_confirmation')}}" class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Confirm Password"  >
                        @if ($errors->has('password_confirmation'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-12  ">
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
          $('.select2').select2();
        })
    </script>
@stop
