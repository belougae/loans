<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    // 图片上架
    $router->get('pictures/{picture}/putaway', 'PictureController@putaway')->name('pictures.putaway');
    // 图片下架
    $router->get('pictures/{picture}/sold_out', 'PictureController@soldOut')->name('pictures.soldOut');
    

    $router->group(['prefix' => 'statistics'],function ($router)
    {
        // 商户列表
        $router->get('merchants/group', 'MerchantController@merchantGroup');
        // 定时任务：统计 Redis 到 Mysql
        $router->get('timing', 'MerchantStatisticController@timing')->name('merchants.timing');
        $router->get('hours', 'MerchantStatisticController@hours');
        $router->get('days', 'MerchantStatisticController@days');
    });
    // 渠道列表
    $router->get('constants/group', 'ConstantController@constantGroup');
    $router->resource('users', 'UserController');
    $router->resource('constants', 'ConstantController');
    $router->resource('channel_statistics', 'ChannelStatisticController');
    $router->resource('merchants', 'MerchantController');
    $router->resource('pictures', 'PictureController');
    $router->resource('statistics', 'MerchantStatisticController');
    $router->resource('charts', 'ChartjsController');


});
