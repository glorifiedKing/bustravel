@extends('bustravel::backend.layouts.app')

@section('title', 'Bookings')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Bookings - {{date('d-m-Y')}}</h1>
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
              <div class="col-md-6">
                  <form role="form"  method="POST" >
                      {{csrf_field() }}
                      <div class="box-body">
                          <div class="row">
                            <div class="form-group col-md-9 ">
                                <label for="exampleInputEmail1">Ticket</label>
                                <input type="text"  name="ticket" value="{{$ticket}}" class="form-control {{ $errors->has('ticket') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Ticket" >
                                @if ($errors->has('ticket'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('ticket') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-md-12">
                              <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </div>
                  </form>
              </div>
              <div class="col-md-3">
               <canvas id="pieChart1" style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
              </div>
            <div class="col-md-3">
                <canvas id="pieChart" style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
            <div class="card-header">
            <h5 class="card-title">All Bookings</h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
               <div class="col-md-12">
                 <table id="example1" class="table table-bordered table-hover table-striped dataTable" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>On Board</th>
                                <th>Ticket</th>
                                <th>Paid Date</th>
                                <th>Travel Date </th>
                                <th>Created </th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach ($bookings as $booking)
                            <tr>
                              <td>@if($booking->status==1)
                                    <span class="badge badge-success "> <i class="fas fa-check"></i> </span>
                                  @else
                                    <span class="badge badge-danger "> <i class="fas fa-check"></i> </span>

                                  @endif
                               </td>
                               <td>@if($booking->boarded==1)
                                     <span class="badge badge-success "> <i class="fas fa-check"></i> Yes</span>
                                   @else
                                   <a href="{{route('bustravel.bookings.boarded',$booking->id)}}" onclick="return confirm('Are you sure  Ticket [{{$booking->ticket_number}}] is On Board')" ><span class="badge badge-danger "> <i class="fas fa-times"></i> No</span></a>

                                   @endif
                                </td>
                               <td>{{$booking->ticket_number}}</td>
                                <td>{{$booking->date_paid}}</td>
                                <td>{{$booking->date_of_travel}}</td>
                                <td>{{Carbon\Carbon::parse($booking->created_at)->format('d-m-Y')}}</td>
                            </tr>

                        @endforeach
                    </tbody>
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

})
</script>

@stop
