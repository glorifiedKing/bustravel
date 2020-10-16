@extends('bustravel::backend.layouts.app')

@section('title', 'Routes')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small>
          <a href="{{route('bustravel.routes')}}" class="btn btn-info">Back</a>
            @if(is_null($route->inverse))
            @if(!is_null($inverse_route))
             <a href="{{route('bustravel.routes.edit',$inverse_route->id)}}" class="btn btn-info">Inverse</a>
            @endif
            @else
            @if(!is_null($mainroute))
             <a href="{{route('bustravel.routes.edit',$mainroute->id)}}" class="btn btn-info">Main Route</a>
            @endif
            @endif


        </small> Routes </h1>
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

          <p>
          <span class="badge badge-warning ">   Updated {{ $diffs = Carbon\Carbon::parse($route->updated_at)->diffForHumans() }} </span>   &nbsp
          <span class="badge badge-success ">   Created {{ $diffs = Carbon\Carbon::parse($route->created_at)->diffForHumans() }} </span>    &nbsp
          </p>
        <div class="card">
            <div class="card-header">
            <h5 class="card-title">Edit {{$route->start->name}}  -- {{$route->end->name}}  Route</h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.routes.update',$route->id)}}" method="POST">
              {{csrf_field() }}



              <div class="box-body">
                <div class="row">
                  <div class="form-group col-md-3">
                    <label>From</label><br>
                    <select id="item-selector" class="form-control select2" name="select_startitem_id" style="width:100%">
                          <option>Select Station</option>
                       @foreach($stations as $station_start)
                           <option data-startitemid="{{$station_start->id}}" data-startitemname="{{$station_start->name}}"  value="{{$station_start->id}}">{{$station_start->name}} - {{$station_start->code}}</option>
                       @endforeach
                    </select>
                  </div>
                 <div class="form-group col-md-3">
                   <label>To</label>
                   <select id="item-selector2" class="form-control select2" name="select_enditem_id" style="width:100%">
                           <option>Select Station</option>
                       @foreach($stations as $station_end)
                           <option data-enditemid="{{$station_end->id}}" data-enditemname="{{$station_end->name}}"  value="{{$station_end->id}}">{{$station_end->name}} - {{$station_end->code}}</option>
                       @endforeach
                   </select>
                 </div>
  @php $stopovers =$route->stopovers()->orderBy('order')->get(); @endphp
                <div class="form-group col-md-2"> <br> <button type="button" class="btn btn-success form-control" id="add_item" >Add</button></div>
              </div>
                  <div class="row">
                  <div class="form-group col-md-12">
                    @php $stopovers =$route->stopovers()->orderBy('order')->get(); @endphp
                                       <div class="responsive" id="routes">
                                           <table class="table" id="routes-tbl">
                                               <caption>generated routes</caption>
                                               <thead>
                                                   <tr>
                                                       <th scope="col">#</th>
                                                       <th scope="col">Type</th>
                                                       <th scope="col">From</th>
                                                       <th scope="col">To</th>
                                                       <th scope="col">Price</th>
                                                       <th scope="col">Order</th>
                                                   </tr>
                                               </thead>
                                               <tbody>
                                                 <tr item-id='bbb{{$route->id}}'>
                                                   <td></td>
                                                    <td>Main Route</td>
                                                   <td><select class="form-control" name="start_station" required><option value="{{$route->start_station}}">{{$route->start->name}}</option></select></td>
                                                   <td><select class="form-control" name="end_station" required><option value="{{$route->end_station}}">{{$route->end->name}}</option></select></td>
                                                   <td><input type="text" class="form-control" name="price" value="{{$route->price}}" required></td>
                                                   <td><input type="text" class="form-control" name="order" value="{{$route->order??''}}"></td>
                                                 </tr>
                                                 @foreach($stopovers as $key=> $stoverstation)
                                                 <tr item-id='{{$stoverstation->stopover_id}}'>
                                                   <td><span class="far fa-trash-alt text-danger" id="del_route"></span></td>
                                                    <td>Stop Over</td>
                                                   <td><input type="hidden" value="{{$stoverstation->id}}" name="routes_id[]"><select class="form-control" name="routes_from[]" required><option value="{{$stoverstation->start_station}}">{{$stoverstation->start_stopover_station->name}}</option></select></td>
                                                   <td><select class="form-control" name="routes_to[]" required><option value="{{$stoverstation->end_station}}">{{$stoverstation->end_stopover_station->name}}</option></select></td>
                                                   <td><input type="text" class="form-control" name="routes_price[]" value="{{$stoverstation->price}}" required></td>
                                                   <td><input type="text" class="form-control" name="routes_order[]" value="{{$stoverstation->order}}" required></td>
                                                 </tr>
                                                 @endforeach
                                               </tbody>
                                           </table>
                                       </div>
                                     </div>
                    <div class=" col-md-3 form-group">
                        <label for="signed" class=" col-md-12 control-label">Status</label>
                        <label class="radio-inline">
                          <input type="radio" id="Active" name="status" value="1" @php echo $route->status == 1? 'checked' :  "" @endphp> Active</label>
                        </label>
                       <label class="radio-inline">
                          <input type="radio" id="Deactive" name="status" value="0" @php echo $route->status == 0? 'checked' :  "" @endphp > Deactive</label>
                       </label>
                    </div>
                    <div class=" col-md-3 form-group">
                      <label for="signed" class=" col-md-12 control-label">Enable Seat Numbers</label>
                      <label class="radio-inline">
                        <input type="radio"  name="enable_seat_number_booking" value="1" @php echo $route->enable_seat_number_booking == 1 ? 'checked' :  "" @endphp> Yes</label>
                      </label>
                     <label class="radio-inline">
                        <input type="radio"  name="enable_seat_number_booking" value="0" @php echo $route->enable_seat_number_booking == 0 ? 'checked' :  "" @endphp> No</label>
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
              @php $times =$route->departure_times()->get(); @endphp


            <!-- /.row -->
            </div>
            <!-- ./card-body -->

            <!-- /.card-footer -->
        </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->
       <!-- /.col -->
    </div>
