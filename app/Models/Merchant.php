<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $guarded = [];

    public function statuses()
    {
        return $this->hasMany('App\Models\MerchantStatuses', 'merchant_id');
    }
}
