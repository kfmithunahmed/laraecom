@extends('layouts.frontLayout.front_design')
@section('content')


<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
              <li><a href="#">Home</a></li>
              <li class="active">Thanks</li>
            </ol>
        </div>
    </div>
</section>

<section id="do_action">
    <div class="container">
        <div class="heading text-center">
            <h3>Your PAYPAL Order Has Been Placed</h3>
            <p>Thanks for the payment. We Will proccess your very soon</p>
            <p>Your order number is {{ Session::get('order_id') }} and total Amount paid is TAKA {{ Session::get('grand_total') }} </p>
        </div>
    </div>
</section>

@endsection

<?php
    Session::forget('grand_total');
    Session::forget('order_id');
?>