@extends('bustravel::backend.layouts.app')

@section('title', 'Ticket Scanners')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small><a href="{{route('bustravel.ticket_scanners')}}" class="btn btn-info">Back</a></small> Ticket Scanners </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Ticket scanners</li>
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
            <h5 class="card-title">Edit Ticket Scanner</h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.ticket_scanners.update',$ticket_scanner->id)}}" method="POST" enctype="multipart/form-data">
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">                    
                    <div class="form-group col-md-4 ">
                        <label for="exampleInputEmail1">Operator</label>
                        <select  name="operator_id" class="form-control {{ $errors->has('operator_id') ? 'is-invalid' : '' }}" id="inputEmail4">
                            @foreach ($operators as $operator)
                              <option value="{{$operator->id}}">{{$operator->name}}</option>
                            @endforeach
                        </select>    
                        @error('operator_id')
                                <small class="form-text invalid-feedback" >
                                    {{ $message }}
                                </small>
                            @enderror
                        
                    </div>
                    <div class="form-group col-md-4">
                        <label for="exampleInputEmail1">Device id</label>
                        <input type="text"  name="device_id" value="{{$ticket_scanner->device_id}}" class="form-control {{ $errors->has('device_id') ? ' is-invalid' : '' }}" id="exampleInputEmail1"  >
                        @error('device_id')
                        <small class="form-text invalid-feedback" >
                            {{ $message }}
                        </small>
                    @enderror
                    </div>
                    <div class="form-group col-md-4 ">
                        <label for="exampleInputEmail1">Device Make</label>
                        <input type="text" name="device_make"  value="{{$ticket_scanner->description['device_make'] ?? ''}}" class="form-control" id="exampleInputEmail1" >
                    </div>

                    <div class="form-group col-md-4 ">
                        <label for="exampleInputEmail1">Device Model</label>
                        <input type="text" name="device_model" value="{{$ticket_scanner->description['device_model'] ?? ''}}"  class="form-control {{ $errors->has('device_model') ? ' is-invalid' : '' }}" id="exampleInputEmail1" >
                        @error('device_model')
                        <small class="form-text invalid-feedback" >
                            {{ $message }}
                        </small>
                    @enderror
                    </div>
                    <div class="form-group col-md-4 ">
                        <label for="exampleInputEmail1">Device Location</label>
                        <input type="text"  name ="device_location" value="{{$ticket_scanner->description['device_location'] ?? ''}}" class="form-control {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="BUS RC 1452" >
                        @error('device_location')
                        <small class="form-text invalid-feedback" >
                            {{ $message }}
                        </small>
                    @enderror
                    </div>
                    <div class=" col-md-3 form-group">
                        <label for="signed" class=" col-md-12 control-label">Status</label>
                        <label class="radio-inline">
                          <input type="radio" id="Active" name="active" value="1" {{($ticket_scanner->active == 1) ? 'checked' : ''}}> Active</label>
                        </label>
                       <label class="radio-inline">
                          <input type="radio" id="Deactive" name="active" value="0" {{($ticket_scanner->active == 0) ? 'checked' : ''}}> Disabled</label>
                       </label>

                    </div>
                    <div class="form-group col-md-6 ">
                        <label for="exampleInputEmail1">Notes</label>
                        <textarea class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" rows="3" placeholder="Other Details" name="notes" >{{$ticket_scanner->description['notes'] ?? ''}}</textarea>
                        @error('notes')
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
