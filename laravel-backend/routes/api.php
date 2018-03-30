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

//shoppingCarts API
Route::get('shoppingCarts', 'ShoppingCartController@getShoppingCarts');
Route::post('shoppingCarts', 'ShoppingCartController@createShoppingCart');
Route::put('shoppingCarts/{id}', 'ShoppingCartController@updateShoppingCart');
Route::delete('shoppingCarts/{id}', 'ShoppingCartController@deleteShoppingCart');


//Product API
Route::get('products', 'ProductController@listAllProducts');
Route::post('products', 'ProductController@uploadProduct');
Route::put('products/{id}', 'ProductController@editProduct');
Route::delete('products/{id}', 'ProductController@deleteProduct');



//user API
Route::get('user/{address}', 'UserInfoController@checkIfUserExists');
Route::post('user', 'UserInfoController@store');
