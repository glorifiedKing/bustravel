@extends('bustravel::backend.layouts.app')

@section('title', 'General Settings')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">General Settings</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">general settings</li>
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
            <h5 class="card-title">  All General Settings</h5>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus" aria-hidden="true"></i>
                </button>
                <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <a href="#modal-default" class="dropdown-item" data-toggle="modal">New General Setting</a>
                    <a href="#" class="dropdown-item">delete selected</a>
                </div>
                </div>

            </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
               <div class="col-md-12">


          <form action="{{route('bustravel.general_settings.update')}}" method="POST">
            {{csrf_field() }}
 @foreach ($settings as $count => $row)
        <div class="row">
         <div class="col-sm-3">
         <div class="form-group no-margin-hr">
             <label class="control-label">Prefix <span class="required-star">*</span></label>
             <input required="required" type="text" class="form-control mandatory" name="setting_prefix[]" id="SettingPrefix" value="{{$row->setting_prefix}}" readonly >
       <input type="hidden" name="id[]" id="id" value="{{$row->id}}" />
         </div>
     </div><!-- col-sm-4 -->
     <div class="col-sm-5">
     <div class="form-group no-margin-hr">
         <label class="control-label">Description <span class="required-star">*</span></label>
         <input required="required" type="text" class="form-control mandatory" name="setting_description[]" id="SettingDesc"  value="{{$row->setting_description}}" readonly >
     </div>
   </div>
   <div class="col-sm-4">
   <div class="form-group no-margin-hr">
       <label class="control-label">Setting Value <span class="required-star">*</span></label>
       <input  type="text" class="form-control mandatory" name="setting_value[]" id="SettingValue" value="{{$row->setting_value}}" >
   </div>
 </div>
</div>

 @endforeach
 <div class="box-footer">
 <div class="form-group col-md-12">
   <button type="submit" class="btn btn-primary">Submit</button>
 </div>
 </div>
</form>
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
              <h4 class="modal-title">Add  New General Setting</h4>

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.general_settings.store')}}" method="POST" enctype="multipart/form-data" aria-label="Adding Field Content">
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <div class="form-group col-md-12 ">
                        <label for="exampleInputEmail1">Prefix</label>
                        <input type="text"  name="setting_prefix" value="{{old('setting_prefix')}}" class="form-control {{ $errors->has('setting_prefix') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Setting Prefix" >
                        @if ($errors->has('setting_prefix'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('setting_prefix') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-12 ">
                        <label for="exampleInputEmail1">Decription</label>
                        <input type="text"  name="setting_description" value="{{old('setting_description')}}" class="form-control {{ $errors->has('setting_description') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Description" >
                        @if ($errors->has('setting_description'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('setting_description') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-12 ">
                        <label for="exampleInputEmail1">Value</label>
                        <input type="text"  name="setting_value" value="{{old('setting_value')}}" class="form-control {{ $errors->has('setting_value') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Value" >
                        @if ($errors->has('setting_value'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('setting_value') }}</strong>
                            </span>
                        @endif
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
              <form role="form" action="{{route('bustravel.company_settings.fields.update','hhh')}}" method="POST" enctype="multipart/form-data" aria-label="Editing Fields">
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
