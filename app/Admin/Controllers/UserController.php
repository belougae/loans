<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class UserController extends Controller
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
            ->header('用户管理')
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
            ->header('用户详情')
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
            ->header('用户编辑')
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
        $grid = new Grid(new User);
        $grid->name('姓名');
        $grid->phone('手机号码');
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });
        // $grid->device_type('设备类型');
        // $grid->status('状态');
        
        $grid->channel_id('渠道');
        $grid->created_at('注册时间')->sortable();
        $grid->updated_at('最近活跃时间');
        $grid->filter(function($filter){
            $filter->column(1/2, function ($filter) {
                $filter->disableIdFilter();
                $filter->like('phone', '手机电话');
                $filter->like('status', '状态');
            });
            $filter->column(1/2, function ($filter) {
                $filter->like('device_type', '设备状态');
                $filter->between('created_at', '创建时间')->datetime();
                // $filter->equal('channel_id', '渠道')->select('');

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
        $show = new Show(User::findOrFail($id));
        $show->panel()
            ->tools(function ($tools) {
                $tools->disableDelete();
            });
        $show->name('用户名');
        $show->phone('手机号码');
        $show->created_at('注册时间');
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User);
        $form->text('name', '用户名');
        $form->mobile('phone', '手机号码');
        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
        });
        $form->footer(function ($footer) {
            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();
        
        });
        return $form;
    }
    
}
