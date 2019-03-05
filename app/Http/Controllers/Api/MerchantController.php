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
        $meta['count_visit'] = \Cache::get('count:count_visit');
        $meta['index_top_banner'] = Picture::where('type', 1)->get()->pluck('thumbnail');
        $meta['navigation_bar'] = [
            [
                'name' => '本周下款王',
                'thumbnail' => 'https://daipub.oss-cn-beijing.aliyuncs.com/upload/201902/20190221/105732222.jpg',
                'url' => 'http://loans.test/api/new_loan_king'
            ],
            [
                'name' => '最新口子',
                'thumbnail' => '',
                'url' => 'https://daipub.oss-cn-beijing.aliyuncs.com/upload/201902/20190221/105222165.jpg'
            ],


        ];
        return $this->response->collection(Merchant::get(), new MerchantTransformer())->setMeta($meta);
    }

    public function newLoanKing()
    {
        $meta['banner_new_loan_king'] = 'https://www.torp.org/atque-dolor-dignissimos-aliquam-dolor';
        $meta['page'] = 'new_loan_king';
        return $this->response->collection(Merchant::get(), new MerchantTransformer())->setMeta($meta);
    }

    // 新口子
    public function newHoles()
    {
        $meta['page'] = 'new_holes';
        return $this->response->collection(Merchant::where('type')->get(), new MerchantTransformer())->setMeta($meta); 
    }

    public function todayRecommend()
    {
        $meta['page'] = 'new_today_recommend';
        return $this->response->collection(Merchant::get(), new MerchantTransformer())->setMeta($meta); 
    }

    // 各渠道点击数（每日每渠道每用户记一次）
    public function clicks(Request $request)
    {
        // etc: channel_clicks:2019-02-22-00(日期):1（channel_id）
        Redis::sadd('channel_clicks'.':'.Carbon::now()->toDateString().'-'.date ( "H").':'.$request->channel_id, $this->user()->phone);
        return $this->response->noContent();
    }
}
