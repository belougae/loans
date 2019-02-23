<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    const OTHER_BANNER = 0;
    const INDEX_TOP_BANNER = 1;
    const INDEX_NAV_BANNER = 2;
    const NEW_KING_BANNER = 3;

    public static $bannerTypeMap = [
        self::OTHER_BANNER   => '其他',
        self::INDEX_TOP_BANNER => '首页顶部',
        self::INDEX_NAV_BANNER  => '首页导航栏',
        self::NEW_KING_BANNER  => '最新下款王',
    ];
}
