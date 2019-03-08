<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Models\Channel;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    // 渠道访问统计接口
    // public function visit(Request $request)
    // {
    //     $ua = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
    //     // Redis::sadd('channel_visit'.':'.Carbon::now()->toDateString().'-'.date ( "H").':'.$request->channel_id, $ua);
    //     Redis::sadd('channel_visit'.':'.Carbon::now()->toDateString().':'.$request->channel_id, $ua);
    //     return $this->response->array([]);
    // }

}
