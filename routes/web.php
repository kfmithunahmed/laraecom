<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});




Auth::routes(['verify' => true]);
Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');



/**
 * Start Frontend Route
 */
// Home Page
Route::get('/', 'IndexController@index');
// Category / Listing Page
Route::get('/products/{url}','ProductsController@products');
// Products Filter Page
Route::match(['get','post'],'/products-filter','ProductsController@filter');
// Product Details Page
Route::get('product/{id}','ProductsController@product');
// Get Product Attribute Price
Route::get('/get-product-price','ProductsController@getProductPrice');
// Add to Cart Route 
Route::match(['get','post'],'/add-cart','ProductsController@addtocart');
// Cart Page
Route::match(['get','post'],'/cart','ProductsController@cart');
// Delete Product from Cart Page
Route::get('/cart/delete-product/{id}','ProductsController@deleteCartProduct');
// Update Product Quantity in Cart
Route::get('/cart/update-quantity/{id}/{quantity}','ProductsController@updateCartQuantity');
// Apply Coupon
Route::post('/cart/apply-coupon','ProductsController@applyCoupon');
// Users Login/Register Page
Route::get('/login-register','UsersController@userLoginRegister');
// Frogot User Password
Route::match(['get','post'],'forgot-password','UsersController@forgotPassword');
// User Register Form Submit
Route::post('/user-register','UsersController@register');
// Confirm Account
Route::get('/confirm/{code}','UsersController@confirmAccount');
// User Login Form Submit
Route::post('/user-login','UsersController@login');
// Users Logout
Route::get('/user-logout','UsersController@logout');
// Search Products
Route::post('/search-products','ProductsController@searchProducts');
// Check if User already exists
Route::match(['GET','POST'],'/check-email','UsersController@checkEmail');
// Check Pincode
Route::post('/check-pincode','ProductsController@checkPincode');

// All Routes after Login | frontlogin middleware route group
Route::group(['middleware' => ['frontlogin']], function () {
    // Users Account Page
    Route::match(['get','post'],'/account','UsersController@account');
    // Check User Current Password
    Route::post('/check-user-pwd','UsersController@chkUserPassword');
    // Update User Password
    Route::post('/update-user-pwd','UsersController@updatePassword');
    // Checkout Page
    Route::match(['get','post'],'/checkout','ProductsController@checkout');
    // Order Review Page
    Route::match(['get','post'],'/order-review','ProductsController@orderReview');
    // Place Order
    Route::match(['get','post'],'/place-order','ProductsController@placeOrder');
    // Thanks Page
    Route::get('/thanks', 'ProductsController@thanks');
    // Paypal Page
    Route::get('/paypal', 'ProductsController@paypal');
    // User Orders Pages
    Route::get('/orders', 'ProductsController@UserOrders');
    // User Ordered Product Page
    Route::get('/orders/{id}/', 'ProductsController@UserOrderDetails');
    // Paypal Thanks Page
    Route::get('/paypal/thanks', 'ProductsController@thanksPaypal');
    // Paypal Cancel Page
    Route::get('/paypal/cancel', 'ProductsController@cancelPaypal');
});




/**
 * END Frontend Route
 */ 




/**
 * Start Admin Backend Route
 */
Route::match(['get','post'],'/admin','AdminController@login');

