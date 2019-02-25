<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantStatistic extends Model
{
    public function merchant()
    {
        return $this->belongsTo('App\Models\Merchant');
    }
}
