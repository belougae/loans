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
        // 用户登录
        $api->post('users', 'UserController@store')->name('api.users.store');
    });
    // 登录
    $api->post('authorizations', 'AuthorizationsController@store')
    ->name('api.authorizations.store');
    $api->get('merchants/today_recommend', 'MerchantController@todayRecommend');//
    $api->get('merchants/new_loan_king', 'MerchantController@newLoanKing');//
    $api->get('merchants/new_holes', 'MerchantController@newHoles');
    $api->resource('merchants', 'MerchantController');
});
