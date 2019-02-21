<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request)
    {
        $phone = $request->phone;
        if(!app()->environment('production')){
            $code = '8888';
        }else{
            // 生成4位随机数，左侧补0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
            try {
                $result = $this->send($phone, $code);
            } catch(\Exception $exception) {
                return $this->response->errorInternal('短信发送异常');
            }

        }
        $key = 'verificationCode_'.str_random(15);
        $expiredAt = now()->addMinutes(10);
        // 缓存验证码 10分钟过期。
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);
        // 清除图片验证码缓存
        return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }

    private function send($phone, $code)
    {
        $postdata = http_build_query([
            "appKey" => config('sms.yunzhuanxin.app_key'),
            "appSecret" => config('sms.yunzhuanxin.app_secret'),
            "phones" => $phone,
            'content'  =>  "【车龙新车】验证码为：{$code}，5分钟内有效。"
        ]);
        $opts = ['http' =>
                        [
                            'method' => 'POST',
                            'header' => 'Content-type: application/x-www-form-urlencoded',
                            'content' => $postdata
                        ]];
        return file_get_contents('https://api.zhuanxinyun.com/api/v2/sendSms.json', false, stream_context_create($opts));
    }
}
