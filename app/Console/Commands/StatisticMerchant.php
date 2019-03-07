<?php

namespace App\Console\Commands;

use App\Models\MerchantStatistic;
use Illuminate\Console\Command;

class StatisticMerchant extends Command
{

    protected $signature = 'loans:statistic-merchant';

    protected $description = '统计商户';
    
    public function handle(MerchantStatistic $merchantStatistic)
    {
        $this->info("开始从 Redis 中计算...");
        $merchantStatistic->timing();
        $this->info("Mysql 商户数据成功生成！"); 
    }
}
