@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')        
@section('navigaton-bar')


@endsection
            @section('content')
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12 from-to-area border-b-color pad-top-bottom">
                                <form method="post" action="{{route('bustravel.homepage.search.routes')}}">
                                    @csrf
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <h4>Where to?</h4>
                                            <select name="to_station"  class="form-control select_search @error('to_station') is-invalid @enderror" id="inputEmail4" >
                                                <option value="">Select a station</option>
                                                @foreach ($bus_stations as $station)                                                    
                                                    <option value="{{$station->id}}">{{$station->name.' ['.$station->code.']'}}</option>
                                                @endforeach
                                            </select>
                                            @error('to_station')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <h4>Where from?</h4>
                                            <select name="departure_station" type="text" class="form-control select_search @error('departure_station') is-invalid @enderror" >
                                                <option value="">Select a station</option>
                                                @foreach ($bus_stations as $station)                                                    
                                                    <option value="{{$station->id}}">{{$station->name.' ['.$station->code.']'}}</option>
                                                @endforeach
                                            </select>
                                            @error('departure_station')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                
                            </div>
                            <div class="col-md-12 scheduling-area border-b-color pad-top-bottom">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h4 class="top">Scheduling</h4>
                                        
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputEmail4">Date of travel</label>
                                                    <input required="required" type="date" class="form-control @error('date_of_travel') is-invalid @enderror" name="date_of_travel" >
                                                    @error('date_of_travel')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputPassword4">Time</label>
                                                    <input required="required" type="time" class="form-control @error('time_of_travel') is-invalid @enderror" name="time_of_travel" >
                                                    @error('time_of_travel')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        
                                    </div>
                                    <div class="col-md-3 extras">
                                        <h4>Extras</h4>
                                       
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="checkbox" aria-label="Checkbox for following text input">
                                                    </div>
                                                </div>
                                                <label type="text" class="form-control" aria-label="Text input with checkbox">Adult</label>
                                                <input type="number" name="adults" class="form-control" aria-label="Text input with checkbox" value="1">
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="checkbox" aria-label="Checkbox for following text input">
                                                    </div>
                                                </div>
                                                <label type="text" class="form-control" aria-label="Text input with checkbox">Child</label>
                                                <input type="number" name="children" class="form-control" aria-label="Text input with checkbox" value=0>
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="checkbox" aria-label="Checkbox for following text input">
                                                    </div>
                                                </div>
                                                <label type="text" class="form-control" aria-label="Text input with checkbox">Luggage pieces</label>
                                                <input type="number" name="luggage" class="form-control" aria-label="Text input with checkbox" value=0>
                                            </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mb-2 btn-proceed">Find Tickets</button>
                    </form>
                    </div>
                    <div class="offset-1 col-md-3 area-extras">
                        <div>AREA FOR ANY FUTURE INCLUSIONS</div>
                    </div>
                </div>
            @endsection   
            @section('js')
            @parent
            <script>
                $(document).ready(function(){
                    $('.select_search').select2({
                        width: 'resolve' // need to override the changed default
                    });
                });
            </script>     
            @stop       
    
  

