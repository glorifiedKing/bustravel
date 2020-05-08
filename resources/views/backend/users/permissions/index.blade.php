@extends('bustravel::backend.layouts.app')

@section('title', 'Permissions')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Permissions</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">permissions</li>
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
            <h5 class="card-title">
              All Permissions
            </h5>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus" aria-hidden="true"></i>
                </button>
                <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <a href="#modal-default" class="dropdown-item" data-toggle="modal">New Permission</a>
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
                                <th scope="col">Name</th>
                                <th scope="col">Guard Name</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach ($permissions as $permission)
                            <tr>
                                <td>{{$permission->name}}</td>
                                <td>{{$permission->guard_name}}</td>
                                <td><a title="Edit" href="#" data-id="{{$permission->id}}" data-name="{{$permission->name}}" data-toggle="modal"  data-target="#editModal"><i class="fas fa-edit" aria-hidden="true"></i></a>
                                    <a title="Delete" onclick="return confirm('Are you sure you want to delete this Permission')" href="{{route('bustravel.users.permissions.delete',$permission->id)}}"><span style="color:tomato"><i class="fas fa-trash-alt" aria-hidden="true"></i></span></a>
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
              <h4 class="modal-title">Add  Permission</h4>

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.users.permissions.store')}}" method="POST" enctype="multipart/form-data" aria-label="Adding Permission">
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <div class="form-group col-md-12 ">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="text"  name="name" value="{{old('name')}}" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Name"  required>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
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
              <h4 class="modal-title">Edit  Permission</h4>

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.users.permissions.update','hhh')}}" method="POST" enctype="multipart/form-data" aria-label="Editing Permission">
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <div class="form-group col-md-12 ">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="hidden" name="id" id="Id">
                        <input type="text"  name="name" value="{{old('name')}}" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="Name" placeholder="Enter Name"  required>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
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
          $('#editModal').on('show.bs.modal', function (event) {
           var button = $(event.relatedTarget) // Button that triggered the modal
            var Name = button.data('name') // Extract info from data-* attributes
            var Id = button.data('id')
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)

            modal.find('.modal-body #Name').val(Name)
            modal.find('.modal-body #Id').val(Id)
          });
          $("#example1").DataTable();
          $('div.alert').not('.alert-danger').delay(5000).fadeOut(350);
        });
    </script>
@stop
