@extends('bustravel::backend.layouts.app')

@section('title', 'Bookings')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small><a href="{{route('bustravel.bookings')}}" class="btn btn-info">Back</a></small> Bookings</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">bookings</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
          <p>
          <span class="badge badge-warning ">   Updated {{ $diffs = Carbon\Carbon::parse($booking->updated_at)->diffForHumans() }} </span>   &nbsp
          <span class="badge badge-success ">   Created {{ $diffs = Carbon\Carbon::parse($booking->created_at)->diffForHumans() }} </span>    &nbsp
          </p>
        <div class="card">
            <div class="card-header">
            <h5 class="card-title">Edit {{$booking->ticket_number}}  Ticket Number</h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.bookings.update',$booking->id)}}" method="POST">
              {{csrf_field() }}

              <div class="box-body">
                <div class="row">
                  <div class="form-group col-md-3 ">
                    <label>From</label>
                    <input  readonly type="text"  name="from" value="{{$booking->route_departure_time->route->start->name}} " class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}" id="exampleInputEmail1">
                  </div>
                  <div class="form-group col-md-3 ">
                    <label>To</label>
                    <input readonly type="text"  name="amount" value="{{$booking->route_departure_time->route->end->name}}" class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}" id="exampleInputEmail1" >
                  </div>
                  <div class="form-group col-md-12">
                    <table id="table_results" class="table table-striped">
                        <caption> Bus service</caption>
                        <thead>
                          <tr>
                            <th scope="col">Time</th>
                            <th scope="col">Bus</th>
                            <th scope="col">Driver</th>
                            <th scope="col">Travelled</th>
                          </tr>
                          <tr>
                             <td>{{$booking->route_departure_time->departure_time}} - {{$booking->route_departure_time->arrival_time}}</td>
                             <td>{{$booking->route_departure_time->bus->number_plate??''}} </td>
                             <td>{{$booking->route_departure_time->driver->name??''}} </td>
                             <td>
                               @if($booking->boarded==0)
                               <span class="badge badge-danger "><i class="fa fa-times"></i> No</span>
                               @else
                               <span class="badge badge-success "><i class="fa fa-check"></i> Yes</span>
                               @endif
                             </td></tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                  </div>
                  <hr>
                  <div class="form-group col-md-12 ">
                    <label>Amount</label>
                    <input readonly class="form-control" name="amount" id="amount" value="{{$booking->amount}}">
                  </div>
                  <div class="form-group col-md-3 ">
                    <label>Date Paid</label>
                    <input type="date"  name="date_paid" value="{{$booking->date_paid}}" class="form-control {{ $errors->has('date_paid') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Date Paid" >
                    @if ($errors->has('date_paid'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('date_paid') }}</strong>
                        </span>
                    @endif
                  </div>
                  <div class="form-group col-md-3 ">
                    <label>Travel Date</label>
                    <input type="date"  name="date_of_travel" value="{{$booking->date_of_travel}}" class="form-control {{ $errors->has('date_of_travel') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Travel Date" >
                    @if ($errors->has('date_of_travel'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('date_of_travel') }}</strong>
                        </span>
                    @endif
                  </div>
                  <div class="form-group col-md-3">
                    <label>Pay by</label>
                    <select name="payment_method" class="form-control">
                      <option value="cash" @php echo $booking->payment_source == "cash" ? 'selected' :  "" @endphp>CASH</option>
                      <option value="palm_kash" @php echo $booking->payment_source == "palm_kash" ? 'selected' :  "" @endphp>Palm</option>
                    </select>
                  </div>
                  <div class=" col-md-12 form-group">
                    <hr>
                  </div>
                  @foreach($custom_fields as $fields)
                    @if(in_array($fields->id, $booking_fields_ids))
                    @foreach($booking_fields as $booking_field)
                    @if($fields->id==$booking_field->field_id)
                    <div class="form-group col-md-3 ">
                      <label>{{$fields->field_name}}</label>
                      <input type=hidden name="field_id[]" value="{{$booking_field->field_id}}">
                      <input type="text"  name="field_value[]" value="{{$booking_field->field_value}}" class="form-control {{ $errors->has('date_paid') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="{{$fields->field_name}}" @php echo $fields->is_required == 1? 'required' :  "" @endphp >
                    </div>
                    @endif
                    @endforeach

                    @else
                    <div class="form-group col-md-3 ">
                      <label>{{$fields->field_name}}</label>
                      <input type=hidden name="field_id[]" value="{{$fields->id}}">
                      <input type="text"  name="field_value[]" value="" class="form-control {{ $errors->has('date_paid') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="{{$fields->field_name}}" @php echo $fields->is_required == 1? 'required' :  "" @endphp >
                    </div>

                    @endif

                  @endforeach
                  <div class=" col-md-12 form-group">
                  </div>
                  <div class=" col-md-12 form-group">
                  </div>
                  <div class=" col-md-3 form-group">
                      <label for="signed" class=" col-md-12 control-label">Status</label>
                      <label class="radio-inline">
                        <input type="radio" id="Active" name="status" value="1" @php echo $booking->status == 1? 'checked' :  "" @endphp>  Paid 
                      </label>
                     <label class="radio-inline">
                        <input type="radio" id="Deactive" name="status" value="0" @php echo $booking->status == 0? 'checked' :  "" @endphp>  Not Paid
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
          $('div.alert').not('.alert-danger').delay(5000).fadeOut(350);
          $('.select2').select2();
        })
    </script>
@stop
