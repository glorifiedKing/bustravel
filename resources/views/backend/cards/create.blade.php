@extends('bustravel::backend.layouts.app')

@section('title', 'Cards')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><small><a href="{{route('bustravel.cards')}}" class="btn btn-info">Back</a></small> Cards </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">cards</li>
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
                <h5 class="card-title">Add Card Details</h5>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                  <form role="form" action="{{route('bustravel.cards.store')}}" method="POST" >
                  {{csrf_field() }}

                  <div class="box-body">
                      <div class="row">
                        <div class="form-group col-md-6 ">
                            <label for="exampleInputEmail1">Identifier</label>
                            <input type="text"  name="identifier" value="{{old('identifier')}}" class="form-control {{ $errors->has('identifier') ? ' is-invalid' : '' }}" id="exampleInputEmail1" placeholder="Enter Identifier" >
                            @if ($errors->has('identifier'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('identifier') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-md-6 ">
                            <label for="exampleInputEmail1">Balance</label>
                            <input type="number" name="balance" value="{{old('balance') ?? 0 }}"  class="form-control {{ $errors->has('balance') ? ' is-invalid' : '' }}" id="seating-capacity" placeholder="Enter Seating Capacity">
                            @if ($errors->has('balance'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('balance') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                      <div class="form-group col-md-6 ">
                        <label for="exampleInputEmail1">name</label>
                        <input id="front-seats" type="text" name="name" value="{{old('name') ?? 2 }}"  class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" required>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    
                  <div class="form-group col-md-6 ">
                    <label for="exampleInputEmail1">Phone Number</label>
                    <input type="text" name="phone" value="{{old('phone')}}"  class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}" id="exampleInputEmail1" >
                    @if ($errors->has('phone'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-md-6 ">
                  <label for="exampleInputEmail1">National Id</label>
                  <input type="text" name="national_id" value="{{old('national_id')}}"  class="form-control {{ $errors->has('national_id') ? ' is-invalid' : '' }}" id="exampleInputEmail1" >
                  @if ($errors->has('national_id'))
                      <span class="invalid-feedback">
                          <strong>{{ $errors->first('national_id') }}</strong>
                      </span>
                  @endif
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
</div>
@stop

@section('css')

@stop

@section('js')
   @parent
   <script>
       $(function () {
         $('div.alert').not('.alert-danger').delay(5000).fadeOut(350);
        
         $('#seating-format').change(function(){
           var seating_format = $(this).val();
           var driver_side = $('.driver-side').val()
           var front_seats = parseInt($('#front-seats').val())+parseInt(1);
           var balance = parseInt($('#seating-capacity').val());
           var driver_string = "<div class='row driver-row'>";
           var last_seat_number = 0;
          // alert(front_seats);
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
           var body_seats = balance-front_seats+1;
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
                    console.log(seat_rows);
                    if(index == last_row)
                    {
                      console.log("last-row");
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
                    console.log(seat_rows);
                    if(index == last_row)
                    {
                      console.log("last-row");
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
                    console.log(seat_rows);
                    if(index == last_row)
                    {
                      console.log("last-row");
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
                    console.log(seat_rows);
                    if(index == last_row)
                    {
                      console.log("last-row");
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
                    console.log(seat_rows);
                    if(index == last_row)
                    {
                      console.log("last-row");
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
         })
       })
</script>
@stop
