<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantStatuses extends Model
{

    const TYPE_INDEX = 'index';
    const TYPE_NEW_LOAN_KING = 'new_loan_king';
    const TYPE_NEW_HOLES = 'new_holes';
    const TYPE_ORANGE_LOAN = 'orange_loan';

    public static $typeMap = [
        self::TYPE_INDEX   => '首页',
        self::TYPE_NEW_LOAN_KING => '最新下款王',
        self::TYPE_NEW_HOLES  => '最新口子',
        self::TYPE_ORANGE_LOAN  => '桔子贷',
    ];

    protected $guarded = [];
    
    public function merchant()
    {
        return $this->belongsTo('App\Models\Merchant', 'merchant_id');
    }


}
