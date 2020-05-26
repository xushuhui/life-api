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

if (!function_exists('axios_request')) {
    /**
     * 跨域问题设置
     */
    function axios_request()
    {
        $http_origin = !isset($_SERVER['HTTP_ORIGIN']) ? "*" : $_SERVER['HTTP_ORIGIN'];

        $http_origin = (empty($http_origin) || $http_origin == null || $http_origin == 'null') ? '*' : $http_origin;

        $_SERVER['HTTP_ORIGIN'] = $http_origin;

        //if(strtoupper($_SERVER['REQUEST_METHOD'] ?? "") == 'OPTIONS'){  //vue 的 axios 发送 OPTIONS 请求，进行验证
        //    return [];
        //}

        header('Access-Control-Allow-Origin: ' . $http_origin);// . $http_origin
        header('Access-Control-Allow-Credentials: true');//【如果请求方存在域名请求，那么为true;否则为false】
        header('Access-Control-Allow-Headers: Authorization, X-Requested-With, Content-Type, Access-Control-Allow-Headers, x-xsrf-token, Accept, x-file-name, x-frame-options, X-Requested-With, hanfuhui_fromclient, hanfuhui_token, hanfuhui_version');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');

        //header('X-Frame-Options:SAMEORIGIN');
    }
}
axios_request();

Route::prefix('store')->group(function() {
    Route::get('', function (){
        var_dump('store 模块');
    });
    Route::post('/getCode', 'AuthController@getCode');
    Route::post('/login', 'AuthController@login');
    Route::post('/register', 'AuthController@register');
    Route::post('/logout', 'AuthController@logout');


    // auth:store
    Route::group(['middleware' => ['store.jwt']], function () {
        // 图片上传
        Route::put('/upload/file', 'UploadController@files');

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
        // 商家分享
        Route::get('/share', 'StoreController@share');
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

        /**
         * 用户管理
         */
        Route::get('/users', 'UserController@index');

        /**
         * 会员管理
         */
        Route::get('/cardusers', 'CardUserController@index');
        Route::get('/oncecard/{user_id}', 'CardUserController@oncecard');
        Route::get('/storedvalue/{user_id}', 'CardUserController@storedvalue');
        Route::get('/getCouponDetail/{id}', 'CardUserController@getCouponDetail');

        /**
         * 订单
         */
        Route::get('/order/statistics', 'OrderController@statistics');
        Route::get('/order/statisticCoupons', 'OrderController@statisticCoupons');
        Route::get('/order/lists', 'OrderController@lists');
        Route::get('/order/orderLists', 'OrderController@orderLists');
        Route::get('/order/coupons', 'OrderController@coupons');
        Route::get('/order/couponLists/{coupon_id}', 'OrderController@couponLists');
        Route::get('/order/couponListExport/{coupon_id}', 'OrderController@couponListExport');


        /**
         * 扫码核销
         */
        // 检测优惠码是否存在
        Route::post('/code_check', 'DeductionCodeController@check');
        // 核销流程
        Route::post('/write_off', 'DeductionCodeController@writeOff');
    });
});
