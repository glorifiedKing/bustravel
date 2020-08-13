@extends('bustravel::backend.layouts.app')

@section('title', 'General Settings')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Ticket Scanner Logs</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Ticket Scanner logs</li>
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
            <h5 class="card-title">  Logs</h5>

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
              
            <div class="row">
               <div class="col-md-12">
                  <table class="table table-striped table-hover" id="log-table">
                    <thead>
                      <tr>                        
                        <th scope="col">Device id</th>
                        <th scope="col">Request Attributes</th>
                        <th scope="col">Result</th>
                        <th scope="col">Ticket Number</th>                       

                    </tr>
                    </thead>
                    <tbody>
                      @foreach ($logs as $log)
                          <tr>
                            <td>{{$log->device->device_id}}</td>                            
                            <td>{{json_encode($log->request_attributes)}}</td>
                            <td>{{$log->result}}</td>
                          <td>{{$log->ticket_number}}</td>
                            
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
        
        
    </div>
</div>
@stop

@section('css')

@stop

@section('js')
    @parent
    <script>
        $(function () {
var table = $('#log-table').DataTable({
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
  $('#editModal').on('show.bs.modal', function (event) {
       var button = $(event.relatedTarget) // Button that triggered the modal
        var Name = button.data('name') // Extract info from data-* attributes
        var Id = button.data('id')
        var Operator = button.data('operator')
        var Order = button.data('order')
        var Status = button.data('status')
        var Required = button.data('required')

        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)

        modal.find('.modal-body #Name').val(Name)
        modal.find('.modal-body #Id').val(Id)
        modal.find('.modal-body #Order').val(Order)
        modal.find('.modal-body #Operator').val(Operator)
        $('input[name=status][value=' + Status + ']').prop('checked',true)
        $('input[name=is_required][value=' + Required + ']').prop('checked',true)

      });
})
</script>

@stop
