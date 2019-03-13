<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Picture;
use App\Models\User;
use App\Transformers\MerchantTransformer;
use Illuminate\Http\Request;
use App\Models\Merchant;
use Illuminate\Support\Facades\Redis;
use App\Models\MerchantStatuses;

class MerchantController extends Controller
{
    public function index(Request $request)
    {
        $ua = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        // Redis::sadd('channel_visit'.':'.Carbon::now()->toDateString().'-'.date ( "H").':'.$request->channel_id, $ua);
        Redis::sadd('channel_visit'.':'.Carbon::now()->toDateString().':'.$request->channel_id, $ua);
        \Cache::increment('count:count_visit', 1);
        $meta['hot_ad'] = '建议申请3家以上，100% 一小时内到帐';
        // $meta['count_visit'] = \Cache::get('count:count_visit');
        // 首页轮播图
        $meta['index_top_banner'] = Picture::where('type', 1)->get()->pluck('thumbnail');

        return $this->response->collection($this->merchantList('index'), new MerchantTransformer())->setMeta($meta);
    }

    /**
     * 各商户点击数（每日每商户每用户记一次）
    */
    public function clicks(Request $request)
    {
        // etc: channel_clicks:2019-02-22-00(日期):merchant_id
        Redis::sadd('merchant_clicks'.':'.Carbon::now()->toDateString().'-'.date ( "H").':'.$request->merchant_id, $this->user()->phone);
        return $this->response->array([]);
    }

    /**
     * 指定平台（桔子贷）商户列表接口
    */
    public function platform(Request $request)
    {
        return $this->response->collection($this->merchantList($request->platform_name), new MerchantTransformer()); 
    }

    public function merchantList($type)
    {
        $merchantIds = MerchantStatuses::where('type', $type)
        ->where('putaway', "on")
        ->orderBy('top', 'DESC')
        ->orderBy('sort', 'DESC')
        ->pluck('merchant_id');
        // 循环是为了保证排序
        $merchants = [];
        foreach($merchantIds as $id){
            $merchants[] = Merchant::find($id);
        }
        return collect($merchants);
    }
}
