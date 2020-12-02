@extends('layouts.adminLayout.admin_design')

@section('content')
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
            <a href="#">Currencies</a>
            <a href="#" class="current">View Currencies</a>
        </div>
        <h1>View Currencies</h1>
        <div class="msg">
            @if (Session::has('flash_message_error'))
                <div class="alert alert-danger alert-block fade in">
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
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>View Currencies</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th style="text-align: left;">ID</th>
                                <th>Currency Code</th>
                                <th>Exchange Rate</th>
                                <th>Updated</th>
                                <th>Status</th>
                                <th width="20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($currencies as $currency)
                            <tr class="gradeX">
                                <td>{{ $currency->id }}</td>
                                <td>{{ $currency->currency_code }}</td>
                                <td>{{ $currency->exchange_rate }}</td>
                                <td>{{ $currency->updated_at->diffforHumans() }}</td>
                                <td>status</td>
                                <td class="center">
                                    <a href="{{ url('/admin/edit-currency/'.$currency->id) }}" class="btn btn-primary">Edit</a>
                                    
                                    <a href="{{ url('/admin/delete-currency/'.$currency->id) }}" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection