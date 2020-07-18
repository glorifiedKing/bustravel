@extends('bustravel::backend.layouts.app')

@section('title', 'Payment Reports')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Customer Payments</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">payment reports</li>
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
            <h5 class="card-title">Showing: {{$selected_date ?? ''}} {{$phone_number ?? ''}} {{$transaction_id ?? ''}}</h5>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus" aria-hidden="true"></i>
                </button>
                <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <a href="{{route('bustravel.reports.payments',\Carbon\Carbon::now()->subMonth()->toDateTimeString())}}" class="dropdown-item">Last Month</a>
                    <a href="{{route('bustravel.reports.payments',\Carbon\Carbon::now()->subMonth(3)->toDateTimeString())}}" class="dropdown-item">Last 3 Months</a>
                    <a href="{{route('bustravel.reports.payments',\Carbon\Carbon::now()->subMonth(6)->toDateTimeString())}}" class="dropdown-item">Last 6 Months</a>
                </div>
                </div>

            </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="inputEmail4">Transaction id</label>
                        <input type="text" name="transaction_id" value="{{$transaction_id ?? ''}}" class="form-control {{ $errors->has('transaction_id') ? 'is-invalid' : '' }}" id="inputEmail4">
                            @error('transaction_id')
                                <small class="form-text invalid-feedback" >
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputPassword4">Phone number</label>
                            <input type="text" name="phone_number" value="{{$phone_number ?? ''}}" class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" id="inputPassword4">
                            @error('phone_number')
                                <small class="form-text invalid-feedback" >
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputPassword4">Date of Transaction</label>
                            <input type="date" name="selected_date"  class="form-control {{ $errors->has('selected_date') ? 'is-invalid' : '' }}" id="inputPassword5">
                            @error('selected_date')
                                <small class="form-text invalid-feedback" >
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label>.</label>
                            <button class="form-control btn btn-success">Search</button>
                        </div>
                    </div>
                </form>
            <div class="row">
               <div class="col-md-12" style="overflow-x: scroll">
                    <table id="example1" class="table table-bordered table-hover table-striped dataTable" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr>
                                <th scope="col">Transaction Id</th>
                                <th scope="col">Date</th>
                                <th scope="col">transaction source</th>
                                <th scope="col">Status</th>
                                <th scope="col">phone number</th>
                                <th scope="col">Name</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Bus Services</th>
                                <th scope="col">Ticket Numbers</th>
                                <th scope="col">Actions</th>
                                

                            </tr>
                        </thead>
                        <tbody>

                        @foreach ($transactions as $payment_report)
                            <tr>
                                <td>{{$payment_report->id}}</td>
                                <td>{{\Carbon\Carbon::parse($payment_report->created_at)->format('d/m/Y')}}</td>
                                <td>{{$payment_report->payment_source}}</td>
                                <td>{{$payment_report->status}} {{$payment_report->payment_gateway_result}}</td>
                                <td>{{$payment_report->phone_number}}</td>
                                <td>{{$payment_report->first_name}}</td>
                                <td>{{$payment_report->amount}}</td>
                                <td>
                                    <ul>
                                        <li>Operator: {{$payment_report->operator->name ?? ''}}</li>
                                    @foreach ($payment_report->services() as $service)
                                    <li> From: {{$service['from']}} To: {{$service['to']}} At: {{$service['time']}}</li>
                                    @endforeach
                                    </ul>
                                </td>
                                <td>
                                    @foreach ($payment_report->bookings as $booking)
                                                {{$booking->ticket_number}},
                                    @endforeach
                                </td>
                            <td>@if($payment_report->status == 'completed')<a class="btn btn-danger" href="{{route('bustravel.transaction.resend_ticket',$payment_report->id)}}">Resend Ticket</a>@endif</td>


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
                <div class="col-sm-3 col-md-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-success"><i class="fas fa-caret-up" aria-hidden="true"></i> </span>
                    <h5 class="description-header">{{count($transactions)}}</h5>
                    <span class="description-text">TOTAL NUMBER OF TRANSACTIONS</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-md-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-warning"><i class="fas fa-caret-left" aria-hidden="true"></i> </span>
                    <h5 class="description-header">{{count($transactions)}}</h5>
                    <span class="description-text">LATEST PAYMENTS</span>
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
            $("#example1").DataTable(
                'responsive' : true,
            );

        });
    </script>
@stop
