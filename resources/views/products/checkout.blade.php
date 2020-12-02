@extends('layouts.frontLayout.front_design')
@section('content')

<!--Start Checkout form-->
<section id="form" style="margin-top:20px;">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="{{ url('/')}}">Home</a></li>
                <li class="active">Check out</li>
            </ol>
        </div><!--/breadcrums-->
        <form action="{{ url('/checkout') }}" method="post">
            @csrf
            <div class="row">
                <div class="msg">
                    @if (Session::has('flash_message_error'))
                        <div class="alert alert-danger alert-block fade in" style="background-color: #2dfd0">
                            <button type="button" class="close" data-dismiss="alert">x</button> 
                            <strong>{{ Session('flash_message_error') }}</strong>
                        </div>
                    @endif
                    @if (Session::has('flash_message_success'))
                        <div class="alert alert-success alert-block fade in">
                            <button type="button" class="close" data-dismiss="alert">x</button> 
                            <strong>{{ Session('flash_message_success') }}</strong>
                        </div>
                    @endif
                </div>
                <div class="col-sm-4 col-sm-offset-1">
                    {{-- Start Billing To --}}
                    <div class="login-form">
                        <h2>Bill To</h2>
                        <div class="form-group">
                            <input name="billing_name" id="billing_name" @if(!empty($userDetails->name)) value="{{ $userDetails->name }}" @endif type="text" placeholder="Billing Name" class="form-control">
                        </div>
                        <div class="form-group">
                            <input name="billing_address" id="billing_address" @if(!empty($userDetails->address)) value="{{ $userDetails->address }}" @endif type="text" placeholder="Billing Address" class="form-control">
                        </div>
                        <div class="form-group">
                            <input name="billing_city" id="billing_city" @if(!empty($userDetails->city)) value="{{ $userDetails->city }}" @endif type="text" placeholder="Billing City" class="form-control">
                        </div>
                        <div class="form-group">
                            <input name="billing_state" id="billing_state" @if(!empty($userDetails->state)) value="{{ $userDetails->state }}" @endif type="text" placeholder="Billing State" class="form-control">
                        </div>
                        <div class="form-group">
                            <select name="billing_country" id="billing_country" class="form-control">
                                <option value="">Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->country_name }}" @if(!empty($userDetails->state) && $country->country_name == $userDetails->country) selected @endif>{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input name="billing_pincode" id="billing_pincode" @if(!empty($userDetails->pincode)) value="{{ $userDetails->pincode }}" @endif type="text" placeholder="Billing Pincode" class="form-control">
                        </div>
                        <div class="form-group">
                            <input name="billing_mobile" id="billing_mobile" @if(!empty($userDetails->mobile)) value="{{ $userDetails->mobile }}" @endif type="text" placeholder="Billing Mobile" class="form-control">
                        </div>
                        <!-- Material unchecked -->
                        <div class="form-check">
                            <input @if(!empty($userDetails->name)) value="{{ $userDetails->name }}" @endif type="checkbox" class="form-check-input" id="billtoship"> &nbsp;
                            <label class="form-check-label" for="billtoship">Shipping Address same as Billing Address</label>
                        </div>
                    </div>
                    {{-- END Billing To --}}
                </div>
                <div class="col-sm-1">
                    <h2></h2>
                </div>
                <div class="col-sm-4">
                    {{-- Start Shipping To --}}
                    <div class="signup-form">
                        <h2>Ship To</h2>
                        <div class="form-group">
                            <input name="shipping_name" id="shipping_name" @if(!empty($shippingDetails->name)) value="{{ $shippingDetails->name }}" @endif type="text" placeholder="Shipping Name" class="form-control">
                        </div>
                        <div class="form-group">
                            <input name="shipping_address" id="shipping_address" @if(!empty($shippingDetails->address)) value="{{ $shippingDetails->address }}" @endif type="text" placeholder="Shipping Address" class="form-control">
                        </div>
                        <div class="form-group">
                            <input name="shipping_city" id="shipping_city" @if(!empty($shippingDetails->city)) value="{{ $shippingDetails->city }}" @endif type="text" placeholder="Shipping City" class="form-control">
                        </div>
                        <div class="form-group">
                            <input name="shipping_state" id="shipping_state" @if(!empty($shippingDetails->state)) value="{{ $shippingDetails->state }}" @endif type="text" placeholder="Shipping State" class="form-control">
                        </div>
                        <div class="form-group">
                            <select name="shipping_country" id="shipping_country" class="form-control">
                                <option value="">Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->country_name }}" @if(!empty($shippingDetails->name) && $country->country_name == $shippingDetails->country) selected @endif>{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input name="shipping_pincode" id="shipping_pincode" @if(!empty($shippingDetails->pincode)) value="{{ $shippingDetails->pincode }}" @endif type="text" placeholder="Shipping Pincode" class="form-control">
                        </div>
                        <div class="form-group">
                            <input name="shipping_mobile" id="shipping_mobile" @if(!empty($shippingDetails->mobile)) value="{{ $shippingDetails->mobile }}" @endif type="text" placeholder="Shipping Mobile" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success">Checkout</button>
                    </div>
                    {{-- END Shipping To --}}
                </div>                
            </div>
        </form>
    </div>
</section>
<!--/END Checkout form-->

@endsection