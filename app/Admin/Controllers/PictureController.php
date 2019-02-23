<?php

namespace App\Admin\Controllers;

use App\Models\Picture;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class PictureController extends Controller
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
            ->header('图片管理')
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
            ->header('详情')
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
            ->header('编辑')
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
            ->header('创建')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Picture);

        $grid->id('Id');
        $grid->thumbnail('图片')->image(config('app.url').'/uploads', 200, 200);
        $grid->name('图片名称');
        $grid->url('图片URL');
        // $grid->type('图片类型');
        $grid->column('type', ' 图片类型')->display(function ($value) {
            return Picture::$bannerTypeMap[$value];
        });
        $grid->created_at('创建时间');
        $grid->updated_at('更新时间');

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
        $show = new Show(Picture::findOrFail($id));

        $show->id('Id');
        $show->thumbnail('图片')->image(config('app.url').'/uploads/', 200, 200);
        $show->name('名称');
        $show->url('URL');
        $show->type('类型')->as(function ($value) {
            return Picture::$bannerTypeMap[$value];
        });
        $show->created_at('创建时间');
        $show->updated_at('更新时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Picture);

        $form->image('thumbnail', '图片')->move('/images/banners');
        $form->text('name', '图片名称');
        $form->url('url', '图片URL');
        $form->select('type', '图片类型')->options([ '其他', '首页顶部', '首页导航栏', '最新下款王']);
        return $form;
    }
}
