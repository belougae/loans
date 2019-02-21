<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(App\Models\Merchant::class, function (Faker $faker) {
    $now = Carbon::now()->toDateTimeString();
    return [
        'thumbnail' => $faker->url,
        'key_name' => $faker->name,
        'name' => $faker->name,
        'max_limit' => 2000,
        'description' => $faker->name,
        'rate'=> rand(0.3, 0.7),
        'url' => $faker->url,
        'label_first' => $faker->name,
        'label_second' => $faker->name,
        'label_third' => $faker->name,
        'count_click' => rand(100,10000),
        'created_at' => $now,
        'updated_at' => $now,
    ];
});
