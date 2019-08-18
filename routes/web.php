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
Route::get('/supplier/report/{id}', 'SupplierController@report')->name('supplier.report');
Route::get('/supplier/delete/{id}', 'SupplierController@delete')->name('supplier.delete');

Route::any('/customer/index', 'CustomerController@index')->name('customer.index');
Route::post('/customer/create', 'CustomerController@create')->name('customer.create');
Route::post('/customer/product_sale_create', 'CustomerController@product_sale_create')->name('customer.product_sale_create');
Route::post('/customer/edit', 'CustomerController@edit')->name('customer.edit');
Route::get('/customer/report/{id}', 'CustomerController@report')->name('customer.report');
Route::get('/customer/delete/{id}', 'CustomerController@delete')->name('customer.delete');

Route::any('/scategory/index', 'ScategoryController@index')->name('scategory.index');
Route::post('/scategory/create', 'ScategoryController@create')->name('scategory.create');
Route::post('/scategory/edit', 'ScategoryController@edit')->name('scategory.edit');
Route::get('/scategory/delete/{id}', 'ScategoryController@delete')->name('scategory.delete');

Route::any('/workshop/index', 'WorkshopController@index')->name('workshop.index');
Route::post('/workshop/create', 'WorkshopController@create')->name('workshop.create');
Route::post('/workshop/edit', 'WorkshopController@edit')->name('workshop.edit');
Route::get('/workshop/delete/{id}', 'WorkshopController@delete')->name('workshop.delete');

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

Route::any('/payment/index/{type}/{id}', 'PaymentController@index')->name('payment.index');
Route::post('/payment/create', 'PaymentController@create')->name('payment.create');
Route::post('/payment/edit', 'PaymentController@edit')->name('payment.edit');
Route::get('/payment/delete/{id}', 'PaymentController@delete')->name('payment.delete');

Route::any('/product/index', 'ProductController@index')->name('product.index');
Route::post('/product/create', 'ProductController@create')->name('product.create');
Route::post('/product/edit', 'ProductController@edit')->name('product.edit');
Route::get('/product/delete/{id}', 'ProductController@delete')->name('product.delete');
Route::post('/product/produce_create', 'ProductController@produce_create')->name('product.produce_create');

Route::any('/produce_order/index', 'ProduceOrderController@index')->name('produce_order.index');
Route::get('/produce_order/create', 'ProduceOrderController@create')->name('produce_order.create');
Route::post('/produce_order/save', 'ProduceOrderController@save')->name('produce_order.save');
Route::get('/produce_order/edit/{id}', 'ProduceOrderController@edit')->name('produce_order.edit');
Route::post('/produce_order/update', 'ProduceOrderController@update')->name('produce_order.update');
Route::get('/produce_order/detail/{id}', 'ProduceOrderController@detail')->name('produce_order.detail');
Route::get('/produce_order/report/{id}', 'ProduceOrderController@report')->name('produce_order.report');
Route::get('/produce_order/report_view/{id}', 'ProduceOrderController@report_view')->name('produce_order.report_view');
Route::get('/produce_order/delete/{id}', 'ProduceOrderController@delete')->name('produce_order.delete');

Route::any('/order_receive/index/{id}', 'ProduceOrderReceptionController@index')->name('order_receive.index');
Route::post('/order_receive/create', 'ProduceOrderReceptionController@create')->name('order_receive.create');
Route::post('/order_receive/edit', 'ProduceOrderReceptionController@edit')->name('order_receive.edit');
Route::get('/order_receive/delete/{id}', 'ProduceOrderReceptionController@delete')->name('order_receive.delete');

Route::any('/product_sale/index', 'ProductSaleController@index')->name('product_sale.index');
Route::get('/product_sale/create', 'ProductSaleController@create')->name('product_sale.create');
Route::post('/product_sale/save', 'ProductSaleController@save')->name('product_sale.save');
Route::get('/product_sale/edit/{id}', 'ProductSaleController@edit')->name('product_sale.edit');
Route::post('/product_sale/update', 'ProductSaleController@update')->name('product_sale.update');
Route::get('/product_sale/detail/{id}', 'ProductSaleController@detail')->name('product_sale.detail');
Route::get('/product_sale/report/{id}', 'ProductSaleController@report')->name('product_sale.report');
Route::get('/product_sale/report_view/{id}', 'ProductSaleController@report_view')->name('product_sale.report_view');
Route::get('/product_sale/delete/{id}', 'ProductSaleController@delete')->name('product_sale.delete');

Route::get('get_supplies', 'VueController@get_supplies');
Route::get('get_products', 'VueController@get_products');
Route::post('get_orders', 'VueController@get_orders');
Route::post('get_supply', 'VueController@get_supply');
Route::post('get_product', 'VueController@get_product');
Route::post('produce_get_supply', 'VueController@produce_get_supply');
Route::get('get_first_supply', 'VueController@get_first_supply');
Route::get('get_first_product', 'VueController@get_first_product');
Route::post('produce_get_data', 'VueController@produce_get_data');
Route::post('get_product_sale_data', 'VueController@get_product_sale_data');
Route::post('get_autocomplete_supplies', 'VueController@get_autocomplete_supplies');
Route::post('get_autocomplete_products', 'VueController@get_autocomplete_products');

Route::post('/set_pagesize', 'HomeController@set_pagesize')->name('set_pagesize');
