<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->get('banner/id/{id}', 'BannerController@getBannerById');
$router->get('banner/name/{name}', 'BannerController@getBannerByName');
$router->get('theme/by/names', 'ThemeController@getThemes');
$router->get('theme/name/{name}', 'ThemeController@getThemeByName');
$router->get('activity/name/{name}', 'ActivityController@getActivityByName');
$router->get('activity/name/{name}', 'ActivityController@getActivityByNameWithSpu');
$router->get('coupon/by/category/{id}', 'CouponController@getCouponByCategory');
$router->get('coupon/whole_store', 'CouponController@wholeStore');
$router->get('coupon/myself/by/status/{status}', 'CouponController@myself');
$router->get('coupon/myself/available/with_category', 'CouponController@myselfWithCategory');
$router->post('coupon/collect/{id}', 'CouponController@collect');
$router->get('category/all', 'CategoryController@all');
$router->get('category/grid/all', 'CategoryController@grid');
$router->get('search', 'SpuController@search');
$router->get('sale_explain/fixed', 'SpuController@saleExplain');
$router->get('tag/type/:type', 'SpuController@searchByTag');
$router->get('spu/id/{id}', 'SpuController@detail');
$router->get('spu/latest', 'SpuController@latest');
$router->get('spu/by/category/{id}', 'SpuController@getSpuByCategory');