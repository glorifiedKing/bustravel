@extends('bustravel::backend.layouts.app')

@section('title', 'Roles')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small><a href="{{route('bustravel.users.roles')}}" class="btn btn-info">Back</a></small> Roles </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Roles</li>
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
            <h5 class="card-title">Edit {{$role->name}}  Role</h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.users.roles.update',$role->id)}}" method="POST" enctype="multipart/form-data">
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <div class="form-group col-md-6  ">
                        <label for="exampleInputEmail1">Role Name</label>
                        <input type="text"  name="name" value="{{$role->name}}" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Name"  required>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    @php
                      $current_permissions = $role->permissions()->pluck('id')->all();
                    @endphp
                    <div class="form-group col-md-12">
                         <label>Select Permissions</label>
                         <select class="form-control select2 {{ $errors->has('permissions') ? ' is-invalid' : '' }}" name="permissions[]"  placeholder="Select Permissions" multiple>
                           <option value="">Select Permissions</option>
                           @foreach($permissions as $permission)
                               <option value="{{$permission->id}}" @if(in_array($permission->id, $current_permissions)) selected @endif>{{$permission->name}}</option>
                           @endforeach
                         </select>
                         @if ($errors->has('permissions'))
                             <span class="invalid-feedback">
                                 <strong>{{ $errors->first('permissions') }}</strong>
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
