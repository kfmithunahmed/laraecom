<?php use App\Product; ?>
<form action="{{ url('/products-filter') }}" method="post">
    @csrf
    <input name="url" value="{{ $url }}" type="hidden">
    <div class="left-sidebar">
        <!-- Start category-productsr-->
        <h2>Category</h2>                   
        <div class="panel-group category-products" id="accordian">
            <div class="panel panel-default">
                <?php //echo $categories_menu; ?>
                @foreach ($categories as $cat)
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordian" 
                            href="#{{ $cat->id }}">
                                <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                                {{ $cat->name }}
                            </a>
                        </h4>
                    </div>
                    <div id="{{ $cat->id }}" class="panel-collapse collapse">
                        <div class="panel-body">
                            <ul>
                                @foreach ($cat->categories as $subcat)
                                    <?php $productCount = Product::productCount($subcat->id); ?>
                                    @if($subcat->status==1)
                                        <li>
                                            <a href="{{ asset('products/'.$subcat->url) }}">
                                                {{ $subcat->name }}
                                            </a>({{ $productCount }})
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!--/ END category-products-->
        <!-- Start Color -->
        <h2>Colors</h2>
        <div class="panel-group">
            @if(!empty($_GET['color']))
                <?php $colorArray = explode('-', $_GET['color']); 
                    echo "<pre>"; print_r($colorArray); die();
                ?>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="Black" value="Black" type="checkbox">&nbsp;&nbsp; <span class="products-colors">Black</span>
                    </h4>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="Red" value="Red" type="checkbox">&nbsp;&nbsp; <span class="products-colors">Red</span>
                    </h4>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="Blue" value="Blue" type="checkbox">&nbsp;&nbsp; <span class="products-colors">Blue</span>
                    </h4>
                </div>
            </div>
        </div>
        <!-- End Color -->
    </div>
</form>