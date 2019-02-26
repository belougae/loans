<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\Api\UserRequest;

class UserController extends Controller
{
    public function store(UserRequest $request)
    {
        $verifyData = \Cache::get($request->verification_key);
        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }

        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            // 返回401
            return $this->response->errorUnauthorized('验证码错误');
        }
        if(!$user = User::where('phone', $verifyData['phone'])->first()){
            $user = User::create([
                        'phone' => $verifyData['phone'],
                    ]);
        }

        $token = \Auth::guard('api')->fromUser($user);

        // 清除验证码缓存
        \Cache::forget($request->verification_key);
    
        return $this->response->array([
            'access_token' => \Auth::guard('api')->fromUser($user),
            'token_type' => 'Bearer',
        ]);
    }
    public function logout()
    {
        Auth::guard('api')->logout();
        return $this->response->noContent();
    }
}
