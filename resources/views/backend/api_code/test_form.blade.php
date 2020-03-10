@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus MOMO TEST')
@section('page-heading','Bus Ticketing System')    


            @section('content')
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="h3 mb-3 font-weight-normal">Momo Tests</h1>
                        <ul class="form-header-custom">
                          
                        </ul>
                        <form class="sign-in-up" method="post" action="{{ route('bustravel.api.debit') }}">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4">From Mtn number</label>
                                    <input type="text" name="from" class="form-control @error('from') is-invalid @enderror" id="inputEmail4" >
                                    @error('from')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6 pos-rel">
                                    <label for="inputPassword4">To Mtn Number</label>
                                    <input type="text" name="to" class="form-control @error('to') is-invalid @enderror" id="inputPassword4" >
                                    @error('to')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    
                                </div>
                                <div class="form-group col-md-6 pos-rel">
                                    <label for="inputPassword4">Amount</label>
                                    <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" id="inputPassword8" >
                                    @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary cust-btn-link">Login</button>
                        </form>
                    </div>
                </div>
                @endsection
