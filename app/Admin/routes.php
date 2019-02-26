<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->get('merchants/group', 'MerchantController@merchantGroup')->name('merchants.group');
    $router->get('merchants/timing', 'MerchantStatisticController@timing')->name('merchants.timing');
    $router->resource('users', 'UserController');
    $router->resource('merchants', 'MerchantController');
    $router->resource('pictures', 'PictureController');
    $router->resource('statistics', 'MerchantStatisticController');

});
