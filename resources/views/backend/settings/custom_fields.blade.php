@extends('bustravel::backend.layouts.app')

@section('title', 'Fields')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Fields</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">fields</li>
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
            <h5 class="card-title">  All Fields</h5>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
                <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-plus"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <a href="#modal-default" class="dropdown-item" data-toggle="modal">New Field</a>
                    <a href="#" class="dropdown-item">delete selected</a>
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
                                <th>Status</th>
                                <th>Operator</th>
                                <th>Name</th>
                                <th>Prefix</th>
                                <th>Required</th>
                                <th>Order</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach ($fields as $field)
                            <tr>
                              <td>@if($field->status==1)
                                    <a href="#" class="btn btn-xs btn-success"> <i class="fas fa-check"></i></a>
                                  @else
                                  <a href="#" class="btn btn-xs btn-danger"> <i class="fas fa-times"></i></a>

                                  @endif
                               </td>
                                <td>{{$field->operator->name??'None'}}</td>
                               <td>{{$field->field_name}}</td>
                                <td>{{$field->field_prefix}}</td>
                                <td>
                                  @if($field->is_required==1)
                                        <a href="#" class="btn btn-xs btn-success"> <i class="fas fa-check"></i></a>
                                      @else
                                      <a href="#" class="btn btn-xs btn-danger"> <i class="fas fa-times"></i></a>

                                      @endif
                                </td>
                                <td>{{$field->field_order}}</td>
                                <td>
                                    <a title="Edit" href="#" data-id="{{$field->id}}" data-name="{{$field->field_name}}" data-prefix="{{$field->field_prefix}}" data-order="{{$field->field_order}}" data-required="{{$field->is_required}}" data-status="{{$field->status}}" data-operator="{{$field->operator_id}}" data-toggle="modal"  data-target="#editModal"><i class="fas fa-edit"></i></a>
                                    <a title="Delete" onclick="return confirm('Are you sure you want to delete this Feild')" href="{{route('bustravel.company_settings.fields.delete',$field->id)}}"><span style="color:tomato"><i class="fas fa-trash-alt"></i></span></a>
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
        <!--add form -->
        <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add  New Field</h4>

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.company_settings.fields.store')}}" method="POST" enctype="multipart/form-data" >
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <div class="form-group col-md-12 ">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="text"  name="field_name" value="{{old('field_name')}}" class="form-control {{ $errors->has('field_name') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Name" >
                        @if ($errors->has('field_name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('field_name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-12 ">
                        <label for="exampleInputEmail1">Order</label>
                        <input type="text"  name="field_order" value="{{old('field_order')}}" class="form-control {{ $errors->has('field_order') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Order" >
                        @if ($errors->has('field_order'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('field_order') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class=" col-md-6 form-group">
                        <label for="signed" class=" col-md-12 control-label">Required</label>
                        <label class="radio-inline">
                          <input type="radio" id="Active" name="is_required" value="1" > Yes</label>
                        </label>
                       <label class="radio-inline">
                          <input type="radio" id="Deactive" name="is_required" value="0" checked > No </label>
                       </label>
                    </div>
                    <div class=" col-md-6 form-group">
                        <label for="signed" class=" col-md-12 control-label">Status</label>
                        <label class="radio-inline">
                          <input type="radio" id="Active" name="status" value="1" checked> Active</label>
                        </label>
                       <label class="radio-inline">
                          <input type="radio" id="Deactive" name="status" value="0" > Deactive</label>
                       </label>
                    </div>
                      <button type="submit" class="btn btn-primary">Save changes</button>
                  </div>
              </div>
            </form>
          </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
        <!---end add -->

        <!--edit form -->
        <div class="modal fade" id="editModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Field</h4>

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.company_settings.fields.update','hhh')}}" method="POST" enctype="multipart/form-data" >
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <div class="form-group col-md-12 ">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="hidden" id="Id" value="" name="id">
                        <input type="hidden" id="Operator" value="" name="operator_id">
                        <input type="text"  id="Name" name="field_name" value="{{old('field_name')}}" class="form-control {{ $errors->has('field_name') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Name" >
                        @if ($errors->has('field_name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('field_name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-12 ">
                        <label for="exampleInputEmail1">Order</label>
                        <input id="Order" type="text"  name="field_order" value="{{old('field_order')}}" class="form-control {{ $errors->has('field_order') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Order" >
                        @if ($errors->has('field_order'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('field_order') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class=" col-md-6 form-group">
                        <label for="signed" class=" col-md-12 control-label">Required</label>
                        <label class="radio-inline">
                          <input type="radio" id="Active" name="is_required" value="1" > Yes</label>
                        </label>
                       <label class="radio-inline">
                          <input type="radio" id="Deactive" name="is_required" value="0" checked > No </label>
                       </label>
                    </div>
                    <div class=" col-md-6 form-group">
                        <label for="signed" class=" col-md-12 control-label">Status</label>
                        <label class="radio-inline">
                          <input type="radio" id="Active" name="status" value="1" checked> Active</label>
                        </label>
                       <label class="radio-inline">
                          <input type="radio" id="Deactive" name="status" value="0" > Deactive</label>
                       </label>
                    </div>
                      <button type="submit" class="btn btn-primary">Save changes</button>
                  </div>
              </div>
            </form>
          </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
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
