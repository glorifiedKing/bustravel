@extends('bustravel::backend.layouts.app')

@section('title', 'Faqs')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Faqs</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">faqs</li>
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
            <h5 class="card-title">  All Faqs</h5>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
                <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-plus"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <a href="#modal-default" class="dropdown-item" data-toggle="modal">New Faq</a>
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
                                <th>Question</th>
                                <th>Answer</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach ($faqs as $faq)
                            <tr>
                                <td>{{$faq->question}}</td>
                               <td>{{$faq->answer}}</td>
                                <td>
                                    <a title="Edit" href="#" data-id="{{$faq->id}}" data-question="{{$faq->question}}" data-answer="{{$faq->answer}}" data-toggle="modal"  data-target="#editModal"><i class="fas fa-edit"></i></a>
                                    <a title="Delete" onclick="return confirm('Are you sure you want to delete this Faqs')" href="{{route('bustravel.faqs.delete',$faq->id)}}"><span style="color:tomato"><i class="fas fa-trash-alt"></i></span></a>
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
              <h4 class="modal-title">Add  New Faq</h4>

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.faqs.store')}}" method="POST" enctype="multipart/form-data" >
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <div class="form-group col-md-12 ">
                        <label for="exampleInputEmail1">Question</label>
                        <textarea class="form-control {{ $errors->has('question') ? ' is-invalid' : '' }}" rows="3" placeholder="Enter Question" name="question" >{{old('question')}}</textarea>
                        @if ($errors->has('question'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('question') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-12 ">
                        <label for="exampleInputEmail1">Answer</label>
                        <textarea class="form-control {{ $errors->has('answer') ? ' is-invalid' : '' }}" rows="3" placeholder="Enter Answer" name="answer" >{{old('answer')}}</textarea>
                        @if ($errors->has('answer'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('answer') }}</strong>
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
              <form role="form" action="{{route('bustravel.faqs.update','hhh')}}" method="POST" enctype="multipart/form-data" >
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <div class="form-group col-md-12 ">
                        <input type="hidden" id="Id" value="" name="id">
                    </div>
                    <div class="form-group col-md-12 ">
                        <label for="exampleInputEmail1">Question</label>
                        <textarea id="Question" class="form-control {{ $errors->has('question') ? ' is-invalid' : '' }}" rows="3" placeholder="Enter Question" name="question" >{{old('question')}}</textarea>
                        @if ($errors->has('question'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('question') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-12 ">
                        <label for="exampleInputEmail1">Answer</label>
                        <textarea id="Answer" class="form-control {{ $errors->has('answer') ? ' is-invalid' : '' }}" rows="3" placeholder="Enter Answer" name="answer" >{{old('answer')}}</textarea>
                        @if ($errors->has('answer'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('answer') }}</strong>
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
        var Id = button.data('id')
        var Question = button.data('question')
        var Answer = button.data('answer')

        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)

        modal.find('.modal-body #Id').val(Id)
        modal.find('.modal-body #Question').val(Question)
        modal.find('.modal-body #Answer').val(Answer)

      });
})
</script>

@stop
