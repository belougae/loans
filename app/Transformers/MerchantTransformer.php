<?php

namespace App\Transformers;

use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Api\Controller;
use App\Models\Merchant;
use League\Fractal\TransformerAbstract;

class MerchantTransformer extends TransformerAbstract
{
    use Helpers;
    public function transform(Merchant $merchant)
    {
        return [
            'id' => (int)$merchant->id,
            'thumbnail' => $merchant->thumbnail,
            'name' => $merchant->name,
            'min_limit' => $merchant->min_limit,
            'max_limit' => $merchant->max_limit,
            'description' => $merchant->description,
            'rate' => $merchant->rate,
            'url' => $merchant->url,
            'label_first' => $merchant->label_first,
            'label_second' => $merchant->label_second,
            'label_third' => $merchant->label_third,
            'online_at' => $merchant->online_at
        ];
    }
}