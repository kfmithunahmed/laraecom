@extends('layouts.adminLayout.admin_design')

@section('content')
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
            <a href="#">Products</a>
            <a href="#" class="current">View Products</a>
        </div>
        <h1>View Products</h1>
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
                        <h5>View Products</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th width="5%">Product ID</th>
                                    <th width="5%">Category ID</th>
                                    <th>Category Name</th>
                                    <th>Product Name</th>
                                    <th>Product Code</th>
                                    <th>Product Color</th>
                                    <th>Price</th>
                                    <th>Image</th>
                                    <th width="5%">Feature Item</th>
                                    <th width="180px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                <tr class="gradeX">
                                    <td class="center">{{ $product->id }}</td>
                                    <td class="center">{{ $product->category_id }}</td>
                                    <td class="center">{{ $product->category_name }}</td>
                                    <td class="left">{{ $product->product_name }}</td>
                                    <td class="center">{{ $product->product_code }}</td>
                                    <td class="center">{{ $product->product_color }}</td>
                                    <td class="center">BDT {{ $product->price }}</td>
                                    <td class="center">
                                        @if (!empty($product->image))
                                            <img width="50px" src="{{ asset('/images/backend_images/products/small/'.$product->image) }}">
                                        @endif
                                    </td>
                                    <td class="center">@if($product->feature_item == 1) Yes @else No @endif</td>
                                    <td class="center">
                                        <a href="#viewModal{{ $product->id }}" data-toggle="modal" class="btn btn-mini btn-success" title="View Product">View</a>
                                        <a href="{{ url('/admin/edit-product/'.$product->id) }}" class="btn btn-mini btn-primary" title="Edit Product">Edit</a>
                                        <a href="{{ url('/admin/add-attributes/'.$product->id) }}" class="btn btn-mini btn-success" title="Add attributes">Add</a>
                                        <a href="{{ url('/admin/add-images/'.$product->id) }}" class="btn btn-mini btn-info" title="Add Images">Add</a>
                                        <a id="delProduct" rel="{{ $product->id }}" rel1="delete-product" href="javascript:" class="btn btn-mini btn-danger deleteRecord" title="Delete Product">Del</a>
                                    </td>
                                </tr>

                                {{-- Start Product View Modal --}}
                                <div id="viewModal{{ $product->id }}" class="modal hide">
                                    <div class="modal-header">
                                        <button data-dismiss="modal" class="close" type="button">×</button>
                                        <h3>{{ $product->product_name }} Full Details</h3>
                                    </div>
                                    <div class="modal-body">
                                        <p>Product ID : {{ $product->id }}</p>
                                        <p>Category ID : {{ $product->category_id }}</p>
                                        <p>Product Code : {{ $product->product_code }}</p>
                                        <p>Product Color : {{ $product->product_color }}</p>
                                        <p>Price : BDT {{ $product->price }} /=</p>
                                        <p>Fabric : </p>
                                        <p>Material : </p>
                                        <p>Description : {{ $product->description }}</p>
                                        <p>Image : 
                                            <img src="{{ asset('/images/backend_images/products/small/'.$product->image) }}">
                                        </p>
                                    </div>
                                </div>
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
