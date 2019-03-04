<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class UserCenterController extends Controller
{
    public function helpCenter()
    {
        return $this->response->array(config('usercenter.help_center'));
    }

    public function privacyPolicy()
    {
        return $this->response->array(config('usercenter.privacy_policy'));
    }
}
