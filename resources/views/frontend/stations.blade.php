@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')
@section('navigaton-bar')


@endsection
            @section('content')
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="h3-bottom">Stations </h3>
                        <div class="row">
                            <div class="col-md-12 ticket-card cart">
                              {!! $stations->links() !!}
                                <table class="table">
                                    <tbody>
                                      <tr>
                                      <th scope="col">Station </th>
                                      <th scope="col">Code</th>
                                      <th scope="col">Address</th>
                                      </tr>
                                      @foreach($stations as $index => $station)
                                      <tr>
                                      <td>{{$station->name}}</td>
                                      <td>{{$station->code}}</td>
                                      <td>{{$station->address}}</td>
                                      </tr>

                                      @endforeach
                                    </tbody>
                                </table>
                                {!! $stations->links() !!}
                            </div>
                        </div>
                    </div>
                    <div class="offset-1 col-md-3 area-extras">
                        <div>AREA FOR ANY FUTURE INCLUSIONS</div>
                    </div>
                </div>
@endsection
