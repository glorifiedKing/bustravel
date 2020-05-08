@extends('bustravel::backend.layouts.app')

@section('title', 'Bookings')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Bookings - {{date('d-m-Y')}}
        @if($tracking->started==1 && $tracking->ended==0)
        <span class="badge badge-warning ">Bus  Enroute  </span>
        @elseif($tracking->ended==1)
        <span class="badge badge-warning "> Bus Arrived </span>
        @endif
        </h1>
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
              <div class="col-md-3">
                <table>
                <tr>
                  <th scope="row"><strong>Route: </strong> </th><td>{{$times_id->route->start->code??'None'}} - {{$times_id->route->end->code??'None'}}</td>
                <tr>
                  <tr>
                  <th scope="row"><strong>Time: </strong></th>  <td>{{$times_id->departure_time}} - {{$times_id->arrival_time}}</td>
                  <tr>
                    <tr>
                    <td><b>Driver: </b></td>  <td> {{$times_id->driver->name}}</td>
                    <tr>
                </table>
              </div>
              <div class="col-md-3">
                <table>
                <tr>
                  <th scope="row"><strong>Bus : </strong> </th><td>{{$times_id->bus->number_plate??'NONE'}} - Capacity:  {{$times_id->bus->seating_capacity??''}}</td>
                <tr>
                  <tr>
                  <th scope="row"><strong>Tickets: </strong></th>  <td>{{$bookings->count()}}</td>
                  <tr>
                </table>
              </div>
              <div class="col-md-3">
                @if(auth()->user()->hasAnyRole('BT Driver'))
                @if($tracking->started==0)
                <a  href="{{route('bustravel.bookings.route.tracking.start',$tracking->id)}}" onclick="return confirm('Are you sure  you want to start this Route')" class="btn btn-app btn-warning">
               <i class="fas fa-play" aria-hidden="true"></i> Start Route
             </a>
               @else
               <a  class="btn btn-app btn-warning">
              <i class="fas fa-play" aria-hidden="true"></i> Start Route
              </a>
               @endif
               @endif
                @if($tracking->started==1)
                 Started: {{$tracking->start_time}}

                @endif
              </div>
            <div class="col-md-3">
              @if(auth()->user()->hasAnyRole('BT Driver'))
              @if($tracking->started==1 && $tracking->ended==0)
              <a  href="{{route('bustravel.bookings.route.tracking.end',$tracking->id)}}" onclick="return confirm('Are you sure  you want to End this Route')" class="btn btn-app btn-warning">
             <i class="fas fa-times" aria-hidden="true"></i> End Route
           </a>
            @else
            <a   class="btn btn-app btn-warning">
           <i class="fas fa-times" aria-hidden="true"></i> End Route
         </a>
            @endif
            @endif
            @if($tracking->ended==1)
             Ended:{{$tracking->end_time}}
            @endif
              </div>
            </div>
          </div>
        </div>
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
                <div id="pieChart2" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></div>
                </div>
                <div class="col-md-3">
                  <div id="pieChart3" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></div>
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
                                <th scope="col">Status</th>
                                <th scope="col">On Board</th>
                                <th scope="col">Ticket</th>
                                <th scope="col">Paid Date</th>
                                <th scope="col">Travel Date </th>
                                <th scope="col">Created </th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach ($bookings as $booking)
                            <tr>
                              <td>@if($booking->status==1)
                                    <span class="badge badge-success "> <i class="fas fa-check" aria-hidden="true"></i> </span>
                                  @else
                                    <span class="badge badge-danger "> <i class="fas fa-check" aria-hidden="true"></i> </span>

                                  @endif
                               </td>
                               <td>@if($booking->boarded==1)
                                     <span class="badge badge-success "> <i class="fas fa-check" aria-hidden="true"></i> Yes</span>
                                   @else
                                   @if(auth()->user()->hasAnyRole('BT Driver'))
                                   <a href="{{route('bustravel.bookings.boarded',$booking->id)}}" onclick="return confirm('Are you sure  Ticket [{{$booking->ticket_number}}] is On Board')" ><span class="badge badge-danger "> <i class="fas fa-times" aria-hidden="true"></i> No</span></a>
                                   @else
                                    <span class="badge badge-danger "> <i class="fas fa-times" aria-hidden="true"></i> No</span>
                                   @endif

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

  var myChart = echarts.init(document.getElementById('pieChart2'));
  option = {
      tooltip: {
          trigger: 'item',
          formatter: '{a} <br/>{b} : {c} ({d}%)'
      },
      legend: {
          type: 'scroll',
          orient: 'horizontal',
          right: 10,
          top: 20,
          bottom: 20,
          data: ['Booked', 'Not Booked',],

          selected:  [
            {value:{{$bookings->count()}},name:'Booked'},
            {value:{{$not_booked}},name:'Not Booked'},
          ],
      },
      series: [
          {
              name: 'Seats',
              type: 'pie',
              radius: '45%',
              center: ['40%', '50%'],
              data: [
               {value: {{$bookings->count()}}, name: 'Booked'},
               {value: {{$not_booked}}, name: 'Not Booked'},
           ],
              emphasis: {
                  itemStyle: {
                      shadowBlur: 10,
                      shadowOffsetX: 0,
                      shadowColor: 'rgba(0, 0, 0, 0.5)'
                  }
              }
          }
      ]
  };

myChart.setOption(option);

var myChart1 = echarts.init(document.getElementById('pieChart3'));
option1 = {
    tooltip: {
        trigger: 'item',
        formatter: '{a} <br/>{b} : {c} ({d}%)'
    },
    legend: {
        type: 'scroll',
        orient: 'horizontal',
        right: 10,
        top: 20,
        bottom: 50,
        data: ['OnBoard', 'Not OnBoard',],

        selected:  [
          {value:{{$onboard_tickets}},name:'OnBoard'},
          {value:{{$notonboard_tickets}},name:'Not OnBoard'},
        ],
    },
    series: [
        {
            name: 'Board',
            type: 'pie',
            radius: '45%',
            center: ['40%', '50%'],
            data: [
             {value: {{$onboard_tickets}}, name: 'OnBoard'},
             {value: {{$notonboard_tickets}}, name: 'Not OnBoard'},
         ],
            emphasis: {
                itemStyle: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            }
        }
    ]
};

myChart1.setOption(option1);


})
</script>

@stop
