<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class UserCenterController extends Controller
{
    // 帮助中心
    public function helpCenter()
    {
        return $this->response->array(config('usercenter.help_center'));
    }

    // 隐私条款
    public function privacyPolicy()
    {
        $meta['first'] = config('usercenter.privacy_policy_top');
        return $this->response->array([config('usercenter.privacy_policy')]);
    }
}
