<?php

namespace App\Admin\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use App\Models\MerchantStatistic;
use App\Models\Merchant;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class MerchantStatisticController extends Controller
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
            ->header('商户访问统计')
            ->body($this->totalGrid());
    }

    public function hours(Content $content)
    {
        return $content
            ->header('商户访问统计（小时）')
            ->body($this->hoursGrid()); 
    }

    public function days(Content $content)
    {
        return $content
            ->header('商户访问统计（日）')
            ->body($this->daysGrid()); 
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
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
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    protected function hoursGrid()
    {
        $grid = new Grid(new MerchantStatistic);
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

        $grid->filter(function($filter){
            $filter->column(1/2, function ($filter) {
                // Remove the default id filter
                $filter->disableIdFilter();
                // 设置datetime类型
                $filter->between('created_at', '日期')->datetime();
            });
            $filter->column(1/2, function ($filter) {
                $filter->equal('merchant_id', '商户')->select('merchants/group');
            });
        
        });
        $grid->merchant_id('商户名')->display(function ($value) {
            return $this->merchant->name ;
        });
        $grid->disableActions();
        $grid->count('合计次数');
        $grid->statistic_at('统计时间')->display(function($value) {
            return $this->statistic_at.'('.$this->hour.': 00 : 00  至  '.++$this->hour.': 00 : 00 )';
        });

        return $grid;
    }

    protected function daysGrid()
    {
        $grid = new Grid(new MerchantStatistic);
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
        $grid->filter(function($filter){
            $filter->column(1/2, function ($filter) {
                // Remove the default id filter
                $filter->disableIdFilter();
                // 设置datetime类型
                $filter->between('created_at', '日期')->date();
            });
            $filter->column(1/2, function ($filter) {
                $filter->equal('merchant_id', '商户')->select('merchants/group');
            });
        
        });
        $grid->merchant_id('商户名')->display(function ($value) {
            return $this->merchant->name ;
        });

        $grid->model()->groupBy('merchant_id')->select([DB::raw("sum(count) as total"),'merchant_id']);
        // 不存的字段列
        $grid->column('counts', '合计次数')->display(function () {
            return $this->total;
        });
        $grid->disableActions();
        // $grid->count('合计次数');
        $grid->statistic_at('统计时间')->display(function($value) {
            return $this->statistic_at.'( 00 : 00 : 00  至  00 : 00 : 00 )';
        });

        return $grid;
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function totalGrid()
    {
        $grid = new Grid(new MerchantStatistic);
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
        $grid->model()->groupBy('merchant_id')->select([DB::raw("sum(count) as total"),'merchant_id']);
        $grid->merchant_id('商户名')->display(function ($value) {
            return $this->merchant->name ;
        });
        $grid->disableActions();
        // 不存的字段列
        $grid->column('counts', '合计次数')->display(function () {
            return $this->total;
        });
        $grid->filter(function($filter){
            $filter->column(1/2, function ($filter) {
                // Remove the default id filter
                $filter->disableIdFilter();
                // 设置datetime类型
                $filter->between('created_at', '日期')->date();
            });
            $filter->column(1/2, function ($filter) {
                $filter->equal('merchant_id', '商户')->select('statistics/merchants/group');
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
        $show = new Show(MerchantStatistic::findOrFail($id));

        $show->id('Id');
        $show->merchant_id('Merchant id');
        $show->count('Count');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new MerchantStatistic);

        $form->number('merchant_id', 'Merchant id');
        $form->number('count', 'Count');

        return $form;
    }

    // 商户统计定时
    public function timing()
    {
        $at = Carbon::now()->toDateTimeString();
        $now = Carbon::now()->toDateString();
        // dd(Redis::keys('channel_clicks:'.$now.'*'));
        // 当天存在数据的商户的 key
        foreach (Redis::keys('channel_clicks:'.$now.'*') as $merchantKey) {
            $merchantexplod = explode(':', $merchantKey); 
            $clock = explode('-', $merchantexplod[1])[3];
            $merchantCount = Redis::scard($merchantKey);
            $result = MerchantStatistic::where('statistic_at', $now)->where('hour', $clock)->where('merchant_id', $merchantexplod[2])->get();
            if(!count($result)){
                MerchantStatistic::insert([
                'count' => $merchantCount,
                'merchant_id' => $merchantexplod[2],
                'created_at' => $at,
                'statistic_at' => $now,
                'hour' => $clock

                ]);
            }else{
                MerchantStatistic::where('statistic_at', $now)->where('merchant_id', $merchantexplod[2])->update([
                    'count' => $merchantCount,
                    'updated_at' => $at
                    ]);
            }
        }

    }
}
