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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// 小程序登录
Route::post('weapp/authorizations', 'AuthorizationsController@weappStore')
    ->name('weapp.authorizations.store');

// 刷新 token
Route::put('authorizations/current', 'AuthorizationsController@update')
    ->name('weapp.authorizations.update');
// 删除 token
Route::delete('authorizations/current', 'AuthorizationsController@destroy')
    ->name('weapp.authorizations.destroy');
Route::get('users/{user}', 'UsersController@show')
    ->name('weapp.users.show');
