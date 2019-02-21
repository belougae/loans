<?php

namespace App\Http\Controllers\Api;

use App\Transformers\MerchantTransformer;
use Illuminate\Http\Request;
use App\Models\Merchant;


class MerchantController extends Controller
{
    public function index()
    {

        \Cache::increment('count:count_visit', 1);
        $meta['hot_ad'] = '建议申请3家以上，100% 一小时内到帐';
        $meta['count_visit'] = \Cache::get('count:count_visit');;
        $meta['top_banner'] = [
            'https://www.torp.org/atque-dolor-dignissimos-aliquam-dolor1',
            'https://www.torp.org/atque-dolor-dignissimos-aliquam-dolor2',
            'https://www.torp.org/atque-dolor-dignissimos-aliquam-dolor3',
            'https://www.torp.org/atque-dolor-dignissimos-aliquam-dolor4',
            'https://www.torp.org/atque-dolor-dignissimos-aliquam-dolor5',
        ];
        $meta['navigation_bar'] = [
            [
                'name' => '本周下款王',
                'thumbnail' => 'https://www.torp.org/atque-dolor-dignissimos-aliquam-dolor',
                'url' => 'http://loans.test/api/week_king'
            ],
            [
                'name' => '最新口子',
                'thumbnail' => 'https://www.torp.org/atque-dolor-dignissimos-aliquam-dolor',
                'url' => 'http://loans.test/api/new_holes'
            ],


        ];
        return $this->response->collection(Merchant::get(), new MerchantTransformer())->setMeta($meta);
    }

    public function newLoanKing()
    {
        $meta['banner_new_loan_king'] = 'https://www.torp.org/atque-dolor-dignissimos-aliquam-dolor';
        return $this->response->collection(Merchant::get(), new MerchantTransformer())->setMeta($meta);
    }

    public function newHoles()
    {
        return $this->response->collection(Merchant::get(), new MerchantTransformer()); 
    }

    public function todayRecommend()
    {
        return $this->response->collection(Merchant::get(), new MerchantTransformer()); 
    }
}
