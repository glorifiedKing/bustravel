@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')    


            @section('content')
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="h3 mb-3 font-weight-normal">Account</h1>
                        <ul class="form-header-custom">
                            <li><a href="{{route('login')}}">Login</a></li>
                            <li class="divider-custom"></li>
                            <li class="active">Sign Up</li>
                        </ul>
                        <form class="sign-in-up" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4">Name</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="inputEmail4" placeholder="John Doe">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inputPassword4">Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="inputPassword4" placeholder="name@email.com">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4">Password</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="inputEmail4" placeholder="*******">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inputPassword4">Confirm Password</label>
                                    <input type="password" class="form-control" name="password_confirmation" id="inputPassword4" placeholder="******">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary cust-btn-link">Sign Up</button>
                            <div class="terms-link">By signing up, you accept our <a>Terms of Use.</a></div>
                        </form>
                    </div>
                </div>
                @endsection
