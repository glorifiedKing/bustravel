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
            <h5 class="card-title">Edit {{$bus_operator->name}}  Operator</h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.operators.update',$bus_operator->id)}}" method="POST" enctype="multipart/form-data">
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <div class="form-group col-md-12 ">
                        <label for="exampleInputEmail1">Operator Name</label>
                        <input type="text"  name="name" value="{{$bus_operator->name}}" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Name" >
                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-4 ">
                        <label for="exampleInputEmail1">Operator Code</label>
                        <input type="text" name="code" value="{{$bus_operator->code}}"  class="form-control" id="exampleInputEmail1" placeholder="Enter Code">
                    </div>
                    <div class="form-group col-md-4 ">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" name="email"  value="{{$bus_operator->email}}" class="form-control" id="exampleInputEmail1" placeholder="Enter Email">
                    </div>

                    <div class="form-group col-md-6 ">
                        <label for="exampleInputEmail1">Contact Person</label>
                        <input type="text" name="contact_person_name" value="{{$bus_operator->contact_person_name}}"  class="form-control {{ $errors->has('contact_person_name') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Contact Person" required>
                        @if ($errors->has('contact_person_name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('contact_person_name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-6 ">
                        <label for="exampleInputEmail1">Phone Number</label>
                        <input type="text"  name ="phone_number" value="{{$bus_operator->phone_number}}" class="form-control {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Phone Number" required>
                        @if ($errors->has('phone_number'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('phone_number') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-6 ">
                        <label for="exampleInputEmail1">Address</label>
                        <textarea class="form-control {{ $errors->has('address') ? ' has-error' : '' }} " rows="3" placeholder="Enter Address" name="address"  value="{{$bus_operator->address}}">{{$bus_operator->address}}</textarea>
                        @if ($errors->has('address'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('address') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group col-md-6">
                       <label for="exampleInputFile">Logo</label><br>
                       <input type="file" id="exampleInputFile" name="newlogo">
                       <input type="hidden" name="logo" value={{$bus_operator->logo}}>
                       @if($bus_operator->logo)
                             <img src="{{url('/logos/'.$bus_operator->logo) }}" width="70px" alt="{{$bus_operator->name}}"/>
                       @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputFile">Logo for Printer</label><br>
                        <input type="file"  name="new_logo_printer">
                        <input type="hidden" name="logo_printer" value={{$bus_operator->logo_printer}}>
                        @if($bus_operator->logo_printer)
                              <img src="{{url('/logos/'.$bus_operator->logo_printer) }}" width="70px" alt="{{$bus_operator->name}}"/>
                        @endif
                     </div>
                    <div class=" col-md-3 form-group">
                        <label for="signed" class=" col-md-12 control-label">Status</label>
                        <label class="radio-inline">
                          <input type="radio" id="Active" name="status" value="1" @php echo $bus_operator->status == 1 ? 'checked' :  "" @endphp> Active</label>
                        </label>
                       <label class="radio-inline">
                          <input type="radio" id="Deactive" name="status" value="0" @php echo  $bus_operator->status == 0 ? 'checked' : '' @endphp> Deactive</label>
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
