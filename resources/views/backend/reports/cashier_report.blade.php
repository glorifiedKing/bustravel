@extends('bustravel::backend.layouts.app')

@section('title', 'Cashier Report')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Cashier Report</h1>
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
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <form action="{{route('bustravel.bookings.cashier.report.search')}}" method="post" >
                  {{ csrf_field() }}
                  <div class="row">
                  <div class="form-group col-md-3">
                    <label>Ticket No</label>
                    <input type="text"  name="ticket" value="{{$ticket??""}}" class="form-control " id="exampleInputEmail1" placeholder="Ticket No" >
                  </div>
                  <div class="form-group col-md-3 ">
                    <label>Start Station</label>
                    <select class="form-control select2 {{ $errors->has('start_station') ? ' is-invalid' : '' }}" name="start_station"  placeholder="Select Station">
                      <option value="">Select Station</option>
                      @foreach($stations as $station)
                          <option value="{{$station->id}}" @php echo $start_station == $station->id ? 'selected' :  "" @endphp>{{$station->name}} - {{$station->code}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label>From</label>
                    <input type="date"  name="from" value="{{$from??""}}" class="form-control " id="exampleInputEmail1" >
                  </div>
                  <div class="form-group col-md-3">
                    <label>To</label>
                    <input type="date"  name="fto" value="{{$to??""}}" class="form-control " id="exampleInputEmail1"  >
                  </div>
                  <div class="form-group col-md-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                  </div>
                </div>
                </form>
                </div>
            </div>
            <div class="row">


               <div class="col-md-12">
                 <table id="example1" class="table table-bordered table-hover table-striped dataTable" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr>
                                <th scope="col">Status</th>
                                <th scope="col">Ticket</th>
                                <th scope="col">Operator</th>
                                <th scope="col">Route</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Paid Date</th>
                                <th scope="col">Travel Date </th>
                                <th scope="col">Created </th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach ($bookings as $booking)
                            <tr>
                              <td>@if($booking->status==1)
                                    <a href="#" class="btn btn-xs btn-success"> <i class="fas fa-check" aria-hidden="true"></i></a>
                                  @else
                                  <a href="#" class="btn btn-xs btn-danger"> <i class="fas fa-times" aria-hidden="true"></i></a>

                                  @endif
                               </td>
                               <td>{{$booking->ticket_number}}</td>
                                <td>{{$booking->route_departure_time->route->operator->name??'None'}}</td>
                                <td>{{$booking->route_departure_time->route->start->code??'None'}} - {{$booking->route_departure_time->route->end->code??'None'}} / {{$booking->route_departure_time->departure_time??'None'}}</td>
                                <td>{{number_format($booking->amount,2)}} </td>
                                <td>{{Carbon\Carbon::parse($booking->date_paid)->format('d-m-Y')}}</td>
                                <td>{{Carbon\Carbon::parse($booking->date_of_travel)->format('d-m-Y')}}</td>
                                <td>{{Carbon\Carbon::parse($booking->created_at)->format('Y-m-d')}}</td>
                            </tr>

                        @endforeach
                    </tbody>
                    <tfoot>
                          <tr>
                           <th colspan="6">Total Amount</th>
                           <th  colspan="2" id="total_order"></th>
                          </tr>
                    </tfoot>
                    </table>
               </div>
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
var table = $('#example1').DataTable({
      responsive: false,
      dom: 'Blfrtip',
      buttons: [
        {
          extend: 'excelHtml5',
          exportOptions: {
            columns: ':visible'
          },
          footer: true
        },
        {
          extend: 'pdfHtml5',
          exportOptions: {
            columns: ':visible'
          },
          footer: true
        },
      'colvis',
        //'selectAll',
          //	'selectNone'
      ],
      "footerCallback": function ( row, data, start, end, display ) {
              var api = this.api(), data;

              // Remove the formatting to get integer data for summation
              var intVal = function ( i ) {
                  return typeof i === 'string' ?
                      i.replace(/[\$,]/g, '')*1 :
                      typeof i === 'number' ?
                          i : 0;
              };

              // Total over all pages
              total = api
                  .column( 4 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );

              // Total over this page
              pageTotal = api
                  .column( 4, { page: 'current'} )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );

              // Update footer
              $('#total_order').html(
                  + pageTotal +' ( '+ total +' total)'
              );
          }
            });
  $('div.alert').not('.alert-danger').delay(5000).fadeOut(350);
})
</script>

@stop
