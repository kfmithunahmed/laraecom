@extends('layouts.frontLayout.front_design')
@section('content')

<!--Start form-->
<section id="form" style="margin-top:20px;">
    <div class="container">
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
                <div class="login-form">
                    <h2>Update Account</h2>
                    <form id="accountForm" name="accountForm" action="{{ url('/account') }}" method="post">
                        @csrf                        
                        <input value="{{ $userDetails->name }}" type="text" name="name" id="name" placeholder="User Name">
                        <input value="{{ $userDetails->address }}" type="text" name="address" id="address" placeholder="Address">
                        <input value="{{ $userDetails->city }}" type="text" name="city" id="city" placeholder="City">
                        <input value="{{ $userDetails->state }}" type="text" name="state" id="state" placeholder="State">
                        <select name="country" id="country">
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->country_name }}" @if($country->country_name == $userDetails->country) selected @endif>{{ $country->country_name }}</option>
                            @endforeach
                        </select>
                        <input value="{{ $userDetails->pincode }}" type="text" name="pincode" id="pincode" placeholder="Pincode" style="margin-top:10px;">
                        <input value="{{ $userDetails->mobile }}" type="text" name="mobile" id="mobile" placeholder="Mobile Number">
                        <button type="submit" class="btn btn-default">Update</button>
                    </form>
                </div>
            </div>
            <div class="col-sm-1">
                <h2 class="or">OR</h2>
            </div>
            <div class="col-sm-4">
                <div class="signup-form">
                    <h2>Update Password</h2>
                    <form id="passwordForm" name="passwordForm" action="{{ url('/update-user-pwd')}}" method="post">
                        @csrf
                        <input value="{{ $userDetails->email }}" type="text" readonly>
                        <input type="password" name="current_pwd" id="current_pwd" placeholder="Current Padssword">
                        <span id="chkPwd"></span>
                        <input type="password" name="new_pwd" id="new_pwd" placeholder="New Padssword">
                        <input type="password" name="confirm_pwd" id="confirm_pwd" placeholder="Confirm Padssword">
                        <button type="submit" class="btn btn-default">Update Password</button>
                    </form>
                </div>
            </div>
            {{-- <div class="col-sm-4">
				<div class="signup-form">
					<h2>Update Password</h2>
                    <form id="passwordForm" name="passwordForm" action="{{ url('/update-user-pwd') }}" method="POST">
                        {{ @csrf_field() }}
						<input type="password" name="current_pwd" id="current_pwd" placeholder="Current Password">
						<span id="chkPwd"></span>
						<input type="password" name="new_pwd" id="new_pwd" placeholder="New Password">
						<input type="password" name="confirm_pwd" id="confirm_pwd" placeholder="Confirm Password">
						<button type="submit" class="btn btn-default">Update</button>
					</form>
				</div>
			</div> --}}
        </div>
    </div>
</section>
<!--/END form-->

@endsection