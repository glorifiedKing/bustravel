@extends('bustravel::backend.layouts.app')

@section('title', 'Sms Templates')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small><a href="{{route('bustravel.email.templates')}}" class="btn btn-info">Back</a></small> Sms Templates </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">sms templates</li>
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
            <h5 class="card-title">Add sms Template</h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="callout callout-info">
                            <h5>variables you can use in the sms [limit to 100 characters]</h5>
          
                            <p>{FIRST_NAME}{TICKET_NO}
                            {DEPARTURE_STATION}
                            {ARRIVAL_STATION}
                            {DEPARTURE_TIME} {DEPARTURE_DATE}
                            {ARRIVAL_TIME} {ARRIVAL_DATE}{AMOUNT}
                            {DATE_PAID}
                            {PAYMENT_METHOD}</p>
                          </div>
                          <br>
                    </div>
                </div>
            <div class="row">
              <div class="col-md-12">
              <form role="form"  method="POST" enctype="multipart/form-data">
                @csrf

              <div class="box-body">                
                    
                    <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Purpose</label>
                        <input type="number" hidden name="operator_id" value="{{$operator_id}}">
                        <input type="number" hidden name="base_operator" value="0">
                        <select name="purpose" value="{{old('purpose')}}" class="form-control {{ $errors->has('purpose') ? 'is-invalid' : '' }}" >
                            <option value="TICKET">TICKET</option>
                        </select>
                         @error('purpose')
                            <small class="form-text invalid-feedback" >
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputPassword4">Language</label>
                        <select name="language" value="{{old('language')}}" class="form-control {{ $errors->has('language') ? 'is-invalid' : '' }}" >
                            <option value="english">English</option>
                            <option value="french">French</option>
                            <option value="kinyarwanda">Kinyarwanda</option>
                        </select>
                         @error('language')
                            <small class="form-text invalid-feedback" >
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputPassword4">is default? </label>
                        <select name="is_default" value="{{old('is_default')}}" class="form-control {{ $errors->has('is_default') ? 'is-invalid' : '' }}" >
                            <option value="0">No</option>
                        </select>
                        @error('is_default')
                            <small class="form-text invalid-feedback" >
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    </div>                    

                    <div class="form-row">
                    
                    <div class="form-group col-md-8">
                        <label for="inputState">Sms Message (max 100 chars)</label>
                    <textarea name="message" class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}">{{old('message')}}</textarea>
                        @error('message')
                            <small class="form-text invalid-feedback" >
                                {{ $message }}
                            </small>
                        @enderror
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
