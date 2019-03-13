<?php

namespace App\Models;

use App\Models\Traits\MerchantStatisticHelper;
use Illuminate\Database\Eloquent\Model;

class MerchantStatistic extends Model
{
    
    use MerchantStatisticHelper;
    protected $guarded = [];
    
    public function merchant()
    {
        return $this->belongsTo('App\Models\Merchant');
    }


}
