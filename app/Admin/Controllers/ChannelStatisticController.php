<?php

namespace App\Admin\Controllers;

use App\Models\ChannelStatistic;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Carbon\Carbon;

class ChannelStatisticController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('渠道管理')
            ->body($this->grid());
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ChannelStatistic);
        // 关掉批量删除操作
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->expandFilter();
        $grid->disableActions();
        $grid->id('渠道id');
        $grid->channel_id('渠道名称')->display(function ($value) {
            return $this->constant->name;
        });
        $grid->count_visit('访问次数');
        $grid->count_register('注册人数');
        $grid->statistic_at('统计时间');
        $grid->filter(function($filter){
            $filter->column(1/2, function ($filter) {
                // Remove the default id filter
                $filter->disableIdFilter();
                // 设置datetime类型
                $filter->between('created_at', '日期')->date();
            });
            $filter->column(1/2, function ($filter) {
                $filter->equal('id', '渠道')->select('constants/group');
            });
        
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(ChannelStatistic::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ChannelStatistic);
        return $form;
    }

    // 商户统计定时
    public function timing()
    {
        $at = Carbon::now()->toDateTimeString();
        $now = Carbon::now()->toDateString();
        // 当天存在数据的商户的 key
        foreach (Redis::keys('channel_register:'.$now.'*') as $channelKey) {
            $channelExplod = explode(':', $channelKey); 
            $clock = explode('-', $channelExplod[1])[3];
            $channelCount = Redis::scard($channelKey);
            $result = ChannelStatistic::where('statistic_at', $now)->where('hour', $clock)->where('merchant_id', $channelExplod[2])->get();
            if(!count($result)){
                ChannelStatistic::insert([
                'count' => $channelCount,
                'merchant_id' => $channelExplod[2],
                'created_at' => $at,
                'statistic_at' => $now,
                'count_register' => $clock
                ]);
            }else{
                ChannelStatistic::where('statistic_at', $now)->where('merchant_id', $channelExplod[2])->update([
                    'count_register' => $channelCount,
                    'updated_at' => $at
                    ]);
            }
        }

    }
}
