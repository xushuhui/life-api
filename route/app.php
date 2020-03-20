<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('hello/:name', 'index/hello');
Route::get('banner/id/:id', 'banner/getBannerById');
Route::get('banner/name/:name', 'banner/getBannerByName');
Route::get('theme/by/names', 'theme/getThemes');
Route::get('theme/name/:name', 'theme/getThemeByName');
Route::get('activity/name/:name', 'activity/getActivityByName');
Route::get('activity/name/:name', 'activity/getActivityByNameWithSpu');
Route::get('coupon/by/category/:id', 'coupon/getCouponByCategory');
Route::get('coupon/whole_store', 'coupon/wholeStore');
Route::get('coupon/myself/by/status/:status', 'coupon/myself');
Route::get('coupon/myself/available/with_category', 'coupon/myselfWithCategory');
Route::post('coupon/collect/:id', 'coupon/collect');
Route::get('category/all', 'category/all');
Route::get('category/grid/all', 'category/grid');
Route::get('search', 'spu/search');
Route::get('sale_explain/fixed', 'spu/saleExplain');
Route::get('tag/type/:type', 'spu/searchByTag');
Route::get('spu/id/:id', 'spu/detail');
Route::get('spu/latest', 'spu/latest');
Route::get('spu/by/category/:id', 'spu/getSpuByCategory');
