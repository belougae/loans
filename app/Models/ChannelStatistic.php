<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelStatistic extends Model
{
    use Traits\ChannelStatisticHelper;

    protected $guarded = [];
    
    public function constant()
    {
        return $this->belongsTo('App\Models\Constant', 'channel_id');
    }
}
