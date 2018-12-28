<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/users','UserController@index')->middleware('admin');
    Route::get('/users/{user}','UserController@show');
    Route::patch('/users/{user}','UserController@update');
    Route::get('/users/{user}/orders','UserController@showOrders');
//    Route::patch('products/{product}/units/add','ProductController@updateUnits');
//    Route::patch('orders/{order}/deliver','OrderController@deliverOrder');
//    Route::resource('/orders', 'OrderController');
//    Route::resource('/products', 'ProductController')->except(['index','show']);
});
