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

class MerchantController extends Controller
{
    public function index()
    {
        \Cache::increment('count:count_visit', 1);
        $meta['hot_ad'] = '建议申请3家以上，100% 一小时内到帐';
        // $meta['count_visit'] = \Cache::get('count:count_visit');
        // 首页轮播图
        $meta['index_top_banner'] = Picture::where('type', 1)->get()->pluck('thumbnail');
        return $this->response->collection(Merchant::get(), new MerchantTransformer())->setMeta($meta);
    }

    // 最新下款王
    public function newLoanKing()
    {
        $meta['page'] = 'new_loan_king';
        return $this->response->collection(Merchant::get(), new MerchantTransformer());
    }

    // 新口子
    public function newHoles()
    {
        return $this->response->collection(Merchant::where('type')->get(), new MerchantTransformer()); 
    }

    public function todayRecommend()
    {
        return $this->response->collection(Merchant::get(), new MerchantTransformer()); 
    }

    // 各商户点击数（每日每商户每用户记一次）
    public function clicks(Request $request)
    {
        // etc: channel_clicks:2019-02-22-00(日期):1（channel_id）
        Redis::sadd('merchant_clicks'.':'.Carbon::now()->toDateString().'-'.date ( "H").':'.$request->channel_id, $this->user()->phone);
        return $this->response->noContent();
    }
}
