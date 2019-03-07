<?php

namespace App\Models\Traits;

use App\Models\ChannelStatistic;
use Carbon\Carbon;
use Cache;
use Illuminate\Support\Facades\Redis;

trait ChannelStatisticHelper
{
        // 商户统计定时
        public function timing()
        {
            $at = Carbon::now()->toDateTimeString();
            $now = Carbon::now()->toDateString();
            
            // 访问统计
            foreach (Redis::keys('channel_visit:'.$now.'*') as $channelKey) {
                $channelExplod = explode(':', $channelKey); 
                $channelVisitCount = Redis::scard($channelKey);
                $result = ChannelStatistic::where('statistic_at', $now)->where('channel_id', $channelExplod[2])->get();
    
                if(!count($result)){
                    ChannelStatistic::insert([
                    'count_visit' => $channelVisitCount,
                    'channel_id' => $channelExplod[2],
                    'created_at' => $at,
                    'statistic_at' => $now
                    ]);
                }else{
                    ChannelStatistic::where('statistic_at', $now)->where('channel_id', $channelExplod[2])->update([
                        'count_visit' => $channelVisitCount,
                        'updated_at' => $at
                        ]);
                }
            }
    
            // 注册统计
            foreach (Redis::keys('channel_register:'.$now.'*') as $channelKey) {
                $channelExplod = explode(':', $channelKey); 
                $channelRegisterCount = Redis::scard($channelKey);
                $result = ChannelStatistic::where('statistic_at', $now)->where('channel_id', $channelExplod[2])->get();
    
                if(!count($result)){
                    ChannelStatistic::insert([
                    'count_register' => $channelRegisterCount,
                    'channel_id' => $channelExplod[2],
                    'created_at' => $at,
                    'statistic_at' => $now
                    ]);
                }else{
                    ChannelStatistic::where('statistic_at', $now)->where('channel_id', $channelExplod[2])->update([
                        'count_register' => $channelRegisterCount,
                        'updated_at' => $at
                        ]);
                }
            }
    
        }
}