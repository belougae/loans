<?php

namespace App\Console\Commands;

use App\Models\ChannelStatistic;
use Illuminate\Console\Command;

class StatisticChannel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:statistic-channel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '统计渠道';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(ChannelStatistic $channelStatistic)
    {
        $this->info("开始从 Redis 中计算...");

        $channelStatistic->timing();

        $this->info("Mysqlz 渠道数据成功生成！"); 
    }
}
