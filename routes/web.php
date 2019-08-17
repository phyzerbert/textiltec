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
    return redirect(route('login'));
});

Auth::routes();
Route::get('lang/{locale}', 'IndexController@lang')->name('lang');
Route::get('/home', 'HomeController@index')->name('home');

Route::any('/supplier/index', 'SupplierController@index')->name('supplier.index');
Route::post('/supplier/create', 'SupplierController@create')->name('supplier.create');
Route::post('/supplier/purchase_create', 'SupplierController@purchase_create')->name('supplier.purchase_create');
Route::post('/supplier/edit', 'SupplierController@edit')->name('supplier.edit');
Route::get('/supplier/delete/{id}', 'SupplierController@delete')->name('supplier.delete');

Route::any('/scategory/index', 'ScategoryController@index')->name('scategory.index');
Route::post('/scategory/create', 'ScategoryController@create')->name('scategory.create');
Route::post('/scategory/edit', 'ScategoryController@edit')->name('scategory.edit');
Route::get('/scategory/delete/{id}', 'ScategoryController@delete')->name('scategory.delete');

Route::any('/pcategory/index', 'PcategoryController@index')->name('pcategory.index');
Route::post('/pcategory/create', 'PcategoryController@create')->name('pcategory.create');
Route::post('/pcategory/edit', 'PcategoryController@edit')->name('pcategory.edit');
Route::get('/pcategory/delete/{id}', 'PcategoryController@delete')->name('pcategory.delete');

Route::any('/supply/index', 'SupplyController@index')->name('supply.index');
Route::get('/supply/create', 'SupplyController@create')->name('supply.create');
Route::post('/supply/save', 'SupplyController@save')->name('supply.save');
Route::get('/supply/edit/{id}', 'SupplyController@edit')->name('supply.edit');
Route::post('/supply/update', 'SupplyController@update')->name('supply.update');
Route::get('/supply/detail/{id}', 'SupplyController@detail')->name('supply.detail');
Route::get('/supply/delete/{id}', 'SupplyController@delete')->name('supply.delete');

Route::get('/profile', 'UserController@profile')->name('profile');
Route::post('/updateuser', 'UserController@updateuser')->name('updateuser');
Route::any('/users/index', 'UserController@index')->name('users.index');
Route::post('/user/create', 'UserController@create')->name('user.create');
Route::post('/user/edit', 'UserController@edituser')->name('user.edit');
Route::get('/user/delete/{id}', 'UserController@delete')->name('user.delete');

Route::any('/purchase/index', 'PurchaseController@index')->name('purchase.index');
Route::get('/purchase/create', 'PurchaseController@create')->name('purchase.create');
Route::post('/purchase/save', 'PurchaseController@save')->name('purchase.save');
Route::get('/purchase/edit/{id}', 'PurchaseController@edit')->name('purchase.edit');
Route::post('/purchase/update', 'PurchaseController@update')->name('purchase.update');
Route::get('/purchase/detail/{id}', 'PurchaseController@detail')->name('purchase.detail');
Route::get('/purchase/delete/{id}', 'PurchaseController@delete')->name('purchase.delete');

Route::any('/payment/index/{id}', 'PaymentController@index')->name('payment.index');
Route::post('/payment/create', 'PaymentController@create')->name('payment.create');
Route::post('/payment/edit', 'PaymentController@edit')->name('payment.edit');
Route::get('/payment/delete/{id}', 'PaymentController@delete')->name('payment.delete');

Route::get('get_supplies', 'VueController@get_supplies');
Route::post('get_orders', 'VueController@get_orders');
Route::post('get_supply', 'VueController@get_supply');
Route::get('get_first_supply', 'VueController@get_first_supply');
Route::post('get_data', 'VueController@get_data');
Route::post('get_autocomplete_supplies', 'VueController@get_autocomplete_supplies');

Route::post('/set_pagesize', 'HomeController@set_pagesize')->name('set_pagesize');
