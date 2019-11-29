@extends('bustravel::backend.layouts.app')

@section('title', 'Routes')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small><a href="{{route('bustravel.routes')}}" class="btn btn-info">Back</a></small> Routes </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">routes</li>
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
            <h5 class="card-title">Add Route </h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.routes.store')}}" method="POST" >
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                    <div class="form-group col-md-6">
                         <label> Operator</label>
                         <select class="form-control select2 {{ $errors->has('operator_id') ? ' is-invalid' : '' }}" name="operator_id"  placeholder="Select Operator">
                           <option value="">Select Operator</option>
                           @foreach($bus_operators as $operator)
                               <option value="{{$operator->id}}" @php echo old('operator_id') == $operator->id ? 'selected' :  "" @endphp>{{$operator->name}} - {{$operator->code}}</option>
                           @endforeach
                         </select>
                         @if ($errors->has('operator_id'))
                             <span class="invalid-feedback">
                                 <strong>{{ $errors->first('operator_id') }}</strong>
                             </span>
                         @endif
                    </div>
                    <div class="form-group col-md-3 ">
                      <label>Start Station</label>
                      <select class="form-control select2 {{ $errors->has('start_station') ? ' is-invalid' : '' }}" name="start_station"  placeholder="Select Operator">
                        <option value="">Select Station</option>
                        @foreach($stations as $station)
                            <option value="{{$station->id}}" @php echo old('start_station') == $station->id ? 'selected' :  "" @endphp>{{$station->name}} - {{$station->code}}</option>
                        @endforeach
                      </select>
                      @if ($errors->has('start_station'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('start_station') }}</strong>
                          </span>
                      @endif
                    </div>
                    <div class="form-group col-md-3 ">
                      <label>End Station</label>
                      <select class="form-control select2 {{ $errors->has('end_station') ? ' is-invalid' : '' }}" name="end_station"  placeholder="Select Operator">
                        <option value="">Select Station</option>
                        @foreach($stations as $station)
                            <option value="{{$station->id}}" @php echo old('end_station') == $station->id ? 'selected' :  "" @endphp>{{$station->name}} - {{$station->code}}</option>
                        @endforeach
                      </select>
                      @if ($errors->has('end_station'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('end_station') }}</strong>
                          </span>
                      @endif
                    </div>
                    <div class="form-group col-md-3 ">
                      <label>Price</label>
                      <input type="text"  name="price" value="{{old('price')}}" class="form-control {{ $errors->has('price') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Price" >
                      @if ($errors->has('price'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('price') }}</strong>
                          </span>
                      @endif
                    </div>
                    <div class="form-group col-md-3 ">
                      <label>Return Price</label>
                      <input type="text"  name="return_price" value="{{old('return_price')}}" class="form-control {{ $errors->has('price') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Return Price" >
                      @if ($errors->has('return_price'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('return_price') }}</strong>
                          </span>
                      @endif
                    </div>
                    <div class="form-group col-md-12"><label>Stopovers Routes</label></div>
                    <div class="form-group col-md-12">
                    <div class="row">
                      <div class="form-group col-md-6">
                      <select id="item-selector" class="form-control select2" name="select_item_id">
         @foreach($routes as $stopover)
           <option data-itemid="{{$stopover->id}}" data-itemname="{{$stopover->start->name}} - {{$stopover->end->name}}"  value="{{$stopover->id}}">{{$stopover->start->name}} - {{$stopover->end->name}}</option>
         @endforeach
       </select>
     </div>
                    <div class="form-group col-md-2">  <button type="button" class="btn btn-success form-control" id="add_item" >Add</button></div>
                    <div class="form-group col-md-2">  <button type="button" class="delete-row btn btn-danger form-control">Delete</button></div>
                  </div>
                      <table id="new-table" class="table table-striped table-hover">
                           <thead>
                             <tr>
                               <th width="30"></th>
                               <th >Station</th>
                               <th width="100" >Order</th>
                             </tr>
                           </thead>

                           <tbody>
                           </tbody>
                        </table>
                    </div>
                    <div class=" col-md-12 form-group">
                    </div>
                    <div class=" col-md-3 form-group">
                        <label for="signed" class=" col-md-12 control-label">Status</label>
                        <label class="radio-inline">
                          <input type="radio" id="Active" name="status" value="1" checked> Active</label>
                        </label>
                       <label class="radio-inline">
                          <input type="radio" id="Deactive" name="status" value="0" > Deactive</label>
                       </label>
                    </div>
                  </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
              <div class="form-group col-md-12">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
              </div>
            </form>
            </div>

            <!-- /.row -->
            </div>
            <!-- ./card-body -->

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
         $('div.alert').not('.alert-danger').delay(5000).fadeOut(350);
         $('.select2').select2();
         $('#add_item').on('click',function(e){
        e.preventDefault();
        //get selected option
        var routeid = $('#item-selector').find(":selected").data('itemid');
        var routename = $('#item-selector').find(":selected").data('itemname');
        var markup = "<tr item-id='"+routeid+"'><td><input type='checkbox' name='checkeditem[]'></td><td ><input type='hidden' value='"+routeid+"' name='stopover_id[]'><input type='text' class='form-control' name='name1'size='4' value='"+routename+"' readonly /></td><td><input type='text' class='form-control' name='stopover_order[]'size='4' value='0'  required /></td></tr>";

          var exists =  0;
         $("table tbody").find("tr").each(function () {
             var current_stock_id = $(this).attr('item-id');
             if(current_stock_id == routeid)
             {
               exists = exists + 1;
             }
           });
         if(exists == 0)
         {
                 $("#new-table tbody").append(markup);
         }
       });
      // Find and remove selected table rows
       $(".delete-row").click(function(){
           $("table tbody").find('input[name="checkeditem[]"]').each(function(){
               if($(this).is(":checked")){
                   $(this).parents("tr").remove();
               }
           });
       });
       })
</script>
@stop
