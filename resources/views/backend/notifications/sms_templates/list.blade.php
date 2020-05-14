@extends('bustravel::backend.layouts.app')

@section('title', 'Sms Templates')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Sms Templates</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Sms templates</li>
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
            <h5 class="card-title">All Sms Templates</h5>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus" aria-hidden="true"></i>
                </button>
                <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <a href="{{route('bustravel.sms.templates.create')}}" class="dropdown-item">New Sms Template</a>
                    
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
                                <th scope="col">Purpose</th>
                                <th scope="col">Language</th>
                                <th scope="col">Message</th>
                                <th scope="col">Is Default?</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach ($sms_templates as $sms_template)
                            <tr>                              
                                <td>{{$sms_template->operator->name}}</td>
                                <td>{{$sms_template->purpose}}</td>
                                <td>{{$sms_template->language}}</td>
                                <td>{{$sms_template->message}}</td>
                                <td>{{$sms_template->is_default}}</td>
                                <td><a title="Edit" href="{{route('bustravel.sms.templates.edit',$sms_template->id)}}"><i class="fas fa-edit" aria-hidden="true"></i></a>
                                    <a title="Delete" onclick="return confirm('Are you sure you want to delete this sms Template')" href="{{route('bustravel.sms.templates.delete',$sms_template->id)}}"><span style="color:tomato"><i class="fas fa-trash-alt" aria-hidden="true"></i></span></a>
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
                    <h5 class="description-header">{{count($sms_templates)}}</h5>
                    <span class="description-text">TOTAL NUMBER OF TEMPLATES</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-md-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-warning"><i class="fas fa-caret-left" aria-hidden="true"></i> </span>
                    <h5 class="description-header">{{$sms_templates_latest_count}}</h5>
                    <span class="description-text">LATEST TEMPLATES</span>
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
