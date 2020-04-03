@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')    


            @section('content')
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="h3 mb-3 font-weight-normal">Payment Processing</h1>
                        <div class="card">
                            <div class="card-body">
                                
                                <ul class="list-inline">
                                    <li class="list-inline-item">{{$notification['type']}}</li>
                                </ul>
                                
                                <h3 class="card-title">{{$notification['message']}}}</h3>
                            </div>
                        </div>
                        
                    </div>
                </div>
                @endsection
