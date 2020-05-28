@extends('bustravel::backend.layouts.app')

@section('title', 'Bookings')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small><a href="{{route('bustravel.bookings')}}" class="btn btn-info">Back</a></small> Bookings </h1>
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
        <div class="card">
            <div class="card-header">
            <h5 class="card-title">Add Booking on {{\Carbon\Carbon::now()->toDateString()}} </h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.bookings.store')}}" method="POST" >
              {{csrf_field() }}

              <div class="box-body">
                <div class="form-row">
                  <div class="form-group col-xs-6 ">
                      <label for="inputEmail4">From</label>
                      <input id="route_id" type="number" hidden name="route_id" >
                      <input id="route_type" type="text" hidden name="route_type" >
                      <select id="from_station_id"  name="from_station_id" class="form-control {{ $errors->has('printer_name') ? 'is-invalid' : '' }}" >
                        <option value="{{$workstation->id??0}}">{{$workstation->name??''}}</option>
                      </select>
                       @error('printer_name')
                          <small class="form-text invalid-feedback" >
                              {{ $message }}
                          </small>
                      @enderror
                  </div>
                  <div class="form-group col-xs-6">
                      <label for="inputPassword4">To </label>
                      <select id="to_station_id"  name="to_station_id" class="form-control {{ $errors->has('printer_url') ? 'is-invalid' : '' }}" >
                       <option value="0">To Station</option>
                       @foreach ($stations as $station)
                           <option value="{{$station->id}}">{{$station->name}}</option>
                       @endforeach
                      </select>
                       @error('printer_url')
                          <small class="form-text invalid-feedback" >
                              {{ $message }}
                          </small>
                      @enderror
                  </div>
                </div>
                  <div class="form-group col-xs-12">
                    <table id="table_results" class="table table-striped">
                        <caption>Available Bus services</caption>
                        <thead>
                          <tr>
                            <th scope="col">Time</th>
                            <th scope="col">seats left</th>
                            <th scope="col">amount</th>
                            <th scope="col">book</th>
                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                  </div>
                  <hr>
                  <div class="form-group col-xs-12 ">
                    <label>Amount</label>
                    <input readonly class="form-control" name="amount" id="amount">
                  </div>
                  <div class="form-row">
                  @foreach($custom_fields as $fields)
                       <div class="form-group col-xs-6 ">
                         <label>{{$fields->field_name}}</label>
                         <input type=hidden name="field_id[]" value="{{$fields->id}}">
                         <input type="text" size="6"  name="field_value[]" class="form-control {{ $errors->has('date_paid') ? ' is-invalid' : '' }}" id="exampleInputEmail1"  {{ ($fields->is_required == 1 ) ? 'required' :  "" }} >
                       </div>
                    @endforeach
                  </div>
                  <hr>
                  <div class="form-row">
                    <div class="form-group col-xs-6">
                      <label>Pay by</label>
                      <select name="payment_method" class="form-control">
                        <option value="cash">CASH</option>
                        <option value="palm_kash">Palm</option>
                      </select>
                    </div>
                    <div class="form-group col-xs-6">
                      <label>Printer</label>
                      <select name="printer" class="form-control">
                        @foreach ($printers as $printer)
                          <option value="{{$printer->id}}" {{($printer->is_default)? 'selected' : ''}}>{{$printer->printer_name}}</option>
                        @endforeach
                      </select>
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
         var operators_url = "{{route('bustravel.api.get.route.times',auth()->user()->operator_id)}}"
         $('#to_station_id').change(function(){
           var to_station = $(this).val();
           var from_station = $('#from_station_id').val();
           var table_body = "";
           $('#amount').val('');
           $('#route_id').val('');
          $('#route_type').val('');

            $.post(operators_url,
            {
              _token: "{{ csrf_token() }}",
              to_station_id: to_station,
              from_station_id: from_station
            },
            function(data, status){
              if(status == 'success')
              {
                var table_row = "";
                data.forEach(function(item) {

                   table_row = "<tr data-route='"+item['id']+"' data-amount='"+item['price']+"' data-type='"+item['route_type']+"'> <td>"+item['time']+"</td><td>"+item['seats_left']+"</td><td>"+item['price']+"</td><td><button type='button' class='btn btn-xs btn-success bus_service_select'>select</button></td></tr>";
                    table_body = table_body + table_row;
                  });

                  $("#table_results tbody").html(table_body);
              }

            });
         });
         $('#table_results').on('click','tbody tr',function(){

          var route_id = $(this).data('route');
          var route_type = $(this).data('type');
          var route_price = $(this).data('amount');
          $('#route_id').val(route_id);
          $('#route_type').val(route_type);
          $('#amount').val(route_price);


         });
         $('.select2').select2();
       })
</script>
@stop
