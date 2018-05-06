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
Route::get('shoppingCarts/{address}', 'ShoppingCartController@getShoppingCarts');
Route::post('shoppingCarts/{address}', 'ShoppingCartController@createShoppingCart');
Route::put('shoppingCarts/{id}', 'ShoppingCartController@updateShoppingCart');
Route::delete('shoppingCarts/{id}/{address}', 'ShoppingCartController@deleteShoppingCart');


//Product API
Route::get('products/{address}', 'ProductController@listAllOwnProducts');
Route::post('products/{address}', 'ProductController@uploadProduct');
Route::put('products/{id}', 'ProductController@editProduct');
Route::delete('products/{id}/{address}', 'ProductController@deleteProduct');
Route::get('products', 'ProductController@listAllProducts');
Route::get('searchproducts/{key_words}', 'ProductController@searchProducts');
Route::post('listproducts', 'ProductController@getListOfProducts');
Route::get('products/product/{id}', 'ProductController@getSingleProduct');
Route::post('purchaseProducts', 'ProductController@reduceProductQuantity');


//user API
Route::get('user/{address}', 'UserInfoController@checkIfUserExists');
Route::post('user', 'UserInfoController@store');
Route::get('userid/{id}','UserInfoController@getUserAddressById');

//Image Upload POC
Route::post('image', 'ImageController@store');
