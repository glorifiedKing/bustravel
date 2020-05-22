@extends('bustravel::backend.layouts.app')

@section('title', 'Printers')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small><a href="{{route('bustravel.printers.list')}}" class="btn btn-info">Back</a></small> Printers </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">printers</li>
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
            <h5 class="card-title">Edit Printer</h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">               
            <div class="row">
              <div class="col-md-12">
              <form role="form"  method="POST" enctype="multipart/form-data">
                @csrf

              <div class="box-body">                
                    
                    <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Printer Name</label>
                        <input type="number" hidden name="operator_id" value="{{$printer->operator_id}}">
                        <input type="number" hidden name="base_operator" value="0">
                        <input name="printer_name" value="{{$printer->printer_name ?? old('printer_name') ?? 'palmkash_printer'}}" class="form-control {{ $errors->has('printer_name') ? 'is-invalid' : '' }}" >
                        
                         @error('printer_name')
                            <small class="form-text invalid-feedback" >
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputPassword4">Printer Url or IP</label>
                        <input name="printer_url" value="{{$printer->printer_url ?? old('printer_url') ?? 'rawbt:base64'}}" class="form-control {{ $errors->has('printer_url') ? 'is-invalid' : '' }}" >
                         
                         @error('printer_url')
                            <small class="form-text invalid-feedback" >
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputPassword4">Printer Port</label>
                        <input name="printer_port" value="{{$printer->printer_port ?? old('printer_port') ?? '9100'}}" class="form-control {{ $errors->has('printer_port') ? 'is-invalid' : '' }}" >
                         
                         @error('printer_port')
                            <small class="form-text invalid-feedback" >
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="inputPassword4">is default? </label>
                        <select name="is_default" value="{{old('is_default')}}" class="form-control {{ $errors->has('is_default') ? 'is-invalid' : '' }}" >
                            <option value="0" @if($printer->is_default == '0') selected @endif>No</option>
                            <option value="1" @if($printer->is_default == '1') selected @endif>Yes</option>
                        </select>
                        @error('is_default')
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
