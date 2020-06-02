@extends('bustravel::backend.layouts.app')

@section('title', 'Payment Reports')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Payment Reports</h1>
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
            <h5 class="card-title">Report starting from: {{$period}}</h5>

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
            <div class="row">
               <div class="col-md-12">
                    <table id="example1" class="table table-bordered table-hover table-striped dataTable" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr>
                                <th scope="col">Time</th>
                                <th scope="col">payment method</th>
                                <th scope="col">client</th>
                                <th scope="col">Amount Client paid</th>
                                <th scope="col">Amount to operator</th>
                                <th scope="col">Status</th>

                            </tr>
                        </thead>
                        <tbody>

                        @foreach ($payment_reports as $payment_report)
                            <tr>
                                <td>{{$payment_report->created_at}}</td>
                                <td>{{$payment_report->payment_transaction->payment_source??''}}</td>
                                <td>{{$payment_report->payment_transaction->payee_reference??''}}</td>
                                <td>{{$payment_report->payment_transaction->amount}}
                                <td>{{$payment_report->amount}}</td>
                                <td>{{$payment_report->status}}</td>

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
                    <h5 class="description-header">{{$count_all_payments}}</h5>
                    <span class="description-text">TOTAL NUMBER OF PAYMENTS</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-md-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-warning"><i class="fas fa-caret-left" aria-hidden="true"></i> </span>
                    <h5 class="description-header">{{count($payment_reports)}}</h5>
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
            $("#example1").DataTable();

        });
    </script>
@stop
