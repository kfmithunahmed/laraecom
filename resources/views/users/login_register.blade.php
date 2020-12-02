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
                <div class="login-form"><!--login form-->
                    <h2>Login to your account</h2>
                    <form id="loginForm" name="loginForm" action="{{ url('/user-login') }}" method="POST">
                        @csrf
                        <input type="email" name="email" placeholder="Email Address" />
                        <input type="password" name="password" placeholder="Password" />
                        {{-- <span>
                            <input type="checkbox" class="checkbox"> 
                            Keep me signed in
                        </span> --}}
                        <button type="submit" class="btn btn-default">Login</button><br>
                        <a href="{{ url('forgot-password')}}">Forgot Password ?</a>
                    </form>
                </div><!--/login form-->
            </div>
            <div class="col-sm-1">
                <h2 class="or">OR</h2>
            </div>
            <div class="col-sm-4">
                <div class="signup-form"><!--sign up form-->
                    <h2>New User Signup!</h2>
                    <form id="registerForm" name="registerForm" action="{{ url('/user-register') }}" method="POST">
                        @csrf
                        <input id="name" name="name" type="text" placeholder="Name"/>
                        <input id="email" name="email" type="email" placeholder="Email Address"/>
                        <input id="myPassword" name="password" type="password" placeholder="Password"/>
                        <button type="submit" class="btn btn-default">Signup</button>
                    </form>
                </div><!--/sign up form-->
            </div>
        </div>
    </div>
</section>
<!--/END form-->

@endsection