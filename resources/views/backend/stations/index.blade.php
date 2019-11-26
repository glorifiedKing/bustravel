@extends('bustravel::backend.layouts.app')

@section('title', 'Bus Stations')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Bus Stations</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">bus stations</li>
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
            <h5 class="card-title">All Bus Stations</h5>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
                <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-plus"></i>
                </button>
                @can('Manage BT Stations')
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <a href="{{route('bustravel.stations.create')}}" class="dropdown-item">New Station</a>
                    <a href="#" class="dropdown-item">delete selected</a>                    
                </div>
                @endcan
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
                                <th>Station Name</th>
                                <th>Station Code</th>
                                <th>Station Address</th> 
                                <th>Station Cordinates</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>                        
                        @foreach ($bus_stations as $bus_station)
                            <tr>
                                <td>{{$bus_station->name}}</td>
                                <td>{{$bus_station->code}}</td>
                                <td>{{$bus_station->address.' '.$bus_station->province.' '.$bus_station->city}}</td>
                                <td>lat: {{$bus_station->latitude}} log: {{$bus_station->longitude}}</td>
                                <td>
                                    @can('Manage BT Stations')
                                    <a title="Edit" href="{{route('bustravel.stations.edit',$bus_station->id)}}"><i class="fas fa-edit"></i></a>
                                    <a title="Delete" onclick="return confirm('are you sure you want to delete this station')" href="{{route('bustravel.stations.delete',$bus_station->id)}}"><span style="color:tomato"><i class="fas fa-trash-alt"></i></span></a>
                                    @endcan
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
                    <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>
                    <h5 class="description-header">$35,210.43</h5>
                    <span class="description-text">TOTAL NUMBER OF STATIONS</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>
                    <h5 class="description-header">$10,390.90</h5>
                    <span class="description-text">TOTAL NUMBER OF STATIONS</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
                    <h5 class="description-header">$24,813.53</h5>
                    <span class="description-text">TOTAL NUMBER OF STATIONS</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block">
                    <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>
                    <h5 class="description-header">1200</h5>
                    <span class="description-text">TOTAL NUMBER OF STATIONS</span>
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