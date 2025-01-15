<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::post('forgot', 'API\UserController@sendResetLinkEmail');

// Route::post('login', 'AuthController@login');
// Route::post('social_login', 'AuthController@social_login');
// Route::post('signup', 'AuthController@signup');
// Route::post('forgot', 'AuthController@sendResetLinkEmail');
// Route::post('reset', 'ResetPasswordController@reset')->name('password.reset');


// cache clear
// Route::get('/cache', function () {
//     Artisan::call('config:clear');
//     Artisan::call('route:clear');
//     Artisan::call('view:clear');
//     Artisan::call('event:clear');
//     Artisan::call('cache:clear');
//     Artisan::call('config:clear');
//     Artisan::call('optimize');
//     dd("Cache is cleared");
// });


Route::middleware('auth:api')->group(function () {

    Route::get('user/me', 'API\UserController@me');
    Route::get('user/info', 'API\UserController@userById');
    Route::post('user/update', 'API\UserController@update');

    Route::get('categories', 'API\CategoryController@index');
    Route::get('materials', 'API\MaterialController@index');
    Route::get('material', 'API\MaterialController@single');
    // Route::resource('products', 'API\ProductController');
    
    Route::post('order/create', 'API\OrderController@store');
    Route::get('order/info', 'API\OrderController@single');
    Route::get('orders/my', 'API\OrderController@myOrders');
    Route::get('orders/by_customer', 'API\OrderController@getCustomerOrders');

    Route::get('bookings/my', 'API\OrderController@myBookings');
    Route::get('bookings/by_customer', 'API\OrderController@getCustomerBookings');

    Route::get('my_clients', 'API\OrderController@getSellerOrderClients');
    
    Route::post('send_contact_mail', 'API\CommonController@sendContactMail');

    // Hardik mobile api
    Route::get('dashoard-info', 'API\OrderController@dashboardInfo');
    Route::get('order-list', 'API\OrderController@orderList');
    Route::get('order-item-list', 'API\OrderController@orderitemList');
    Route::post('order-item/{id}/status', 'API\OrderController@updateStatus');
    Route::delete('order-item-delete/{id}', 'API\OrderController@destroyOrderItem');
    // end code
});


Route::post('check_purchase_details', 'API\PurchaseController@CheckPurchaseDetails');
Route::post('add_audit_details', 'API\PurchaseController@Addauditdetails');
Route::post('get_my_audit', 'API\PurchaseController@getmyaudit');

Route::post('send-notification', 'API\UserController@sendPushNotification');