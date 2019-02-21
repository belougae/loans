<?php

use Illuminate\Database\Seeder;
use App\Models\Merchant;

class MerchantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {      
        $merchant = factory(Merchant::class)->times(10)->make();

        Merchant::insert($merchant->makeVisible([])->toArray());
    }
}
