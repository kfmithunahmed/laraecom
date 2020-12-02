@extends('layouts.adminLayout.admin_design')

@section('content')
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
            <a href="#">Products</a>
            <a href="#" class="current">Add Product Images</a>
        </div>
        <h1>Products Alternate Images</h1>
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
                    <div class="widget-title"> <span class="icon"><i class="icon-info-sign"></i> </span>
                        <h5>Add Product Alternate Images</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form class="form-horizontal" method="post" action="{{ url('/admin/add-images/'.$productDetails->id) }}" name="add_image" id="add_image" novalidate="novalidate" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $productDetails->id }}">
                            <div class="control-group">
                                <label class="control-label">Product Name : </label>
                                <label class="control-label"><strong>{{ $productDetails->product_name}}</strong></label>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Product Code :</label>
                                <label class="control-label"><strong>{{ $productDetails->product_code}}</strong></label>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Alternate Image upload :</label>
                                <div class="controls">
                                    <input type="file" name="image[]" id="image" multiple="multiple"/>
                                </div>
                            </div>
                            <div class="form-actions">
                                <input type="submit" value="Add Images" class="btn btn-success">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                        <h5>View Images</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>Image ID</th>
                                <th>Product ID</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                                <?php echo $productsImages; ?>
                            {{-- @foreach ($productsImages as $image)
                                <tr>
                                    <td>{{ $image->id }}</td>
                                    <td>{{ $image->product_id }}</td>
                                    <td style="width:5%;"><img src="{{ asset('images/backend_images/products/small/'.$image->image)}}" alt=""></td>
                                    <td>Delete</td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection