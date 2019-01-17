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
Route::get('countries', function (\App\Country $country) {
    return response()->json($country->all());
});

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/users','UserController@index')->middleware('admin');
    Route::get('/users/{user}','UserController@show');
    Route::patch('/users/{user}','UserController@update');
    Route::get('/users/{user}/orders','UserController@showOrders');
    Route::get('/orders', 'OrderController@index');
    Route::get('/order/{order_id}', 'OrderController@show');
    Route::post('/order', 'OrderController@store');
    Route::post('/charge', 'StripeController@charge');
    Route::post('/change-order-status/{order_id}', 'OrderController@changeStatus')->middleware('admin');
//    Route::patch('products/{product}/units/add','ProductController@updateUnits');
//    Route::patch('orders/{order}/deliver','OrderController@deliverOrder');
//    Route::resource('/orders', 'OrderController');
//    Route::resource('/products', 'ProductController')->except(['index','show']);
});
