@extends('bustravel::backend.layouts.app')

@section('title', 'Printers')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Printers</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">printers</li>
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
            <h5 class="card-title">All Printers</h5>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus" aria-hidden="true"></i>
                </button>
                <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <a href="{{route('bustravel.printers.create')}}" class="dropdown-item">New printer</a>
                    
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
                                <th scope="col">Operator</th>
                                <th scope="col">Printer</th>
                                <th scope="col">Printer URL</th>
                                <th scope="col">Is Default?</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach ($printers as $printer)
                            <tr>                              
                                <td>{{$printer->operator->name}}</td>
                                <td>{{$printer->printer_name}}</td>
                                <td>{{$printer->printer_url}}</td>
                                <td>{{($printer->is_default) ? 'yes' : 'no'}}</td>
                                <td><a title="Edit" href="{{route('bustravel.printers.edit',$printer->id)}}"><i class="fas fa-edit" aria-hidden="true"></i></a>
                                    <a title="Delete" onclick="return confirm('Are you sure you want to delete this Printer')" href="{{route('bustravel.printers.delete',$printer->id)}}"><span style="color:tomato"><i class="fas fa-trash-alt" aria-hidden="true"></i></span></a>
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
                <div class="col-sm-3 col-md-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-success"><i class="fas fa-caret-up" aria-hidden="true"></i> </span>
                    <h5 class="description-header">{{count($printers)}}</h5>
                    <span class="description-text">TOTAL NUMBER OF PRINTERS</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-md-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-warning"><i class="fas fa-caret-left" aria-hidden="true"></i> </span>
                    <h5 class="description-header">{{$printers_latest_count}}</h5>
                    <span class="description-text">LATEST PRINTERS</span>
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
