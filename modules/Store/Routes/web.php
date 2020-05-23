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


use Illuminate\Support\Facades\Route;

Route::prefix('store')->group(function() {
    Route::post('/getCode', 'AuthController@getCode');
    Route::post('/login', 'AuthController@login');
    Route::post('/register', 'AuthController@register');
    Route::post('/logout', 'AuthController@logout');


    Route::group(['middleware' => ['auth:store']], function () {
        // 图片上传
        Route::put('/upload/file', 'UploadController@file');

        /**
         * 优惠券
         */
        // 发布优惠券
        Route::put('/coupon/publish', 'CouponController@publish');
        // Route::delete('/coupon/delete/{id}', 'CouponController@delete');
        // 次卡下拉列表
        Route::get('/coupon_oncecard', 'CouponController@getOnceCardList');
        // 储值卡下拉列表
        Route::get('/coupon_storedvalue', 'CouponController@getStoredValueList');
        // 优惠券详情
        Route::get('/coupon/{id}', 'CouponController@share');
        // 次卡/储值 充值列表
        Route::get('/recharges', 'RechargeController@index');
        // 次卡充值
        Route::put('/coupon/oncecard_recharge', 'RechargeController@create');
        // 储值充值
        Route::put('/coupon/storedvalue_recharge', 'RechargeController@create');


        // 获取商家信息
        Route::get('/detail', 'StoreController@detail');
        // 更新商家信息
        Route::put('/store', 'StoreController@update');

        /**
         * 商品管理
         */
        Route::get('/good', 'GoodController@index');
        Route::get('/good/{id}', 'GoodController@detail');
        Route::put('/good', 'GoodController@update');
        Route::delete('/good/{id}', 'GoodController@delete');

        /**
         * 员工管理
         */
        Route::get('/staff', 'StaffController@index');
        Route::get('/staff/{id}', 'StaffController@detail');
        Route::put('/staff', 'StaffController@update');
        Route::delete('/staff/{id}', 'StaffController@delete');

        /**
         * 会员
         */
        Route::get('/user/lists', 'UserStatisticsController@lists');
        Route::get('/user/statistics', 'UserStatisticsController@statistics');
        Route::get('/user/userLists', 'UserStatisticsController@userLists');

        /**
         * 地址管理
         */
        Route::get('/address', 'AddressController@index');
        Route::get('/address/{id}', 'AddressController@detail');
        Route::put('/address', 'AddressController@update');
        Route::delete('/address/{id}', 'AddressController@delete');
    });
});
