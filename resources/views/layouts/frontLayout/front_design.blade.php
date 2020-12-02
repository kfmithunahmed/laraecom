<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@if(!empty($meta_title)){{ $meta_title }} @else Home | E-Shopper @endif</title>
    @if(!empty($meta_description))<meta name="description" content="{{ $meta_description }}">@endif
    
    @if(!empty($meta_keywords))<meta name="keywords" content="{{ $meta_keywords }}">@endif

    <link href="{{ asset('css/frontend_css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/frontend_css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/frontend_css/prettyPhoto.css') }}" rel="stylesheet">
    <link href="{{ asset('css/frontend_css/price-range.css') }}" rel="stylesheet">
    <link href="{{ asset('css/frontend_css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('css/frontend_css/easyzoom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/frontend_css/passtrength.css') }}" rel="stylesheet">
	<link href="{{ asset('css/frontend_css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/frontend_css/responsive.css') }}" rel="stylesheet">
    
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="{{ asset('images/frontend_images/ico/favicon.ico') }}">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('images/frontend_images/ico/apple-touch-icon-144-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('images/frontend_images/ico/apple-touch-icon-114-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('images/frontend_images/ico/apple-touch-icon-72-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('images/frontend_images/ico/apple-touch-icon-57-precomposed.png') }}">

    <!—- ShareThis BEGIN -—>
    {{-- <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=5fc504e0f193bb00129e2bfa&product=sticky-share-buttons" async="async"></script> --}}
    <script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=5cc08788f3971d0012e248e5&product=inline-share-buttons' async='async'></script>
    <!—- ShareThis END -—>
</head><!--/head-->

<body>
    {{-- Start Front Header Part --}}
    @include('layouts.frontLayout.front_header')
    {{-- END Front Header Part --}}
	
	@yield('content')
	
	{{-- Start Front Footer Part --}}
    @include('layouts.frontLayout.front_footer')
    {{-- END Front Footer Part --}}
	

    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
    <script src="{{ asset('js/frontend_js/jquery.js') }}"></script>
	<script src="{{ asset('js/frontend_js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/frontend_js/popper.min.js') }}"></script>
	<script src="{{ asset('js/frontend_js/jquery.scrollUp.min.js') }}"></script>
	<script src="{{ asset('js/frontend_js/price-range.js') }}"></script>
    <script src="{{ asset('js/frontend_js/jquery.prettyPhoto.js') }}"></script>
    <script src="{{ asset('js/frontend_js/easyzoom.js') }}"></script>
    <script src="{{ asset('js/frontend_js/jquery.validate.js') }}"></script>
    <script src="{{ asset('js/frontend_js/jquery.passtrength.js') }}"></script>
    <script src="{{ asset('js/frontend_js/main.js') }}"></script>

    <script>
        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

    <!-- Go to www.addthis.com/dashboard to customize your tools -->
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5fc4f27ebd9b17db"></script>
    {{-- <script src="{{ asset('js/frontend_js/addthis_widget.js') }}"></script> --}}



</body>
</html>