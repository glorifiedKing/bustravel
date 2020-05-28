@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')    


            @section('content')
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="h3 mb-3 font-weight-normal">Reset Your Password</h1>
                        <ul class="form-header-custom">
                            <li class="active">Reset</li>
                            <li class="divider-custom"></li>
                            <li><a href="{{route('login')}}">Login</a></li>
                        </ul>
                        <form class="sign-in-up" method="post" action="{{ route('login') }}">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4">Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="inputEmail4" placeholder="jane@doe.com">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6 pos-rel">
                                    <label for="inputPassword4">Password</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="inputPassword4" placeholder="****">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <a href="" class="forgot-pass">Forgot Password</a>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary cust-btn-link">Login</button> 
                        </form> OR
                        <form method="post" action="{{ route('login') }}">
                            @csrf
                                <input hidden value="guest@palmkash.com" name="email">
                                <input hidden value="guestofpalmkash" name="password">
                            <button type="submit" class="btn btn-primary cust-btn-link">Guest Login</button>
                        </form>
                    </div>
                </div>
                @endsection
