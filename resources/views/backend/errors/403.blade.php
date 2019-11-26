@extends('bustravel::backend.layouts.app')

@section('title', 'Error')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Access Denied</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('bustravel.homepage')}}">Home</a></li>
          <li class="breadcrumb-item active">403 error</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
        <div class="card bg-danger">
            <div class="card-header">
            <h5 class="card-title">Access Denied</h5>

            
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
               <div class="col-md-12">
                    <p>You do not have permission to access the requested resource</p>
                    <p><a class="btn" href="{{route('bustravel.homepage')}}">Go Home</a></p>
                </div>
            </div>
            <!-- /.row -->
            </div>
            <!-- ./card-body -->
            <div class="card-footer">
            
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
 
@stop