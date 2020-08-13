@extends('bustravel::backend.layouts.app')

@section('title', 'Ticket Scanners')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Ticket Scanners</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Ticket Scanners</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
          @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <ul>
             @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
             @endforeach
        </ul>
       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
       <span aria-hidden="true">&times;</span>
       </button>
      </div>
      @endif
        <div class="card">
            <div class="card-header">
            <h5 class="card-title">  All Ticket Scanners</h5>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus" aria-hidden="true"></i>
                </button>
                <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <a href="{{route('bustravel.ticket_scanners.create')}}" class="dropdown-item" >New Ticket Scanner</a>
                    <a href="#" class="dropdown-item">delete selected</a>
                </div>
                </div>

            </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <form method="GET">
                
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="inputEmail4">Operator id</label>
                    <select  name="id" class="form-control {{ $errors->has('id') ? 'is-invalid' : '' }}" id="inputEmail4">
                        @foreach ($operators as $operator)
                          <option value="{{$operator->id}}">{{$operator->name}}</option>
                        @endforeach
                    </select>

                    @error('transaction_id')
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
               <div class="col-md-12">
                  <table class="table table-striped table-hover" id="ticket-table">
                    <thead>
                      <tr>
                        <th scope="col">operator</th>
                        <th scope="col">Device id</th>
                        <th scope="col">Attributes</th>
                        <th scope="col">Active</th>
                        <th scope="col">Actions</th>                   

                    </tr>
                    </thead>
                    <tbody>
                      @foreach ($ticket_scanners as $device)
                          <tr>
                            <td>{{$device->operator->name}}</td>
                            <td>{{$device->device_id}}</td>
                            <td>{{json_encode($device->description)}}</td>
                          <td><span class="badge {{($device->active == 1) ? 'badge-success ' : 'badge-danger' }}">{{($device->active == 1) ? 'Active' : 'Disabled'}}</span></td>
                            <td>
                            <a class="btn btn-xs {{($device->active == 1) ? 'btn-danger' : 'btn-success'}}" href="{{route('bustravel.ticket_scanners.toggle_status',$device->id)}}">Toggle Status</a>
                            <a href="{{route('bustravel.ticket_scanners.edit',$device->id)}}" class="btn btn-xs btn-warning">Edit</a>
                            <a href="{{route('bustravel.ticket_scanners.delete',$device->id)}}" class="btn btn-xs btn-danger">Delete</a>
                            <a href="{{route('bustravel.ticket_scanners.scan_logs',$device->id)}}" class="btn btn-xs btn-info">Logs</a>
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

            <!-- /.card-footer -->
        </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->

      <!-- /.modal -->
        <!---end add -->

      <!-- /.modal -->
        <!---end edit -->
    </div>
</div>
@stop

@section('css')

@stop

@section('js')
    @parent
    <script>
        $(function () {
var table = $('#ticket-table').DataTable({
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
  $('.select2').select2();
  
})
</script>

@stop
