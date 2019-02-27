<?php

namespace App\Admin\Controllers;

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
            ->body($this->grid());
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

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
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

        $grid->id('Id');
        $grid->merchant_id('商户名')->display(function ($value) {
            return "{$this->merchant->name}:{$this->merchant->key_name}" ;
        });
        $grid->statistic_at('日期');
        $grid->count('累计次数');
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
        });
        
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
        // 当天存在数据的商户的 key
        foreach (Redis::keys('channel_clicks:'.$now.':*') as $merchantKey) {
            $merchantexplod = explode(':', $merchantKey); 
            $merchantCount = Redis::scard($merchantKey);
            $result = MerchantStatistic::where('statistic_at', $now)->where('merchant_id', $merchantexplod[2])->get();
            if(!count($result)){
                $merchantStatistic = MerchantStatistic::insert([
                'count' => $merchantCount,
                'merchant_id' => $merchantexplod[2],
                'created_at' => $at,
                'statistic_at' => $now
                ]);
            }else{
                $merchantStatistic = MerchantStatistic::where('statistic_at', $now)->where('merchant_id', $merchantexplod[2])->update([
                    'count' => $merchantCount,
                    'updated_at' => $at
                    ]);
            }
        }

    }    
}
