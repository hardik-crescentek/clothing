<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/api/doc', function () {
    return view('api');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('dashboard')->middleware('auth');

Route::group(['middleware' => ['auth']], function() {
    // Route::get('/dashboard', 'HomeController@index')->name('dashboard');
    // Route::resource('roles','RoleController');
    Route::resource('users','UserController');   
    
	Route::resource('category', 'CategoryController');
    Route::resource('color', 'ColorController');
    Route::resource('materials', 'MaterialController');
    Route::resource('purchase', 'PurchaseController');
    Route::resource('supplier', 'SupplierController');
    Route::resource('supplier', 'SupplierController');
    Route::delete('purchase_item/{purchaseItem}', 'PurchaseController@deletePurchaseItem')->name('purchase.deletePurchaseItem');
    Route::patch('update-item/', 'PurchaseController@updatePurchaseItem')->name('purchase.update-item');
    Route::get('inventory', 'InventoryController@index')->name('inventory');
    Route::get('printbarcode', 'PrintBarcodeController@index')->name('printbarcode');
    Route::get('genrate_code', 'MaterialController@generateCode');

    Route::get('profile', 'ProfileController@index')->name('profile');
	Route::put('profile/update', 'ProfileController@update')->name('profile.update');
	Route::put('profile/changepass', 'ProfileController@changePassword')->name('profile.changePassword');

});