// adminlogin middleware route group
Route::group(['middleware' => 'adminlogin'], function () {
    Route::get('/admin/dashboard','AdminController@dashboard');
    Route::get('/admin/settings','AdminController@settings');
    Route::get('/admin/check-pwd','AdminController@chkPassword');
    Route::match(['get','post'],'/admin/update-pwd', 'AdminController@updatePassword');

    // Categories Route (Admin)
    Route::match(['get','post'],'/admin/add-category', 'CategoryController@addCategory');
    Route::match(['get','post'],'/admin/edit-category/{id}', 'CategoryController@editCategory');
    Route::match(['get','post'],'/admin/delete-category/{id}', 'CategoryController@deleteCategory');
    Route::get('/admin/view-categories', 'CategoryController@viewCategories');

    // Products Route (Admin)
    Route::match(['get','post'],'/admin/add-product', 'ProductsController@addProduct');
    Route::match(['get','post'],'/admin/edit-product/{id}', 'ProductsController@editProduct');
    Route::get('/admin/view-products', 'ProductsController@viewProducts');
    Route::get('/admin/delete-product-image/{id}', 'ProductsController@deleteProductImage');
    Route::get('/admin/delete-product-video/{id}', 'ProductsController@deleteProductVideo');
    Route::get('/admin/delete-product/{id}', 'ProductsController@deleteProduct');
    Route::get('/admin/delete-alt-image/{id}', 'ProductsController@deleteAltImage');

    // Products Attribute Route (Admin)
    Route::match(['get','post'],'admin/add-attributes/{id}', 'ProductsController@addAttributes');
    Route::match(['get','post'],'admin/edit-attributes/{id}', 'ProductsController@editAttributes');
    Route::match(['get','post'],'admin/add-images/{id}', 'ProductsController@addImages');
    Route::get('/admin/delete-attribute/{id}', 'ProductsController@deleteAttribute');

    // Coupons Routes
    Route::match(['get','post'],'/admin/add-coupon', 'CouponsController@addCoupon');
    Route::match(['get','post'],'/admin/edit-coupon/{id}', 'CouponsController@editCoupon');
    Route::get('/admin/view-coupons', 'CouponsController@viewCoupons');
    Route::get('/admin/delete-coupon/{id}', 'CouponsController@deleteCoupon');

    // Admin Banner Route
    Route::match(['get','post'],'/admin/add-banner', 'BannersController@addBanner');
    Route::get('/admin/view-banners', 'BannersController@viewBanners');
    Route::match(['get','post'],'/admin/edit-banner/{id}', 'BannersController@editBanner');
    Route::get('/admin/delete-banner/{id}', 'BannersController@deleteBanner');

    // Admin Orders Route
    Route::get('/admin/view-orders', 'ProductsController@viewOrders');
    // Admin Order Details Route
    Route::get('/admin/view-order/{id}', 'ProductsController@viewOrderDetails');
    // Order Invoice
    Route::get('/admin/view-order-invoice/{id}', 'ProductsController@viewOrderInvoice');
    // Update order status
    Route::post('/admin/update-order-status', 'ProductsController@updateOrderStatus');
    // Admin Users Route
    Route::get('/admin/view-users', 'UsersController@viewUsers');
    

    // Add CMS Route
    Route::match(['GET','POST'],'/admin/add-cms-page','CmsController@addCmsPage');
    // Edit CMS Route
    Route::match(['GET','POST'],'/admin/edit-cms-page/{id}','CmsController@editCmsPage');
    // View CMS Pages Route
    Route::get('/admin/view-cms-pages', 'CmsController@viewCmsPages');
    // Delete CMS Pages Route
    Route::get('/admin/delete-cms-page/{id}', 'CmsController@deleteCmsPages');

    // Get Enquiries
    Route::get('/admin/get-enquiries','CmsController@getEnquiries');
    // View Enquiries
    Route::get('/admin/view-enquiries','CmsController@viewEnquiries');

    // Add Currency Route
    Route::match(['GET','POST'],'/admin/add-currency','CurrencyController@addCurrency');
    // View Currency Route
    Route::get('/admin/view-currencies','CurrencyController@viewCurrencies');
    // Edit Currency Route
    Route::match(['GET','POST'],'/admin/edit-currency/{id}','CurrencyController@editCurrency');
    // Delete Currency Route
    Route::get('/admin/delete-currency/{id}','CurrencyController@deleteCurrency');

});



Route::get('/logout','AdminController@logout');
/**
 * END Admin Backend Route
 */

// Display Cms Page Frontend Route
Route::match(['GET','POST'],'/page/{url}','CmsController@cmsPage');
// Upload Image CMS Pages Frontend Route
Route::post('ckeditor/image_upload', 'CmsController@upload')->name('upload');
// Display Contact Page Route
Route::match(['get','post'],'/contact','CmsController@contact');
