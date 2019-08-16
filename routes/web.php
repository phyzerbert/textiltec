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

Route::post('/set_pagesize', 'HomeController@set_pagesize')->name('set_pagesize');
