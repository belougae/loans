<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\Api\UserRequest;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class UserController extends Controller
{
    public function store(UserRequest $request)
    {
        $verifyData = \Cache::get($request->verification_key);
        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }

        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            return $this->response->error('验证码错误', 422);
        }
        $user = User::where('phone', $verifyData['phone'])->first();
        $createArr = [];
        if($channel_id = $request->channel_id && !$user){
            Redis::sadd('channel_register'.':'.Carbon::now()->toDateString().':'.$channel_id, $verifyData['phone']);
            $createArr['channel_id'] = $channel_id;
        }

        if(!$user){
            $createArr['phone'] = $verifyData['phone'];
            $user = User::create($createArr);
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
        return $this->response->array([]);
    }
}
