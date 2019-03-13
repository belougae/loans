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

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
], function($api) {

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function($api) {
        // 短信验证码
        $api->post('verificationCodes', 'VerificationCodesController@store')->name('api.verificationCodes.store');
        $api->post('merchants/clicks', 'MerchantController@clicks');
        $api->get('users/logout', 'UserController@logout');
        // 渠道访问统计
        $api->post('channels/visit', 'ChannelController@visit');
    });
    $api->group([
        'middleware' => 'cors',// 跨域
    ], function($api) {
        // 指定平台（桔子贷）商户列表接口
        $api->get('merchants/platform', 'MerchantController@platform');
    });

    // 用户登录
    $api->post('users', 'UserController@store')->name('api.users.store');
    // 登录
    $api->post('authorizations', 'AuthorizationsController@store');
    // 帮助中心
    $api->get('help_center', 'UserCenterController@helpCenter');
    // 隐私政策
    $api->get('privacy_policy', 'UserCenterController@privacyPolicy');
    $api->get('merchants/today_recommend', 'MerchantController@todayRecommend');//
    $api->get('merchants/new_loan_king', 'MerchantController@newLoanKing');//最新下款王
    $api->get('merchants/new_holes', 'MerchantController@newHoles');
    $api->resource('merchants', 'MerchantController');
});
