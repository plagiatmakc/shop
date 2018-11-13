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

Route::get('/', 'PageController@index');


Auth::routes();

//Route::prefix('admin')->group(function () {});

Route::resources([
    'categories' => 'CategoryController',
    'products' => 'ProductController',
    'product_images' => 'ProductImagesController',
]);
Route::get('/products/{product_id}/attributes', 'ProductController@addAttr');
Route::post('/products/{product_id}/attributes', 'ProductController@storeAttr');
Route::delete('/attributes/{id}', 'ProductController@delAttr');
Route::get('/attributes/{id}', 'ProductController@editAttr');
Route::put('/attributes/{id}', 'ProductController@changeAttr');

// Cart manipulations
Route::get('/cart', 'CartController@index');
Route::get('/add-to-cart/{id}', 'CartController@addToCart');
Route::get('/del-from-cart/{id}', 'CartController@delFromCart');
Route::get('/remove-from-cart/{id}', 'CartController@removeFromCart');

Route::get('/show-product/{id}/{sku}', 'PageController@showProduct');

Route::get('/all-categories', 'CategoryController@allCategories');
//Route::post('/category/create', 'CategoryController@store');
//Route::get('/category/{id}', 'CategoryController@show');
//Route::get('/category/update/{id}', 'CategoryController@edit');
//Route::post('/category/update/{id}', 'CategoryController@update');
//Route::post('/category/delete/{id}', 'CategoryController@delete');

// Products routes

//Route::get('/product/create', 'ProductController@create');
//Route::post('/product/create', 'ProductController@store');
//Route::get('/product/{id}', 'ProductController@show');
//Route::get('/product/update/{id}', 'ProductController@edit');
//Route::post('/product/update/{id}', 'ProductController@update');
//Route::post('/product/delete/{id}', 'ProductController@delete');
//Route::get('/products/{items}', 'ProductController@index');


Route::get('/home', 'HomeController@index')->middleware('auth');
Route::get('/admin', 'HomeController@admin')->middleware('admin');
