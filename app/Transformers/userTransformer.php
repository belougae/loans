<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $customer)
    {
        return [
            'id' => (int)$customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'avatar' => $customer->avatar,
            // 'mobile' => substr_replace($customer->mobile, '****', 3, 4),
            'mobile' => $customer->mobile,
            'integral' => $customer->integral,
            'bound_mobile' => $customer->mobile ? true : false,
            'bound_wechat' => ($customer->weixin_unionid || $customer->weixin_openid) ? true : false,
            'created_at' => $customer->created_at->toDateTimeString(),
            'updated_at' => $customer->updated_at->toDateTimeString(),
        ];
    }
}