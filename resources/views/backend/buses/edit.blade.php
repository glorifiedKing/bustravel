@extends('bustravel::backend.layouts.app')

@section('title', 'Buses')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small><a href="{{route('bustravel.buses')}}" class="btn btn-info">Back</a></small> Buses </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">buses</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
        <div class="card">
            <div class="card-header">
            <h5 class="card-title">Edit {{$bus->number_plate}}  {{$bus->operator->name??"NONE"}}</h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <form role="form" action="{{route('bustravel.buses.update',$bus->id)}}" method="POST">
              {{csrf_field() }}

              <div class="box-body">
                  <div class="row">
                        <div class="form-group col-md-6 ">
                            <label for="exampleInputEmail1">Number Plate</label>
                            <input type="text"  name="number_plate" value="{{$bus->number_plate}}" class="form-control {{ $errors->has('number_plate') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Number Plate" required>
                            @if ($errors->has('number_plate'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('number_plate') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-md-6 ">
                            <label for="exampleInputEmail1">Seating Capacity</label>
                            <input type="number" name="seating_capacity" value="{{$bus->seating_capacity}}"  class="form-control {{ $errors->has('seating_capacity') ? ' is-invalid' : '' }}" id="seating-capacity" placeholder="Enter Seating Capacity">
                            @if ($errors->has('seating_capacity'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('seating_capacity') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-md-6 ">
                          <label for="exampleInputEmail1">Driver Side</label>
                          <select class="form-control driver-side" name="driver_side">
                            <option value="left" @if($bus->driver_side == 'left') selected @endif>Left</option>
                            <option value="right" @if($bus->driver_side == 'right') selected @endif>Right</option>
                          </select>
                          @if ($errors->has('driver_side'))
                              <span class="invalid-feedback">
                                  <strong>{{ $errors->first('driver_side') }}</strong>
                              </span>
                          @endif
                      </div>
                      <div class="form-group col-md-6 ">
                        <label for="exampleInputEmail1">No on driver row</label>
                        <input id="front-seats" type="number" name="first_row_count" value="{{$bus->first_row_count}}"  class="form-control {{ $errors->has('first_row_count') ? ' is-invalid' : '' }}" required>
                        @if ($errors->has('first_row_count'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('first_row_count') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-6 ">
                      <label for="exampleInputEmail1">Seating Format(from left)</label>
                      <select id="seating-format" class="form-control" name="seating_format">
                        <option>Select</option>
                        <option value="3x2" @if($bus->seating_format == '3x2') selected @endif>3 x 2</option>
                        <option value="2x3" @if($bus->seating_format == '2x3') selected @endif>2 x 3</option>
                        <option value="2x2" @if($bus->seating_format == '2x2') selected @endif>2 x 2</option>
                        <option value="2x1" @if($bus->seating_format == '2x1') selected @endif>2 x 1</option>
                        <option value="1x2" @if($bus->seating_format == '1x2') selected @endif>1 x 2</option>
                      </select>
                      @if ($errors->has('seating_format'))
                          <span class="invalid-feedback">
                              <strong>{{ $errors->first('seating_format') }}</strong>
                          </span>
                      @endif
                  </div>
                  <div class="form-group col-md-6 ">
                    <label for="exampleInputEmail1">offset numbers(unused use ,)</label>
                    <input type="text" name="offset_seats" value="{{$bus->offset_seats}}"  class="form-control {{ $errors->has('offset_seats') ? ' is-invalid' : '' }}" id="exampleInputEmail1" >
                    @if ($errors->has('offset_seats'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('offset_seats') }}</strong>
                        </span>
                    @endif
                </div>
                        <div class="form-group col-md-12 ">
                            <label for="exampleInputEmail1">Description</label>
                            <textarea class="form-control" rows="3" placeholder="Enter Description" name="description" >{{$bus->description}}</textarea>
                        </div>
                        <div class=" col-md-3 form-group">
                            <label for="signed" class=" col-md-12 control-label">Status</label>
                            <label class="radio-inline">
                              <input type="radio" id="Active" name="status" value="1"  @php echo $bus->status == 1 ? 'checked' :  "" @endphp> Active</label>
                            </label>
                           <label class="radio-inline">
                              <input type="radio" id="Deactive" name="status" value="0"@php echo $bus->status == 0 ? 'checked' :  "" @endphp > Deactive</label>
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
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">Bus View</h5>
          </div>

        <div id="bus-view-body" class="card-body">
        <!-- driver row -->
        
          
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
          $('div.alert').not('.alert-danger').delay(5000).fadeOut(350);

          show_bus_view();

          $('#seating-format').change(function(){
            show_bus_view();
          });
          
          function show_bus_view(){
           var seating_format = $('#seating-format').val();
           var driver_side = $('.driver-side').val()
           var front_seats = parseInt($('#front-seats').val())+parseInt(1);
           var seating_capacity = parseInt($('#seating-capacity').val());
           var driver_string = "<div class='row driver-row'>";
           var last_seat_number = 0;
          // alert(front_seats);
          console.log("seating_format"+seating_format);
           $('#bus-view-body').children().remove();
           if(driver_side == 'left')
           {
             driver_string = driver_string + "<div class='col-sm-1'><p>D<img src='{{asset('bus.png')}}' alt='' class='left-driver'></p></div><div class='col-sm-8'></div>";
             
             
             for (let index = 1; index < front_seats; index++) {
               driver_string = driver_string + "<div class='col-sm-1'><p>"+index+"<img src='{{asset('bus.png')}}' alt='' </p></div>";
                last_seat_number = index;
               
             }
           }
           if(driver_side == 'right')
           {            
             
             
             for (let index = 1; index < front_seats; index++) {
               driver_string = driver_string + "<div class='col-sm-1'><p>"+index+"<img src='{{asset('bus.png')}}' alt='' </p></div>";
                last_seat_number = index;
               
             }
             driver_string = driver_string + "<div class='col-sm-8'></div><div class='col-sm-1'><p>D<img src='{{asset('bus.png')}}' alt='' class='left-driver'></p></div>";
           }
           driver_string = driver_string + "</div>";
           $('#bus-view-body').html(driver_string);

           //calculate body
           var body_seats = seating_capacity-front_seats+1;
           var body_string = "";
           switch (seating_format) {
             case "3x2":
                  var seat_rows = Math.round(body_seats/5);
                  var last_row = seat_rows -1;
                  for (let index = 0; index < seat_rows; index++) {
                   body_string = body_string + "<div class='row' >"
                    for (let i = 1; i < 4; i++) {
                      last_seat_number = last_seat_number + 1;
                      body_string = body_string + "<div class='col-sm-1'><p>"+last_seat_number+"<img src='{{asset('bus.png')}}' alt='' </p></div>";                     
                      
                    }
                    
                    if(index == last_row)
                    {
                      
                      last_seat_number = last_seat_number +1 ;
                      body_string = body_string + "<div class='col-sm-1'><p>"+last_seat_number+"<img src='{{asset('bus.png')}}' alt='' </p></div><div class='col-sm-6' ></div>";
                    }
                    else if(index != last_row)
                    {
                      body_string = body_string + "<div class='col-sm-7' ></div>";
                    }

                    for (let k = 1; k < 3; k++) {
                      last_seat_number = last_seat_number + 1;
                      body_string = body_string + "<div class='col-sm-1'><p>"+last_seat_number+"<img src='{{asset('bus.png')}}' alt='' </p></div>";                     
                      
                    }

                    body_string = body_string + "</div>";
                    
                  }
               
               break;

               case "2x3":
                  var seat_rows = Math.round(body_seats/5);
                  var last_row = seat_rows -1;
                  for (let index = 0; index < seat_rows; index++) {
                   body_string = body_string + "<div class='row' >"
                    for (let i = 1; i < 3; i++) {
                      last_seat_number = last_seat_number + 1;
                      body_string = body_string + "<div class='col-sm-1'><p>"+last_seat_number+"<img src='{{asset('bus.png')}}' alt='' </p></div>";                     
                      
                    }
                    
                    if(index == last_row)
                    {
                      
                      last_seat_number = last_seat_number +1 ;
                      body_string = body_string + "<div class='col-sm-1'><p>"+last_seat_number+"<img src='{{asset('bus.png')}}' alt='' </p></div><div class='col-sm-6' ></div>";
                    }
                    else if(index != last_row)
                    {
                      body_string = body_string + "<div class='col-sm-7' ></div>";
                    }

                    for (let k = 1; k < 4; k++) {
                      last_seat_number = last_seat_number + 1;
                      body_string = body_string + "<div class='col-sm-1'><p>"+last_seat_number+"<img src='{{asset('bus.png')}}' alt='' </p></div>";                     
                      
                    }

                    body_string = body_string + "</div>";
                    
                  }
               
               break;

               case "2x2":
                  var seat_rows = Math.round(body_seats/4);
                  var last_row = seat_rows -1;
                  for (let index = 0; index < seat_rows; index++) {
                   body_string = body_string + "<div class='row' >"
                    for (let i = 1; i < 3; i++) {
                      last_seat_number = last_seat_number + 1;
                      body_string = body_string + "<div class='col-sm-1'><p>"+last_seat_number+"<img src='{{asset('bus.png')}}' alt='' </p></div>";                     
                      
                    }
                    
                    if(index == last_row)
                    {
                      
                      last_seat_number = last_seat_number +1 ;
                      body_string = body_string + "<div class='col-sm-1'><p>"+last_seat_number+"<img src='{{asset('bus.png')}}' alt='' </p></div><div class='col-sm-7' ></div>";
                    }
                    else if(index != last_row)
                    {
                      body_string = body_string + "<div class='col-sm-8' ></div>";
                    }

                    for (let k = 1; k < 3; k++) {
                      last_seat_number = last_seat_number + 1;
                      body_string = body_string + "<div class='col-sm-1'><p>"+last_seat_number+"<img src='{{asset('bus.png')}}' alt='' </p></div>";                     
                      
                    }

                    body_string = body_string + "</div>";
                    
                  }
               
               break;

               case "2x1":
                  var seat_rows = Math.round(body_seats/3);
                  var last_row = seat_rows -1;
                  for (let index = 0; index < seat_rows; index++) {
                   body_string = body_string + "<div class='row' >"
                    for (let i = 1; i < 3; i++) {
                      last_seat_number = last_seat_number + 1;
                      body_string = body_string + "<div class='col-sm-1'><p>"+last_seat_number+"<img src='{{asset('bus.png')}}' alt='' </p></div>";                     
                      
                    }
                    
                    if(index == last_row)
                    {
                      
                      last_seat_number = last_seat_number +1 ;
                      body_string = body_string + "<div class='col-sm-1'><p>"+last_seat_number+"<img src='{{asset('bus.png')}}' alt='' </p></div><div class='col-sm-8' ></div>";
                    }
                    else if(index != last_row)
                    {
                      body_string = body_string + "<div class='col-sm-9' ></div>";
                    }

                    for (let k = 1; k < 2; k++) {
                      last_seat_number = last_seat_number + 1;
                      body_string = body_string + "<div class='col-sm-1'><p>"+last_seat_number+"<img src='{{asset('bus.png')}}' alt='' </p></div>";                     
                      
                    }

                    body_string = body_string + "</div>";
                    
                  }
               
               break;

               case "1x2":
                  var seat_rows = Math.round(body_seats/3);
                  var last_row = seat_rows -1;
                  for (let index = 0; index < seat_rows; index++) {
                   body_string = body_string + "<div class='row' >"
                    for (let i = 1; i < 2; i++) {
                      last_seat_number = last_seat_number + 1;
                      body_string = body_string + "<div class='col-sm-1'><p>"+last_seat_number+"<img src='{{asset('bus.png')}}' alt='' </p></div>";                     
                      
                    }
                    
                    if(index == last_row)
                    {
                      
                      last_seat_number = last_seat_number +1 ;
                      body_string = body_string + "<div class='col-sm-1'><p>"+last_seat_number+"<img src='{{asset('bus.png')}}' alt='' </p></div><div class='col-sm-8' ></div>";
                    }
                    else if(index != last_row)
                    {
                      body_string = body_string + "<div class='col-sm-9' ></div>";
                    }

                    for (let k = 1; k < 3; k++) {
                      last_seat_number = last_seat_number + 1;
                      body_string = body_string + "<div class='col-sm-1'><p>"+last_seat_number+"<img src='{{asset('bus.png')}}' alt='' </p></div>";                     
                      
                    }

                    body_string = body_string + "</div>";
                    
                  }
               
               break;
           
             default:
               break;
           }
           $("#bus-view-body").append(body_string);
         }
        })
    </script>
@stop
