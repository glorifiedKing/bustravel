@extends('bustravel::backend.layouts.app')

@section('title', 'Bookings')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Bookings</h1>
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
            <h5 class="card-title">All Bookings</h5>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus" aria-hidden="true"></i>
                </button>
                <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <a href="{{route('bustravel.bookings.create')}}" class="dropdown-item">New Booking</a>
                    <a href="#" class="dropdown-item">delete selected</a>
                </div>
                </div>

            </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <form action="{{route('bustravel.bookings.search')}}" method="post" >
                    {{ csrf_field() }}
                    <div class="row">
                    <div class="form-group col-md-3">
                      <label>Ticket No</label>
                      <input type="text"  name="b_ticket" value="{{$b_ticket??""}}" class="form-control " id="exampleInputEmail1" placeholder="Ticket No" >
                    </div>
                    <div class="form-group col-md-3">
                      <label>From</label>
                      <input type="date"  name="b_from" value="{{$b_from??""}}" class="form-control " id="exampleInputEmail1" >
                    </div>
                    <div class="form-group col-md-3">
                      <label>To</label>
                      <input type="date"  name="b_to" value="{{$b_to??""}}" class="form-control " id="exampleInputEmail1"  >
                    </div>
                    <div class="form-group col-md-3">
                    <label>Operator</label>
                   @if(auth()->user()->hasAnyRole('BT Super Admin'))
                   <select  name="operator_id" class="form-control select2"  onchange="this.form.submit()">
                   <option ="0"> Select Operator</option>
                   @foreach ($operators as $operator)
                   <option value="{{$operator->id}}" @php echo $operator->id == $Selected_OperatorId ? 'selected' :  "" @endphp>{{$operator->name}}</option>
                   @endforeach
                   </select>
                   @else
                   <select  name="operator_id" class="form-control select2"  onchange="this.form.submit()">
                   <option value="{{$Selected_OperatorId}}"> {{$operator_Name}}</option>
                   </select>

                   @endif
                 </div>
                    <div class="form-group col-md-6">
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
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach ($bookings as $booking)
                            <tr>
                              <td>@if($booking['status']==1)
                                    <a href="#" class="btn btn-xs btn-success"> <i class="fas fa-check" aria-hidden="true"></i></a>
                                  @else
                                  <a href="#" class="btn btn-xs btn-danger"> <i class="fas fa-times" aria-hidden="true"></i></a>

                                  @endif
                               </td>
                               <td>{{$booking['ticket_number']}}</td>
                                <td>{{$booking['operator']}}</td>
                                <td>{{$booking['start']}} - {{$booking['end']}} / {{$booking['time']}}</td>
                               <td>{{number_format($booking['amount'],2)}} </td>
                                <td>{{$booking['date_paid']}}</td>
                                <td>{{$booking['date_of_travel']}}</td>
                                <td>{{Carbon\Carbon::parse($booking['created_at'])->format('Y-m-d')}}</td>
                                <td><a title="Edit" href="{{route('bustravel.bookings.edit',$booking['id'])}}"><i class="fas fa-edit" aria-hidden="true"></i></a>
                                    <a title="Delete" onclick="return confirm('Are you sure you want to delete this  Booking {{$booking['ticket_number']}}')" href="{{route('bustravel.bookings.delete',$booking['id'])}}"><span style="color:tomato"><i class="fas fa-trash-alt" aria-hidden="true"></i></span></a>
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                    </table>
               </div>
            </div>
            <!-- /.row -->
            </div>
            <!-- ./card-body -->
            <div class="card-footer">
            <div class="row">
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <h5 class="description-header">{{number_format($total_bookings,0)}}</h5>
                    <span class="description-text">TOTAL NUMBER OF BOOKINGS</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <h5 class="description-header">{{number_format($total_bookings_amount,0)}}</h5>
                    <span class="description-text">TOTAL  BOOKINGS AMOUNT</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <h5 class="description-header">{{number_format($total_number_of_routes,0)}}</h5>
                    <span class="description-text">TOTAL NUMBER OF ROUTES</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block">
                    <h5 class="description-header">{{number_format($total_number_of_services,0)}}</h5>
                    <span class="description-text">TOTAL NUMBER OF SERVICES</span>
                </div>
                <!-- /.description-block -->
                </div>
            </div>
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

@stop

@section('js')
    @parent
    <script>
        $(function () {
var table = $('#example1').DataTable({
      responsive: false,
      dom: 'Blfrtip',
      buttons: [
        {
          extend: 'excelHtml5',
          exportOptions: {
            columns: ':visible'
          }
        },
        {
          extend: 'pdfHtml5',
          exportOptions: {
            columns: ':visible'
          }
        },
      'colvis',
        //'selectAll',
          //	'selectNone'
      ],
            });
  $('div.alert').not('.alert-danger').delay(5000).fadeOut(350);
  $('.select2').select2();
})
</script>

@stop
