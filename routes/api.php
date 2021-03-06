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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/sms/{dstaddr}', 'SmsController@send');
Route::post('/server/storage/error', 'Exception\ErrorController@storage');

Route::post('/register', 'Customer\CustomerController@register');
Route::put('/login', 'Customer\CustomerController@login');
Route::put('/logout', 'Customer\CustomerController@logout');
Route::put('/resetpassword', 'Customer\CustomerController@resetpassword');


Route::get('/get/{models}/{where?}', 'GetDataController@index');
Route::get('/authorization/get/{models}/{where?}', 'GetDataController@authorization');

Route::get('/image/{id}', 'GetDataController@image');


Route::group(['middleware' => 'api-auth'], function ($route) {

    Route::get('/user', 'Customer\CustomerController@getInfo');
    Route::post('/usersetting', 'Customer\CustomerController@updateInfo');

    Route::post('/watchlist/product', 'WatchlistController@addProduct');
    Route::delete('/watchlist/product', 'WatchlistController@deleteProduct');

    Route::post('/watchlist/article', 'WatchlistController@addArticle');
    Route::delete('/watchlist/article', 'WatchlistController@deleteArticle');


    $route->group(['prefix' => 'payment', 'namespace' => 'Payment'], function($route_payment) {
        $route_payment->get('cart', 'CartController@get');
        $route_payment->put('cart', 'CartController@upsert');

        $route_payment->post('order', 'OrderController@create');

        $route_payment->post('groupbuying', 'GroupBuyingController@create');
    });

});

Route::get('/psa/content/{id}', 'GetDataController@psaContent');


Route::options('/{a}', function ($req){
    return 'ok';
});

Route::options('/{a}/{b}', function ($req){
    return 'ok';
});

Route::options('/{a}/{b}/{c}', function ($req){
    return 'ok';
});

Route::options('/{a}/{b}/{c}/{d}', function ($req){
    return 'ok';
});

