<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Widgets\Box;

class ChartjsController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('商户访问量报表')
            ->body(new Box('Bar chart', view('admin.chartjs')));
    }
}
