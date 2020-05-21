@extends('bustravel::backend.layouts.app')

@section('title', 'Bus Operators')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Bus Operators</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">bus operators</li>
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
            <h5 class="card-title">All Bus Operators</h5>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus" aria-hidden="true"></i>
                </button>
                <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <a href="{{route('bustravel.operators.create')}}" class="dropdown-item">New Operator</a>
                    <a href="#" class="dropdown-item">delete selected</a>
                </div>
                </div>

            </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
               <div class="col-md-12">
                    <table id="example1" class="table table-bordered table-hover table-striped dataTable" role="grid" aria-describedby="example1_info" summary="List of Operators">
                        <thead>
                            <tr>
                                <th scope="col">Status</th>
                                <th scope="col">Logo</th>
                                <th scope="col">Operator Name</th>
                                <th scope="col"> Code</th>
                                <th scope="col"> Address</th>
                                <th scope="col">Contact Person</th>
                                <th scope="col">Phone Number</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach ($bus_operators as $bus_operator)
                            <tr>
                              <td>@if($bus_operator->status==1)
                                    <a href="#" class="btn btn-xs btn-success"> <i class="fas fa-check" aria-hidden="true"></i></a>
                                  @else
                                  <a href="#" class="btn btn-xs btn-danger"> <i class="fas fa-times" aria-hidden="true"></i></a>

                                  @endif
                               </td>
                              <td>@if($bus_operator->logo)
                                    <img src="{{url('/logos/'.$bus_operator->logo) }}" width="50px" alt="{{$bus_operator->name}}"/>
                                  @endif
                               </td>
                                <td>{{$bus_operator->name}}</td>
                                <td>{{$bus_operator->code}}</td>
                                <td>{{$bus_operator->address}}</td>
                                <td>{{$bus_operator->contact_person_name}}</td>
                                <td>{{$bus_operator->phone_number}}</td>
                                <td><a title="Edit" href="{{route('bustravel.operators.edit',$bus_operator->id)}}"><i class="fas fa-edit" aria-hidden="true"></i></a>
                                    <a title="Delete" onclick="return confirm('Are you sure you want to delete this Operator')" href="{{route('bustravel.operators.delete',$bus_operator->id)}}"><span style="color:tomato"><i class="fas fa-trash-alt" aria-hidden="true"></i></span></a>
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
                    <span class="description-percentage text-success"><i class="fas fa-caret-up" aria-hidden="true"></i> 17%</span>
                    <h5 class="description-header">$35,210.43</h5>
                    <span class="description-text">TOTAL NUMBER OF OPERATORS</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-warning"><i class="fas fa-caret-left" aria-hidden="true"></i> 0%</span>
                    <h5 class="description-header">$10,390.90</h5>
                    <span class="description-text">TOTAL NUMBER OF OPERATORS</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-success"><i class="fas fa-caret-up" aria-hidden="true"></i> 20%</span>
                    <h5 class="description-header">$24,813.53</h5>
                    <span class="description-text">TOTAL NUMBER OF OPERATORS</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block">
                    <span class="description-percentage text-danger"><i class="fas fa-caret-down" aria-hidden="true"></i> 18%</span>
                    <h5 class="description-header">1200</h5>
                    <span class="description-text">TOTAL NUMBER OF OPERATORS</span>
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
            $('div.alert').not('.alert-danger').delay(5000).fadeOut(350);
        });
    </script>
@stop
