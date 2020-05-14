@extends('bustravel::backend.layouts.app')

@section('title', 'Email and sms template')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">email_sms templates</li>
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
            <h5 class="card-title">Edit Email and Sms Template</h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="callout callout-info">
                            <h5>variables to use</h5>
          
                            <p><{FIRST_NAME}{TICKET_NO}
                            {DEPARTURE_STATION}
                            {ARRIVAL_STATION}
                            {DEPARTURE_TIME} {DEPARTURE_DATE}
                            {ARRIVAL_TIME} {ARRIVAL_DATE}{AMOUNT}
                            {DATE_PAID}
                            {PAYMENT_METHOD}</p>
                          </div>
                    </div>
                </div>
            <div class="row">
              <div class="col-md-12">
              <form role="form"  method="POST" enctype="multipart/form-data">
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <div class="form-group col-md-12 ">
                        <input hidden name="email_template_id" value="{{$email_template->id ?? 0}}">
                        <input hidden name="sms_template_id" value="{{$sms_template->id ?? 0}}">
                        <input hidden name="operator_id" value="{{$operator_id}}">
                        <input hidden name="base_operator" value="0">
                        <label for="exampleInputEmail1">Email Template</label>
                        @trix($email_template, 'content', [ 'hideButtonIcons' => ['attach', 'bold'] ])
                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    
                    
                    <div class="form-group col-md-12 ">
                        <label for="exampleInputEmail1">Sms template</label>
                        <textarea class="form-control {{ $errors->has('sms_template') ? ' has-error' : '' }} " rows="3"  name="sms_template"  value="{{$bus_operator->address}}">{{$sms_template}}</textarea>
                        @if ($errors->has('sms_template'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('address') }}</strong>
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
@stop