<div class="col-md-12">
  <div class="card">
      <div class="card-header">
        <h4 class="card-title">
      <a  data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
        {{$route->start->name}}  -- {{$route->end->name}}   Service  Times
         <button type="button" class="btn btn-box-tool" data-toggle="collapse" data-target="#collapseExample" >
           <i class="fa fa-plus" aria-hidden="true"></i>
         </button>
      </a>
    </h4>
      </div>
      <div class="collapses in" id="collapseExample">
        <div class=" card-body">
          <a href="{{route('bustravel.routes.departures.create',$route->id)}}" class="btn btn-info">Create Bus Service Times</a>
          <table id="example1" class="table table-bordered table-hover table-striped dataTable" role="grid" aria-describedby="example1_info" style="width:100%">
                 <thead>
                     <tr>
                         <th scope="col">Status</th>
                         <th scope="col">Operator</th>
                         <th scope="col">Route</th>
                         <th scope="col">Price</th>
                         <th scope="col">Bus </th>
                         <th scope="col">times</th>
                         <th scope="col">Driver</th>
                         <th scope="col">Action</th>
                     </tr>
                 </thead>
                 <tbody>

                 @foreach ($times as $route_departure_time)
                     <tr>
                       <td>@if($route_departure_time->status==1)
                             <a href="#" class="btn btn-xs btn-success"> <i class="fas fa-check" aria-hidden="true"></i></a>
                           @else
                           <a href="#" class="btn btn-xs btn-danger"> <i class="fas fa-times" aria-hidden="true"></i></a>

                           @endif
                        </td>
                         <td>{{$route_departure_time->route->operator->name}}</td>
                         <td>{{$route_departure_time->route->start->code??'None'}} - {{$route_departure_time->route->end->code??'None'}}</td>
                         <td>{{number_format($route_departure_time->route->price,2)}} - {{number_format($route_departure_time->route->return_price,2)}}</td>
                         <td>{{$route_departure_time->bus->number_plate??'NONE'}} - {{$route_departure_time->bus->seating_capacity??''}}</td>
                         <td>{{$route_departure_time->departure_time}} - {{$route_departure_time->arrival_time}}</td>
                         <td>{{$route_departure_time->driver->name??'NONE'}}</td>
                         <td><a title="Edit" href="{{route('bustravel.routes.departures.edit',$route_departure_time->id)}}"><i class="fas fa-edit" aria-hidden="true"></i></a>
                             <a title="Delete" onclick="return confirm('Are you sure you want to delete this Service')" href="{{route('bustravel.routes.departures.delete',$route_departure_time->id)}}"><span style="color:tomato"><i class="fas fa-trash-alt" aria-hidden="true"></i></span></a>
                         </td>
                     </tr>

                 @endforeach
             </tbody>
             </table>

        </div>
      </div>
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
        $('.select2').select2();
          $('#add_item').on('click',function(e){
         e.preventDefault();
         //get selected option
         var uniqueid = Date.now();
         var routeid = $('#item-selector').find(":selected").data('startitemid');
         var endid = $('#item-selector2').find(":selected").data('enditemid');
         var startname = $('#item-selector').find(":selected").data('startitemname');
         var endname = $('#item-selector2').find(":selected").data('enditemname');
         var markup = "<tr item-id='"+uniqueid+"'><td><input type='checkbox' name='checkeditem[]'></td><td ><input type='hidden' value='"+routeid+"' name='stopover_startid[]'><input type='text' class='form-control' name='name1'size='4' value='"+startname+"' readonly /></td><td ><input type='hidden' value='"+endid+"' name='stopover_endid[]'><input type='text' class='form-control' name='name1'size='4' value='"+endname+"' readonly /></td><td><input type='text' class='form-control' name='stopover_price[]'size='4' value='0'  required /></td><td><input type='text' class='form-control' name='stopover_order[]'size='4' value='0'  required /></td></tr>";
         var row = '';
         row += '<tr item-id="'+uniqueid+'">';
         row += '<th><i class="far fa-trash-alt text-danger" id="del_route"></i></th>';
         row += '<th><input type="hidden" value="'+uniqueid+'" name="routes_id[]">Stop Over</th>';
         row += '<td><select class="form-control" name="routes_from[]" required><option value="'+routeid+'">'+startname+'</option></select></td>';
         row += '<td><select class="form-control" name="routes_to[]" required><option value="'+endid+'">'+endname+'</option></select></td>';
         row += '<td><input type="text" class="form-control" name="routes_price[]" value="" required></td>';
         row += '<td><input type="text" class="form-control" name="routes_order[]" value="0" required></td>';
         row += '</tr>';
           var exists =  0;
          $("table tbody").find("tr").each(function () {
              var current_stock_id = $(this).attr('item-id');
              if(current_stock_id == uniqueid)
              {
                exists = exists + 1;
              }
            });
          if(exists == 0)
          {
                  $("#routes-tbl tbody").append(row);
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
        $('table[id=routes-tbl] tbody').on('click', '#del_route', function() {
            if(confirm("Are you sure?")) {
                $(this).closest('tr').remove();
            }
        });

        })
    </script>
@stop
