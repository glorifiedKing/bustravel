@extends('bustravel::frontend.layouts.app')
@section('title', 'PalmKash Bus Ticketing Homepage')
@section('page-heading','Bus Ticketing System')        
@section('navigaton-bar')


@endsection
        @section('content')
            <div class="row">
                <div class="col-md-4 order-md-2 mb-4">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Your cart</span>
                        <span class="badge badge-secondary badge-pill">{{count(session()->get('cart.items'))}}</span>
                    </h4>
                    @php
                            $cart = session()->get('cart.items');
                            $total_amount = 0;
                            $reserve_fee = 0;
                            $booking_fee = 0;
                        @endphp
                    <ul class="list-group mb-3">
                        @foreach($cart as $index=> $item)
                        @php                                
                                
                                $total_amount += $item['amount'];
                            @endphp
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">Product name</h6>
                                <small class="text-muted">Brief description</small>
                            </div>
                            <span class="text-muted">RWF {{$item['amount']}}</span>
                        </li>
                        @endforeach
                        
                        
                        <li class="list-group-item d-flex justify-content-between bg-light">
                            <div class="text-success">
                                <h6 class="my-0">Promo code</h6>
                                <small>EXAMPLECODE</small>
                            </div>
                            <span class="text-success">-0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total (RWF)</span>
                            <strong>{{$total_amount}}</strong>
                        </li>
                    </ul>
                    <form class="card p-2">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Promo code">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-secondary">Redeem</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-8 order-md-1">
                    <h4 class="mb-3">Billing address</h4>
                <form class="needs-validation" method="POST" action="{{route('bustravel.cart.pay')}}">
                    @csrf   
                    <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">First name</label>
                                <input type="text" name="first_name" class="form-control" id="firstName"  value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName">Last name</label>
                                <input name="last_name" type="text" class="form-control" id="lastName" value="{{ old('last_name') }}"  required>
                                @error('last_name')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="you@example.com" value="{{ old('email')}}">
                        @error('email')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                        </div>
                        <div class="mb-3">
                            <label for="address">Address</label>
                            <input type="text" name="address_1" class="form-control" id="address" placeholder="1234 Main St" value="{{ old('address_1') }}" required>
                            @error('address_1')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                        </div>
                        <div class="mb-3">
                            <label for="address2">
                                Address 2 <span class="text-muted">(Optional)</span>
                            </label>
                            <input type="text" name="address_2" class="form-control" id="address2" placeholder="Apartment or suite" value="{{ old('address_2') }}">
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="country">Country</label>
                                <select class="custom-select d-block w-100" name="country" id="country" required>
                                    <option value="">Choose...</option>
                                    <option>Rwanda</option>
                                </select>
                                @error('country')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state">State</label>
                                <select class="custom-select d-block w-100" name="state" id="state" required>
                                    <option value="">Choose...</option>
                                    <option value="Kigali">Kigali</option>
                                    <option value="Eastern">Eastern</option>
                                    <option value="Western">Western</option>
                                </select>
                                
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="zip">Zip</label>
                                <input type="text" class="form-control" id="zip" placeholder="" >
                                
                            </div>
                        </div>
                        <hr class="mb-4">
                        <h4 class="mb-3">Ticket Delivery</h4>
                        <div class="d-block my-3">                            
                            <div>
                                <input  name="ticketdeliveryemail" type="checkbox" value="email" checked >
                                <label for="paypal">Email</label><br>
                                @error('ticketdeliveryemail')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <input  name="ticketdeliverysms" type="checkbox" value="sms"  >
                                <label >Sms [additional cost of: {{$sms_cost ?? 5}} RWF applies]</label>
                                @error('ticketdeliverysms')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <hr class="mb-4">
                        <h4 class="mb-3">Payment Details</h4>
                        <div class="d-block my-3">                            
                            <div class="custom-control custom-radio">
                                <input id="momo" value="mobile_money" name="payment_method" type="radio" class="custom-control-input" checked required>
                                <label class="custom-control-label" for="paypal">Mobile Money</label>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6 mb-3">
                                <label for="cc-number">Phone Number</label>
                                <input name="phone_number" type="text" class="form-control" id="cc-number" placeholder="250780123123" value="{{ old('phone_number') }}" required>
                                @error('phone_number')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                       
                        <hr class="mb-4">
                        <button class="btn btn-primary btn-lg btn-block" type="submit">Pay</button>
                    </form>
                </div>
            </div>
@endsection
