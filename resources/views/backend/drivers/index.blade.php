@extends('bustravel::backend.layouts.app')

@section('title', 'Drivers')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Drivers</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">drivers</li>
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
            <h5 class="card-title">All Drivers</h5>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus" aria-hidden="true"></i>
                </button>
                <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <a href="{{route('bustravel.drivers.create')}}" class="dropdown-item">New Driver</a>
                    <a href="#" class="dropdown-item">delete selected</a>
                </div>
                </div>

            </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
               <div class="col-md-12">
                 <table id="example1" class="table table-bordered table-hover table-striped dataTable" role="grid" aria-describedby="example1_info" summary="List of Drivers">
                        <thead>
                            <tr>
                                <th scope="col">Status</th>
                                <th scope="col">Picture</th>
                                <th scope="col">Name</th>
                                <th scope="col">Operator Name</th>
                                <th scope="col">NIN</th>
                                <th scope="col">Permit No</th>
                                <th scope="col"> Age</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach ($drivers as $driver)
                            <tr>
                              <td>@if($driver->status==1)
                                    <a href="#" class="btn btn-xs btn-success"> <i class="fas fa-check" aria-hidden="true"></i></a>
                                  @else
                                  <a href="#" class="btn btn-xs btn-danger"> <i class="fas fa-times" aria-hidden="true"></i></a>

                                  @endif
                               </td>
                               <td>@if($driver->picture)
                                     <img src="{{url('/drivers/'.$driver->picture) }}" width="50px" alt="{{$driver->name}}"/>
                                   @endif
                                </td>
                               <td>{{$driver->name}}</td>
                                <td>{{$driver->operator->name??'None'}}</td>
                                <td>{{$driver->nin}}</td>
                                <td>{{$driver->driving_permit_no}}</td>
                                <td>
                                     @if(!is_null($driver->date_of_birth))
																				 {{ Carbon\Carbon::parse($driver->date_of_birth)->age}}
			                                @endif
                                </td>
                                <td><a title="Edit" href="{{route('bustravel.drivers.edit',$driver->id)}}"><i class="fas fa-edit" aria-hidden="true"></i></a>
                                    <a title="Delete" onclick="return confirm('Are you sure you want to delete this Driver {{$driver->name}}')" href="{{route('bustravel.drivers.delete',$driver->id)}}"><span style="color:tomato"><i class="fas fa-trash-alt" aria-hidden="true"></i></span></a>
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
                  <h5 class="description-header">{{number_format($drivers->count(),0)}}</h5>
                    <span class="description-text">TOTAL NUMBER OF DRIVERS</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <h5 class="description-header">{{number_format($routes->count(),0)}}</h5>
                    <span class="description-text">TOTAL NUMBER OF ROUTES</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <h5 class="description-header">{{number_format($services,0)}}</h5>
                    <span class="description-text">TOTAL NUMBER OF ROUTES SERVICES</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block">
                    <h5 class="description-header">{{number_format($buses->count(),0)}}</h5>
                    <span class="description-text">TOTAL NUMBER OF BUS</span>
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
})
</script>

@stop
