<?php

use App\Models\Merchant;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\MerchantStatistic::class, function (Faker $faker) {
    $now = Carbon::now()->toDateTimeString();

    $merchant = \App\Models\Merchant::query()->inRandomOrder()->first();
    return [
        'merchant_id' => $merchant->id,
        'count' => rand(100,10000),
        'created_at' => $now,
        'updated_at' => $now,
    ];
});
