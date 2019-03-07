<?php

namespace App\Models\Traits;

use App\Models\MerchantStatistic;
use Carbon\Carbon;
use Cache;
use Illuminate\Support\Facades\Redis;

trait MerchantStatisticHelper
{
    // 商户统计定时
    public function timing1()
    {
        $at = Carbon::now()->toDateTimeString();
        $now = Carbon::now()->toDateString();
        // 当天存在数据的商户的 key
        foreach (Redis::keys('merchant_clicks:'.$now.'*') as $merchantKey) {
            $merchantexplod = explode(':', $merchantKey); 
            $clock = explode('-', $merchantexplod[1])[3];
            $merchantCount = Redis::scard($merchantKey);
            $result = MerchantStatistic::where('statistic_at', $now)->where('hour', $clock)->where('merchant_id', $merchantexplod[2])->get();
            if(!count($result)){
                MerchantStatistic::insert([
                'count' => $merchantCount,
                'merchant_id' => $merchantexplod[2],
                'created_at' => $at,
                'statistic_at' => $now,
                'hour' => $clock

                ]);
            }else{
                MerchantStatistic::where('statistic_at', $now)->where('merchant_id', $merchantexplod[2])->update([
                    'count' => $merchantCount,
                    'updated_at' => $at
                    ]);
            }
        }

    }
}