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
    return response()->json($request->user());
});
Route::get('countries', function (\App\Country $country) {
    return response()->json($country->all());
});
Route::post('messenger', 'SupportController@sendMessage');
Route::post('send-invite', 'SupportController@sendInvite');
Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/users','UserController@index')->middleware('admin');
    Route::post('/users','UserController@store')->middleware('admin');
    Route::get('/user/{user}','UserController@show');
    Route::post('/user/{user}','UserController@update');
    Route::delete('/user/{user}', 'UserController@destroy')->middleware('admin');
    Route::get('/users/{user}/orders','UserController@showOrders');
    Route::get('/orders', 'OrderController@index');
    Route::get('/order/{order_id}', 'OrderController@show');
    Route::post('/order', 'OrderController@store');
    Route::post('/charge', 'StripeController@charge');
    Route::post('/change-order-status/{order_id}', 'OrderController@changeStatus')->middleware('admin');

    Route::post('/product-comments/{product}', 'CommentController@storeComment');
    Route::post('/comment/{comment}', 'CommentController@replyComment');
    Route::delete('/comment/{comment}', 'CommentController@deleteComment')->middleware('admin');


    Route::get('/is-admin', 'HomeController@admin');
//    Route::patch('products/{product}/units/add','ProductController@updateUnits');
//    Route::patch('orders/{order}/deliver','OrderController@deliverOrder');
//    Route::resource('/orders', 'OrderController');
//    Route::resource('/products', 'ProductController')->except(['index','show']);
});
