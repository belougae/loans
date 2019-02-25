<?php

use App\Models\MerchantStatistic;
use Illuminate\Database\Seeder;

class MerchantStatisticTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchantStatistic = factory(MerchantStatistic::class)->times(15)->make();
        MerchantStatistic::insert($merchantStatistic->makeVisible([])->toArray());
    }
}
