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
                                <form>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <h4>Where to?</h4>
                                            <input type="text" class="form-control" id="inputEmail4" placeholder="k">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <h4>Where from?</h4>
                                            <input type="text" class="form-control" id="inputPassword4" placeholder="k">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12 scheduling-area border-b-color pad-top-bottom">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h4 class="top">Scheduling</h4>
                                        <form class="form-inline">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputEmail4">Email</label>
                                                    <input type="email" class="form-control" id="inputEmail4" placeholder="Email">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputPassword4">Time</label>
                                                    <input type="password" class="form-control" id="inputPassword4" placeholder="Password">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-3 extras">
                                        <h4>Extras</h4>
                                        <form>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="checkbox" aria-label="Checkbox for following text input">
                                                    </div>
                                                </div>
                                                <label type="text" class="form-control" aria-label="Text input with checkbox">Adult</label>
                                                <input type="number" class="form-control" aria-label="Text input with checkbox">
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="checkbox" aria-label="Checkbox for following text input">
                                                    </div>
                                                </div>
                                                <label type="text" class="form-control" aria-label="Text input with checkbox">Child</label>
                                                <input type="number" class="form-control" aria-label="Text input with checkbox">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mb-2 btn-proceed">Find Tickets</button>
                    </div>
                    <div class="offset-1 col-md-3 area-extras">
                        <div>AREA FOR ANY FUTURE INCLUSIONS</div>
                    </div>
                </div>
            @endsection               
    
  

