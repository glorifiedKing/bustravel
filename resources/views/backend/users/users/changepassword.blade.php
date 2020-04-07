@extends('bustravel::backend.layouts.app')

@section('title', 'Change Password')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"> Change Password </h1>
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
            <h5 class="card-title"> {{auth()->user()->name}}</h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.users.changepassword.save')}}" method="POST" >
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
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
