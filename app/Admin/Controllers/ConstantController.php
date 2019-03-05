<?php

namespace App\Admin\Controllers;

use App\Models\Constant;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ConstantController extends Controller
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
            ->header('渠道信息管理')
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
        $grid = new Grid(new Constant);
        // 关掉批量删除操作
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        // 导出
        $grid->disableExport();
        // 多选列
        $grid->disableRowSelector();
        $grid->expandFilter();

        $grid->filter(function($filter){
            $filter->column(1/2, function ($filter) {
                $filter->between('created_at', '创建日期')->datetime();
            });
            $filter->column(1/2, function ($filter) {
                $filter->equal('name', '渠道名称');
                $filter->equal('cons_key', '渠道标识');
            });
        
        });

        $grid->id('ID');
        $grid->cons_key('渠道标识');
        $grid->cons_desc('渠道描述');
        $grid->name('渠道名称');
        $grid->created_at('创建时间');
        $grid->updated_at('修改时间');

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
        $show = new Show(Constant::findOrFail($id));

        $show->id('Id');
        $show->parent_id('Parent id');
        $show->cons_key('Cons key');
        $show->cons_desc('Cons desc');
        $show->name('Name');
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
        $form = new Form(new Constant);

        $form->text('cons_key', '渠道标识');
        $form->text('cons_desc', '渠道描述');
        $form->text('name', '渠道名称');

        return $form;
    }



    public function constantGroup()
    {
        return collect((Constant::paginate(null, ['id', 'name as text']))->all());
    }
}
