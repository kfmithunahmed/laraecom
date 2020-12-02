@extends('layouts.adminLayout.admin_design')

@section('content')
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
            <a href="#">Banners</a>
            <a href="#" class="current">Add Banner</a>
        </div>
        <h1>Banners</h1>
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
                        <h5>Add Banner</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form class="form-horizontal" method="post" action="{{ url('/admin/add-banner')}}" name="add_banner" id="add_banner" novalidate="novalidate" enctype="multipart/form-data">
                        @csrf
                        <div class="control-group">
                            <label class="control-label">Banner Image upload</label>
                            <div class="controls">
                                <input type="file" name="image" id="image" multiple="multiple" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Banner Title</label>
                            <div class="controls">
                            <input type="text" name="title" id="title">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Banner Link</label>
                            <div class="controls">
                            <input type="text" name="link" id="link">
                            </div>
                        </div>                        
                        <div class="control-group">
                            <label class="control-label">Enable</label>
                            <div class="controls">
                            <input type="checkbox" name="status" id="status" value="1">
                            </div>
                        </div>
                        <div class="form-actions">
                            <input type="submit" value="Add banner" class="btn btn-success">
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection