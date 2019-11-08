@extends('adminlte::page')

@section('title', 'Bus Stations| Create')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Bus Stations</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="{{route('bustravel.stations')}}">bus stations</a></li>
          <li class="breadcrumb-item active">create/update</li>
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
            <h5 class="card-title">Bus Station Form</h5>

            
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
               <div class="col-md-12">
                    <form method="post">
                            @csrf
                            
                            <input name="station_id" type="hidden" value="{{$station->id ?? null}}">
                        <div class="form-group">                            
                            <label for="name">Station Name:</label>
                            <input type="text" class="form-control{{ $errors->has('station_name') ? ' is-invalid' : '' }}" name="station_name" value="{{ old('station_name') ?? $station->name ?? null}}"/>
                            @if ($errors->has('station_name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('station_name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="code">Station Code :</label>
                            <input type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" value ="{{ old('code') ?? $station->code ?? null}}"/>
                            @if ($errors->has('code'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('code') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="street">Street address :</label>
                            <input type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ old('address') ?? $station->address ?? null }}"/>
                            @if ($errors->has('address'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="province">Province/State :</label>
                            <input type="text" class="form-control{{ $errors->has('province') ? ' is-invalid' : '' }}" name="province" value="{{ old('province') ?? $station->province ?? null }}"/>
                            @if ($errors->has('province'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('province') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="city">City :</label>
                            <input type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ old('city') ?? $station->city ?? null }}"/>
                            @if ($errors->has('city'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('city') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="latitude">Latitude :</label>
                            <input type="text" class="form-control{{ $errors->has('latitude') ? ' is-invalid' : '' }}" name="latitude" value="{{old('latitude') ?? $station->latitude ?? null}}"/>
                            @if ($errors->has('latitude'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('latitude') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="longitude">Longitude :</label>
                            <input type="text" class="form-control{{ $errors->has('longitude') ? ' is-invalid' : '' }}" name="longitude" value="{{ old('longitude') ?? $station->longitude ?? null}}"/>
                            @if ($errors->has('longitude'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('longitude') }}</strong>
                                </span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
               </div>
            </div>
            <!-- /.row -->
            </div>
            <!-- ./card-body -->
            <div class="card-footer">
            
            <!-- /.row -->
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
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop