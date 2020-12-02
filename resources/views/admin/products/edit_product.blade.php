@extends('layouts.adminLayout.admin_design')

@section('content')
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
            <a href="#">Products</a>
            <a href="#" class="current">Edit Product</a>
        </div>
        <h1>Products</h1>
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
    <div class="container-fluid"><hr>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-info-sign"></i> </span>
                        <h5>Add Product</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form class="form-horizontal" method="post" action="{{url('/admin/edit-product/'.$productDetails->id)}}" name="edit_product" id="edit_product" novalidate="novalidate" enctype="multipart/form-data">
                        @csrf
                        <div class="control-group">
                            <label class="control-label">Under Category</label>
                            <div class="controls">
                                <select name="category_id" id="category_id" style="width: 220px;">
                                    <?php echo $categories_dropdown; ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Product Name</label>
                            <div class="controls">
                            <input type="text" name="product_name" id="product_name" value="{{$productDetails->product_name}}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Product Code</label>
                            <div class="controls">
                            <input type="text" name="product_code" id="product_code" value="{{$productDetails->product_code}}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Product Color</label>
                            <div class="controls">
                            <input type="text" name="product_color" id="product_color" value="{{$productDetails->product_color}}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Product Description</label>
                            <div class="controls">
                                <textarea name="description" id="description">
                                    {{$productDetails->description}}
                                </textarea>
                            </div>
                        </div>
                        <div class="control-group">
                                <label class="control-label">Material & Care</label>
                                <div class="controls">
                                    <textarea name="care" id="care">
                                        {{$productDetails->care}}
                                    </textarea>
                                </div>
                            </div>
                        <div class="control-group">
                            <label class="control-label">Product Price</label>
                            <div class="controls">
                            <input type="text" name="price" id="price" value="{{$productDetails->price}}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Image upload</label>
                            <div class="controls">
                                <div id="uniform-undefined">
                                    <table>
                                      <tr>
                                        <td>
                                          <input name="image" id="image" type="file">
                                          @if(!empty($productDetails->image))
                                            <input type="hidden" name="current_image" value="{{ $productDetails->image }}"> 
                                          @endif
                                        </td>
                                        <td>
                                          @if(!empty($productDetails->image))
                                            <img style="width:30px;" src="{{ asset('/images/backend_images/products/small/'.$productDetails->image) }}"> | <a href="{{ url('/admin/delete-product-image/'.$productDetails->id) }}">Delete</a>
                                          @endif
                                        </td>
                                      </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Video</label>
                            <div class="controls">
                              <div id="uniform-undefined">
                                <input name="video" id="video" type="file">
                                @if(!empty($productDetails->video))
                                  <input type="hidden" name="current_video" value="{{ $productDetails->video }}">
                                <a target="_blank" href="{{ url('videos/'.$productDetails->video) }}">View</a> |
                                <a href="{{ url('/admin/delete-product-video/'.$productDetails->id) }}">Delete</a>
                                @endif
                              </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Feature Item</label>
                            <div class="controls">
                            <input type="checkbox" name="feature_item" id="feature_item" 
                            @if ($productDetails->feature_item==1) checked @endif value="1">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Enable</label>
                            <div class="controls">
                            <input type="checkbox" name="status" id="status" 
                            @if ($productDetails->status==1) checked @endif value="1">
                            </div>
                        </div>
                        <div class="form-actions">
                            <input type="submit" value="Update Product" class="btn btn-success">
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection