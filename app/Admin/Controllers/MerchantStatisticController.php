<?php

namespace App\Admin\Controllers;

use App\Models\MerchantStatistic;
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

        $grid->id('Id');
        $grid->merchant_id('商户名')->display(function ($value) {
            return "{$this->merchant->name}:{$this->merchant->key_name}" ;
        });
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
                $filter->between('created_at', '创建时间')->datetime();
                $filter->equal('channel_id', '渠道')->select('api/users');
            });
            $filter->column(1/2, function ($filter) {

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
}
