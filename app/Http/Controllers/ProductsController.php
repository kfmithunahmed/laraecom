<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;
use Session;
use Image;
use App\Category;
use App\Country;
use App\Product;
use App\Coupon;
use App\DeliveryAddress;
use App\ProductsAttribute;
use App\ProductsImages;
use App\User;
use DB;
use App\Order;
use App\OrdersProduct;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class ProductsController extends Controller
{
    public function addProduct(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            if (empty($data['category_id'])) {
                return redirect()->back()->with('flash_message_error','Under Category is missing');
            }

            $product = new Product;
            $product->category_id = $data['category_id'];
            $product->product_name = $data['product_name'];
            $product->product_code = $data['product_code'];
            $product->product_color = $data['product_color'];

            if (!empty($data['description'])) {
                $product->description = $data['description'];
            }else{
                $product->description = '';
            }

            if (!empty($data['care'])) {
                $product->care = $data['care'];
            }else{
                $product->care = '';
            }

            if (empty($data['status'])) {
                $status = 0;
            }else {
                $status = 1;
            }

            if (empty($data['feature_item'])) {
                $feature_item = 0;
            }else {
                $feature_item = 1;
            }
            
            $product->price = $data['price'];

            // Upload Image
            if ($request->hasFile('image')) {
                $image_tmp = Input::file('image');
                if ($image_tmp->isValid()) {
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;
                    // Resize Image
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);

                    // Store Image name in products table
                    $product->image = $filename;                    
                }
            }

            // Start Upload Video
            if ($request->hasFile('video')) {
                $video_tmp = Input::file('video');
                $video_name = $video_tmp->getClientOriginalName();
                $video_path = 'videos/';
                $video_tmp->move($video_path, $video_name);
                $product->video = $video_name;
            }
            // END Upload Video

            $product->feature_item = $feature_item;
            $product->status = $status;
            $product->save();
            // return redirect()->back()->with('flash_message_success','Product has been Added Successfully');
            return redirect('/admin/view-products')->with('flash_message_success','Product has been Added Successfully');
        }

        // Start Category Sub Category Dropdown
        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option value='' selected disabled>Select Category</option>";
        foreach ($categories as $cat){
            $categories_dropdown .= "<option value='".$cat->id."'>".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach ($sub_categories as $sub_cat) {
                $categories_dropdown .= "<option value='".$sub_cat->id."'>&nbsp;---&nbsp;".$sub_cat->name."</option>";
            }
        }
        // END Category Sub Category Dropdown

        return view('admin.products.add_product')->with(compact('categories_dropdown'));
    }

    public function editProduct(Request $request, $id=null)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            // Upload Image
            if ($request->hasFile('image')) {
                $image_tmp = Input::file('image');
                if ($image_tmp->isValid()) {
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;
                    // Resize Image
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                }
            }else if(!empty($data['current_image'])){
                $filename = $data['current_image'];
            }else{
                $filename = "";
            }

            // Start Upload Video
            if ($request->hasFile('video')) {
                $video_tmp = Input::file('video');
                $video_name = $video_tmp->getClientOriginalName();
                $video_path = 'videos/';
                $video_tmp->move($video_path, $video_name);
                $videoName = $video_name;
            }else if(!empty($data['current_video'])){
                $videoName = $data['current_video'];
            }else{
                $videoName = "";
            }
            // END Upload Video

            if (empty($data['description'])) {
                $data['description'] = '';
            }

            if (empty($data['care'])) {
                $data['care'] = '';
            }

            if (empty($data['status'])) {
                $status = 0;
            }else {
                $status = 1;
            }

            if (empty($data['feature_item'])) {
                $feature_item = 0;
            }else {
                $feature_item = 1;
            }
            
            Product::where(['id'=>$id])->update([
                'category_id'   => $data['category_id'],
                'product_name'  => $data['product_name'],
                'product_code'  => $data['product_code'],
                'product_color' => $data['product_color'],
                'description'   => $data['description'],
                'care'          => $data['care'],
                'price'         => $data['price'],
                'image'         => $filename,
                'video'         => $videoName,
                'status'        => $status,
                'feature_item'  => $feature_item
            ]);
            return redirect()->back()->with('flash_message_success','Product has been Updated Successfully');
        }
        // Get Product Details
        $productDetails = Product::where(['id'=>$id])->first();

        // Category Sub Category Dropdown Start
        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option value='' selected disabled>Se lect Category</option>";
        foreach ($categories as $cat){
            if ($cat->id==$productDetails->category_id) {
                $selected = "selected";
            }else {
                $selected = "";
            }
            $categories_dropdown .= "<option value='".$cat->id."' ".$selected.">".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach ($sub_categories as $sub_cat) {
                if ($sub_cat->id==$productDetails->category_id) {
                    $selected = "selected";
                }else {
                    $selected = "";
                }
                $categories_dropdown .= "<option value='".$sub_cat->id."' ".$selected.">&nbsp;---&nbsp;".$sub_cat->name."</option>";
            }
        }
        // Category Sub Category Dropdown END

        return view('admin.products.edit_product', compact('productDetails', 'categories_dropdown','categories'));
    }

    public function viewProducts()
    {
        $products = Product::orderby('id','DESC')->get();
        // $products = json_decode(json_encode($products));
        foreach ($products as $key => $val) {
            $category_name = Category::where(['id'=>$val->category_id])->first();
            $products[$key]->category_name = $category_name->name;
        }
        // echo "<pre>"; print_r($products); die;
        return view('admin.products.view_products')->with(compact('products'));
    }

    public function deleteProductImage($id=null)
    {
        // Get Product Image Name
        $productImage = Product::where(['id'=>$id])->first();

        // Get Product Image Paths
        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';

        // Delete Large Image if not exists in folder
        if (file_exists($large_image_path.$productImage->image)) {
            unlink($large_image_path.$productImage->image);
        }

        // Delete Medium Image if not exists in folder
        if (file_exists($medium_image_path.$productImage->image)) {
            unlink($medium_image_path.$productImage->image);
        }

        // Delete Small Image if not exists in folder
        if (file_exists($small_image_path.$productImage->image)) {
            unlink($small_image_path.$productImage->image);
        }

        // Delete Image from Product table
        Product::where(['id'=>$id])->update(['image'=>'']);
        return redirect()->back()->with('flash_message_success', 'Product Image has been deleted Successfully');
    }

    public function deleteProductVideo($id)
    {
        // Get Video Name
        $productVideo = Product::select('video')->where('id',$id)->first();

        // Get Video Path
        $video_path = 'video/';

        // Delete Video if exists in videos folder
        if (file_exists($video_path.$productVideo->video)) {
            unlink($video_path.$productVideo->video);
        }

        // Delete Video Form Products Table
        Product::where('id',$id)->update(['video'=>'']);

        return redirect()->back()->with('flash_message_success','Product Video has benn deleted Successfully');
    }

    public function deleteAltImage($id=null)
    {
        // Get Product Image Name
        $productImage = ProductsImages::where(['id'=>$id])->first();
        // Get Product Image Paths
        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';
        // Delete Large Image if not exists in folder
        if (file_exists($large_image_path.$productImage->image)) {
            unlink($large_image_path.$productImage->image);
        }
        // Delete Medium Image if not exists in folder
        if (file_exists($medium_image_path.$productImage->image)) {
            unlink($medium_image_path.$productImage->image);
        }
        // Delete Small Image if not exists in folder
        if (file_exists($small_image_path.$productImage->image)) {
            unlink($small_image_path.$productImage->image);
        }
        // Delete Image from Product table
        ProductsImages::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success', 'Product Alternate Image(s) has been deleted Successfully !!!');
    }

    public function deleteProduct($id = null)
    {
        Product::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success', 'Product has been deleted Successfully');
    }

    public function addAttributes(Request $request, $id=null)
    {
        $productDetails = Product::with('attributes')->where(['id'=>$id])->first();
        // $productDetails = json_decode(json_encode($productDetails));
        // echo "<pre>"; print_r($productDetails); die;
        
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            foreach ($data['sku'] as $key => $val) {
                if (!empty($val)) {
                    // Prevent duplicate SKU Check
                    $attrCountSKU = ProductsAttribute::where('sku',$val)->count();
                    if ($attrCountSKU > 0) {
                        return redirect('admin/add-attributes/'.$id)->with('flash_message_error',
                        'SKU already exists ! Please add another SKU.');
                    }

                    // Prevent duplicate Size Check
                    $attrCountSizes = ProductsAttribute::where(['product_id'=>$id, 'size'=>$data['size'][$key]])->count();
                    if ($attrCountSizes > 0) {
                        return redirect('admin/add-attributes/'.$id)->with('flash_message_error', '"'.$data['size'][$key]. '" Size already exists ! Please add another Size.');
                    }

                    $attribute = new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $val;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->save();
                }                
            }
            return redirect('admin/add-attributes/'.$id)->with('flash_message_success','Product Attributes Added Successfully ! ! !');
        }
        return view('admin.products.add_attributes', compact('productDetails'));
    }

    public function editAttributes(Request $request, $id=null)
    {        
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            foreach ($data['idAttr'] as $key => $attr) {
                ProductsAttribute::where(['id'=>$data['idAttr'][$key]])->update(['price'=>$data['price'][$key],'stock'=>$data['stock'][$key]]);
            }
            return redirect()->back()->with('flash_message_success','Products Attributes has been updated successfully !!!');
        }
    }

    public function addImages(Request $request, $id=null)
    { 
        $productDetails = Product::with('attributes')->where(['id'=>$id])->first();
        if ($request->isMethod('post')) {
            $data = $request->all();
            if($request->hasfile('image')){
                $files = $request->file('image');
                foreach ($files as $file) {
                    // Upload Image after Resize
                    $image = New ProductsImages();
                    $extension = $file->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;
                    Image::make($file)->save($large_image_path);
                    Image::make($file)->resize(600,600)->save($medium_image_path);
                    Image::make($file)->resize(300,300)->save($small_image_path);
                    $image->image = $filename;
                    $image->product_id = $data['product_id'];
                    $image->save();
                }  
            }
            return redirect('admin/add-images/'.$id)->with('flash_message_success','Product Images has been added successfu');
        }

        $productsImg = ProductsImages::where(['product_id'=>$id])->get();
        // $productsImg = json_decode(json_encode($productsImg));
        // echo "<pre>"; print_r($productsImages); die;

        $productsImages = "";
        foreach ($productsImg as $img) {
                $productsImages .= "<tr>
                <td>".$img->id."</td>
                <td>".$img->product_id."</td>
                <td width='10%'><img src='/images/backend_images/products/small/$img->image'></td>
                <td>
                    <a rel='$img->id' rel1='delete-alt-image' href='javascript:' class='btn btn-danger deleteRecord' title='Delete Product Image'>Delete</a>
                </td>
            </tr>";
        }

        return view('admin.products.add_images', compact('productDetails','productsImages'));
    }

    public function deleteAttribute($id=null)
    {
        ProductsAttribute::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success', 'Attributes has been Deleted Successfully');
    }

    public function products($url=null)
    {
        // Show 404 page if Category URL does not exist
        $countCategory = Category::where(['url' => $url, 'status' => 1])->count();
        // echo $countCategory; die;
        if ($countCategory == 0) {
            abort(404);
        }
        // Get all Categories and Sub Categories
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();
        $categoryDetails = Category::where(['url' => $url])->first();
        if ($categoryDetails->parent_id == 0) {
            // if url is main category url
            $subCategories = Category::where(['parent_id' => $categoryDetails->id])->get();
            // $cat_ids = "";
            foreach ($subCategories as $subcat) {
                // if($key==1) $cat_ids .= ",";
                // $cat_ids .= trim($subcat->id);

                $cat_ids [] = $subcat->id;
            }
            // print_r($cat_ids); die;
            // echo $cat_ids; die;
            $productsAll = Product::whereIn('category_id', $cat_ids)->where('status','1')->orderby('id','Desc')->paginate(6);
            // $productsAll = json_decode(json_encode($productsAll));
            // echo "<pre>"; print_r($productsAll); die;
        }else {
            // if url is sub category url
            $productsAll = Product::where(['category_id' => $categoryDetails->id])->where('status','1')->orderby('id','Desc')->paginate(6);
        }
        $meta_title = $categoryDetails->meta_title;
        $meta_description = $categoryDetails->meta_description;
        $meta_keywords = $categoryDetails->meta_keywords;
        return view('products.listing')->with(compact('categoryDetails','productsAll','categories','meta_title','meta_description','meta_keywords','url'));
    }

    public function filter(Request $request)
    {
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;

        $colorUrl = "";
        if (!empty($data['colorFilter'])) {
            foreach ($data['colorFilter'] as $color) {
                if (empty($colorUrl)) {
                    $colorUrl = "&color=".$color;
                }else{
                    $colorUrl .= "-".$color;
                }
            }
        }

        $finalUrl = "products/".$data['url']."?".$colorUrl;
        return redirect::to($finalUrl);
    }

    public function searchProducts(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $categories = Category::with('categories')->where(['parent_id' => 0])->get();
            $search_product = $data['product'];
            // $productsAll = Product::where('product_name','like','%'.$search_product.'%')->orwhere('product_code',$search_product)->where('status',1)->get();
            $productsAll = Product::where(function($query) use($search_product){
                $query->where('product_name','like','%'.$search_product.'%')
                ->orwhere('product_code','like','%'.$search_product.'%')
                ->orwhere('description','like','%'.$search_product.'%')
                ->orwhere('product_color','like','%'.$search_product.'%');
            })->where('status',1)->get();
            return view('products.listing')->with(compact('categories','productsAll','search_product'));
        }
    }

    public function product($id = null)
    {
        // Show 404 page if Product is disabled
        $productCount = Product::where(['id'=>$id,'status'=>1])->count();
        if($productCount==0){
            abort(404);
        }

        // Get Product Details
        $productDetails = Product::with('attributes')->where('id',$id)->first();
        $productDetails = json_decode(json_encode($productDetails));
        // echo "<pre>"; print_r($productDetails); die;
        // dump($productDetails);
        // dd($productDetails);
        $relatedProducts = Product::where('id','!=',$id)->where(['category_id'=>$productDetails->category_id])->get();
        // $relatedProducts = json_decode(json_encode($relatedProducts));
        // foreach ($relatedProducts->chunk(3) as $key => $chunk) {
        //     foreach ($chunk as $item) {
        //         echo $item; echo "<br>";
        //     }
        //     echo "<br><br><br>";
        // }
        // die;
        // echo "<pre>"; print_r($relatedProducts); die;

        // Get all Categories and Sub Categories
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();

        // Get Product Alternate Image
        $productAltImages = ProductsImages::where('product_id',$id)->get();
        // $productAltImages = json_decode(json_encode($productAltImages));
        // echo "<pre>"; print_r($productAltImages); die;

        $total_stock = ProductsAttribute::where('product_id',$id)->sum('stock');
        $meta_title = $productDetails->product_name;
        $meta_description = $productDetails->description;
        $meta_keywords = $productDetails->product_name;
        return view('products.detail', compact('productDetails','categories','productAltImages','total_stock','relatedProducts','meta_title','meta_description','meta_keywords'));
    }

    public function getProductPrice(Request $request)
    {
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        $proArr = explode("-",$data['idSize']);
        // echo $proArr[0]; echo $proArr[1]; die;
        $proAttr = ProductsAttribute::where(['product_id' => $proArr[0],'size' => $proArr[1]])->first();
        $getCurrencyRates = Product::getCurrencyRates($proAttr->price);
        echo $proAttr->price."-".$getCurrencyRates['USD_Rate']."-".$getCurrencyRates['GBP_Rate']."-".$getCurrencyRates['EUR_Rate'];
        echo "#";
        echo $proAttr->stock;
    }

    public function addtocart(Request $request)
    {
        Session::forget('CouponAmount');
        Session::forget('CouponCode');

        $data = $request->all();
        // echo "<pre>"; print_r($data); die;

        // Check Product Stock is available or not
        $product_size = explode("-",$data['size']);
        $getProductStock = ProductsAttribute::where(['product_id'=>$data['product_id'], 'size'=>$product_size[1]])->first();
        if ($getProductStock->stock < $data['quantity']) {
            return redirect()->back()->with('flash_message_error', 'Required Quantity is not available!');
        }

        if (empty(Auth::user()->email)) {
            $data['user_email'] = '';
        }else {
            $data['user_email'] = Auth::user()->email;
        }

        $session_id = Session::get('session_id');
        if (empty($session_id)) {
            $session_id = str_random(40);
            Session::put('session_id',$session_id);
        }

        $sizeIDArr = explode("-",$data['size']);
        $product_size = $sizeIDArr[1];

        if (empty(Auth::check())) {
            $countProducts = DB::table('cart')->where([
                'product_id' => $data['product_id'],
                'product_color' => $data['product_color'],
                'size' => $product_size,
                'session_id' => $session_id
            ])->count();
            if ($countProducts > 0) {
                return redirect()->back()->with('flash_message_error','Product already exists in Cart !!!');
            }
        }else {
            $countProducts = DB::table('cart')->where([
                'product_id' => $data['product_id'],
                'product_color' => $data['product_color'],
                'size' => $product_size,
                'user_email' => Auth::User()->email
            ])->count();
            if ($countProducts > 0) {
                return redirect()->back()->with('flash_message_error','Product already exists in Cart !!!');
            }
        }
        

        $getSKU = ProductsAttribute::select('sku')->where(['product_id' => $data['product_id'],'size' => $product_size])->first();
        DB::table('cart')->insert([
            'product_id' => $data['product_id'],
            'product_name' => $data['product_name'],
            'product_code' => $getSKU->sku,
            'product_color' => $data['product_color'],
            'price' => $data['price'],
            'size' => $sizeIDArr[1],
            'quantity' => $data['quantity'],
            'user_email' => $data['user_email'],
            'session_id' => $session_id,
        ]);

        return redirect('cart')->with('flash_message_success','Product Has been Add to Cart !!!');
    }

    public function cart()
    {
        if(Auth::check()){
            $user_email = Auth::user()->email;
            $userCart = DB::table('cart')->where(['user_email' => $user_email])->get();
        }else {
            $session_id = Session::get('session_id');
            $userCart = DB::table('cart')->where(['session_id' => $session_id])->get();
        }
        
        foreach($userCart as $key => $product){
            $productDetails = Product::where('id',$product->product_id)->first();
            $userCart[$key]->image = $productDetails->image;
        }
        // echo "<pre>"; print_r($userCart);
        $meta_title = "Shopping Cart - Ecommerce Website";
        $meta_description = "View Shopping Cart of E-com Website";
        $meta_keywords = "Shopping Cart - e-com Website";
        return view('products.cart')->with(compact('userCart','meta_title','meta_description','meta_keywords'));
    }

    public function deleteCartProduct($id = null)
    {
        Session::forget('CouponAmount');
        Session::forget('CouponCode');
        DB::table('cart')->where('id',$id)->delete();
        return redirect('cart')->with('flash_message_success','Product has been deleted from Cart !');
    }

    public function updateCartQuantity($id = null, $quantity = null)
    {
        Session::forget('CouponAmount');
        Session::forget('CouponCode');

        $getCartDetails = DB::table('cart')->where('id',$id)->first();
        $getAttributeStock = ProductsAttribute::where('sku', $getCartDetails->product_code)->first();
        // echo $getAttributeStock->stock;
        // echo "--";
        $updated_quantity = $getCartDetails->quantity+$quantity;
        if ($getAttributeStock->stock >= $updated_quantity) {
            DB::table('cart')->where('id',$id)->increment('quantity',$quantity);
            return redirect('cart')->with('flash_message_success','Product Quantity has been Updated Succussfully !');   
        }else {
            return redirect('cart')->with('flash_message_error','Required Product Quantity is not Available !');
        }
    }

    public function applyCoupon(Request $request)
    {
        Session::forget('CouponAmount');
        Session::forget('CouponCode');

        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        $couponCount = Coupon::where('coupon_code', $data['coupon_code'])->count();
        if ($couponCount == 0) {
            return redirect()->back()->with('flash_message_error','This Coupon does not exists !');
        }else{
            // with perform other check like Active/Inactive, Expiry Date ...
            
            // Get Coupon Details
            $couponDetails = Coupon::where('coupon_code', $data['coupon_code'])->first();

            // If coupon is Inactive
            if ($couponDetails->status==0) {
                return redirect()->back()->with('flash_message_error','This Coupon is not Active !');
            }

            // If coupon is Expired
            $expiry_date = $couponDetails->expiry_date;
            $current_date = date('Y-m-d');
            if ($expiry_date < $current_date) {
                return redirect()->back()->with('flash_message_error','This Coupon is Expired !');
            }

            // Coupon is Valid for Discount

            // Get Cart Total Amount
            $session_id = Session::get('session_id');
                        
            if(Auth::check()){
                $user_email = Auth::user()->email;
                $userCart = DB::table('cart')->where(['user_email' => $user_email])->get();
            }else {
                $session_id = Session::get('session_id');
                $userCart = DB::table('cart')->where(['session_id' => $session_id])->get();
            }

            $total_amount = 0;
            foreach($userCart as $item){
                $total_amount = $total_amount + ($item->price * $item->quantity);
            }

            // Check if amount type is Fixed or Percentage
            if ($couponDetails->amount_type == "Fixed") {
                $couponAmount = $couponDetails->amount;
            }else {
                // echo $total_amount; die;
                $couponAmount = $total_amount * ($couponDetails->amount/100);
            }

            // Add Coupon Code & Amount in Session
            Session::put('CouponAmount', $couponAmount);
            Session::put('CouponCode', $data['coupon_code']);

            return redirect()->back()->with('flash_message_success','Coupon Code Successfully Applied. You are availing Discount !');
        }
    }

    public function checkout(Request $request)
    {
        $user_id = Auth::user()->id;
        $user_email = Auth::user()->email;
        $userDetails = User::find($user_id);
        $countries = Country::get();

        // Check if Shipping Address exists
        $shippingCount = DeliveryAddress::where('user_id',$user_id)->count();
        $shippingDetails = array();
        if ($shippingCount > 0) {
            $shippingDetails = DeliveryAddress::where('user_id',$user_id)->first();
        }

        // Update cart table with user email
        $session_id = Session::get('session_id');
        DB::table('cart')->where(['session_id' => $session_id])->update(['user_email'=>$user_email]);

        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            // Return to Checkout page if any of the field is empty
            if (empty($data['billing_name']) ||
                empty($data['billing_address']) ||
                empty($data['billing_city']) ||
                empty($data['billing_state']) ||
                empty($data['billing_country']) ||
                empty($data['billing_pincode']) ||
                empty($data['billing_mobile']) ||
                empty($data['shipping_name']) ||
                empty($data['shipping_address']) ||
                empty($data['shipping_city']) ||
                empty($data['shipping_state']) ||
                empty($data['shipping_country']) ||
                empty($data['shipping_pincode']) ||
                empty($data['shipping_mobile']) ) {
                return redirect()->back()->with('flash_message_error', 'Please fill all fields to Checkout !');
            }

            // Update User details
            User::where('id',$user_id)->update([
                'name'=>$data['billing_name'],
                'address'=>$data['billing_address'],
                'city'=>$data['billing_city'],
                'state'=>$data['billing_state'],
                'country'=>$data['billing_country'],
                'pincode'=>$data['billing_pincode'],
                'mobile'=>$data['billing_mobile']
            ]);

            if ($shippingCount > 0) {
                // Update Shipping Address
                DeliveryAddress::where('user_id',$user_id)->update([
                    'name'=>$data['shipping_name'],
                    'address'=>$data['shipping_address'],
                    'city'=>$data['shipping_city'],
                    'state'=>$data['shipping_state'],
                    'country'=>$data['shipping_country'],
                    'pincode'=>$data['shipping_pincode'],
                    'mobile'=>$data['shipping_mobile']
                ]);
            }else {
                // Add New Shipping Address
                $shipping = new DeliveryAddress;
                $shipping->user_id = $user_id;
                $shipping->user_email = $user_email;
                $shipping->name = $data['shipping_name'];
                $shipping->address = $data['shipping_address'];
                $shipping->city = $data['shipping_city'];
                $shipping->state = $data['shipping_state'];
                $shipping->pincode = $data['shipping_pincode'];
                $shipping->country = $data['shipping_country'];
                $shipping->mobile = $data['shipping_mobile'];
                $shipping->save();
            }

            // Get Shipping Pincode          
            $pincodeCount = DB::table('pincodes')->where('pincode',$data['shipping_pincode'])->count();
            if ($pincodeCount == 0) {
                return redirect()->back()->with('flash_message_error','Your location is not available for delivery. Please enter another location.');
            }

            return redirect()->action('ProductsController@orderReview');
        }
        $meta_title = "Checkout - E-com Website";
        return view('products.checkout')->with(compact('userDetails','countries','shippingCount','shippingDetails','meta_title'));
    }

    public function orderReview()
    {
        $user_id = Auth::user()->id;
        $user_email = Auth::user()->email;
        $userDetails = User::where('id',$user_id)->first();
        $shippingDetails = DeliveryAddress::where('user_id',$user_id)->first();
        $shippingDetails = json_decode(json_encode($shippingDetails));
        $userCart = DB::table('cart')->where(['user_email' => $user_email])->get();
        foreach ($userCart as $key => $product) {
            $productDetails = Product::where('id',$product->product_id)->first();
            $userCart[$key]->image = $productDetails->image;
        }
        // echo "<pre>"; print_r($userCart); die;
        $codpincodeCount = DB::table('cod_pincodes')->where('pincode',$shippingDetails->pincode)->count();
        $prepaidpincodeCount = DB::table('prepaid_pincodes')->where('pincode',$shippingDetails->pincode)->count();
        $meta_title = "Order Review - E-com Website";
        return view('products.order_review',compact('userDetails', 'shippingDetails', 'userCart','meta_title','codpincodeCount','prepaidpincodeCount'));
    }

    public function placeOrder(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $user_id = Auth::user()->id;
            $user_email = Auth::user()->email;

            // Get Shipping Address of User
            $shippingDetails = DeliveryAddress::where(['user_email' => $user_email])->first();
            
            $pincodeCount = DB::table('pincodes')->where('pincode',$shippingDetails->pincode)->count();
            if ($pincodeCount == 0) {
                return redirect()->back()->with('flash_message_error','Your location is not available for delivery. Please enter another location.');
            }

            if (empty(Session::get('CouponCode'))) {
                $coupon_code = '';
            }else {
                $coupon_code = Session::get('CouponCode');
            }

            if (empty(Session::get('CouponAmount'))) {
                $coupon_amount = '';
            }else {
                $coupon_amount = Session::get('CouponAmount');
            }

            $order = new Order;
            $order->user_id = $user_id;
            $order->user_email = $user_email;
            $order->name = $shippingDetails->name;
            $order->address = $shippingDetails->address;
            $order->city = $shippingDetails->city;
            $order->state = $shippingDetails->state;
            $order->pincode = $shippingDetails->pincode;
            $order->country = $shippingDetails->country;
            $order->mobile = $shippingDetails->mobile;
            $order->coupon_code = $coupon_code;
            $order->coupon_amount = $coupon_amount;
            $order->order_status = "New";
            $order->payment_method = $data['payment_method'];
            $order->grand_total = $data['grand_total'];
            $order->save();

            $order_id = DB::getPdo()->lastInsertId();

            $cartProducts = DB::table('cart')->where(['user_email'=>$user_email])->get();
            foreach ($cartProducts as $pro) {
                $cartPro = new OrdersProduct;
                $cartPro->order_id = $order_id;
                $cartPro->user_id = $user_id;
                $cartPro->product_id = $pro->product_id;
                $cartPro->product_code = $pro->product_code;
                $cartPro->product_name = $pro->product_name;
                $cartPro->product_color = $pro->product_color;
                $cartPro->product_size = $pro->size;
                $cartPro->product_price = $pro->price;
                $cartPro->product_qty = $pro->quantity;
                $cartPro->save();
            }

            Session::put('order_id', $order_id);
            Session::put('grand_total',$data['grand_total']);

            if ($data['payment_method'] == "COD") {

                $productDetails = Order::with('orders')->where('id',$order_id)->first();
                $productDetails = json_decode(json_encode($productDetails),true);
                // echo "<pre>"; print_r($productDetails); die;

                $userDetails = User::where('id',$user_id)->first();
                $userDetails = json_decode(json_encode($userDetails),true);
                // echo "<pre>"; print_r($userDetails); die;

                // Start Code for Order Email
                $email = $user_email;
                $messageData = [
                    'email' => $email,
                    'name' => $shippingDetails->name,
                    'order_id' => $order_id,
                    'productDetails' => $productDetails,
                    'userDetails' => $userDetails
                ];
                Mail::send('emails.order',$messageData,function($message) use($email){
                    $message->to($email)->subject('Order Placed - E-com Website');
                });
                // END Code for Order Email

                // Redirect user to thanks page after saving order
                return redirect('/thanks');
            }else {
                // Paypal - Redirect user to paypal page after saving order
                return redirect('/paypal');
            }
            
        }
    }

    public function thanks(Request $request)
    {
        $user_email = Auth::user()->email;
        DB::table('cart')->where('user_email', $user_email)->delete();
        return view('orders.thanks');
    }

    public function thanksPaypal()
    {
        return view('orders.thanks_paypal');
    }

    public function cancelPaypal()
    {
        return view('orders.cancel_paypal');
    }
    
    public function paypal(Request $request)
    {
        $user_email = Auth::user()->email;
        DB::table('cart')->where('user_email', $user_email)->delete();
        return view('orders.paypal');
    }

    public function UserOrders()
    {
        $user_id = Auth::user()->id;
        $orders = Order::with('orders')->where('user_id', $user_id)->orderBy('id','DESC')->get();
        // $orders = json_decode(json_encode($orders));
        // echo "<pre>"; print_r($orders); die;
        return view('orders.user_orders', compact('orders'));
    }

    public function UserOrderDetails($order_id)
    {
        $user_id = Auth::user()->id;
        $orderDetails = Order::with('orders')->where('id',$order_id)->first();
        $orderDetails = json_decode(json_encode($orderDetails));
        // echo "<pre>"; print_r($orderDetails); die;
        return view('orders.user_order_details', compact('orderDetails'));
    }

    public function viewOrders()
    {
        $orders = Order::with('orders')->orderBy('id','DESC')->get();
        $orders = json_decode(json_encode($orders));
        // echo "<pre>"; print_r($orders); die;
        return view('admin.orders.view_orders', compact('orders'));
    }

    public function viewOrderDetails($order_id)
    {
        $orderDetails = Order::with('orders')->where('id',$order_id)->first();
        // $orderDetails = json_decode(json_encode($orderDetails));
        // echo "<pre>"; print_r($orderDetails); die;
        $user_id = $orderDetails->user_id;
        $userDetails = User::where('id', $user_id)->first();
        // $userDetails = json_decode(json_encode($userDetails));
        // echo "<pre>"; print_r($userDetails); die;
        return view('admin.orders.order_details', compact('orderDetails', 'userDetails'));
    }

    public function viewOrderInvoice($order_id)
    {
        $orderDetails = Order::with('orders')->where('id',$order_id)->first();
        $orderDetails = json_decode(json_encode($orderDetails));
        // echo "<pre>"; print_r($orderDetails); die;
        $user_id = $orderDetails->user_id;
        $userDetails = User::where('id', $user_id)->first();
        return view('admin.orders.order_invoice', compact('orderDetails', 'userDetails'));
    }

    public function updateOrderStatus(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            Order::where('id',$data['order_id'])->update(['order_status'=>$data['order_status']]);
            return redirect()->back()->with('flash_message_success', 'Order Status Updated Successfully !');
        }
    }

    public function checkPincode(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;            
            echo $pincodeCount = DB::table('pincodes')->where('pincode',$data['pincode'])->count();
        }
    }




}