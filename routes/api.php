<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

// 小程序登录
Route::post('authorizations', 'AuthorizationsController@store');
// 刷新 token
Route::put('authorizations', 'AuthorizationsController@update');
// 删除 token
Route::delete('authorizations', 'AuthorizationsController@destroy');


Route::get('user/invites', 'UserController@invites');
Route::get('user/{user}', 'UserController@show');
Route::put('user', 'UserController@update');


Route::get('like/stores', 'LikeController@stores');
Route::post('like/store/{id}', 'LikeController@store');
Route::post('like/coupon/{id}', 'LikeController@coupon');
Route::get('like/coupons', 'LikeController@coupons');

Route::get('coupon/status/{status}', 'CouponController@index');
Route::get('coupon/stores', 'CouponController@stores');
Route::get('coupon/store/{store_id}', 'CouponController@store');
Route::get('coupon/used/{coupon_id}', 'CouponController@used');

Route::get('stores', 'StoreController@index');
//搜索
Route::get('store/search', 'StoreController@search');
//筛选
Route::get('store/filter', 'StoreController@filter');
Route::get('store/{id}', 'StoreController@show');

