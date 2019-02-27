<?php

namespace App\Admin\Controllers;

use App\Models\Merchant;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class MerchantController extends Controller
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
            ->header('Index')
            ->description('description')
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
        $grid = new Grid(new Merchant);

        $grid->id('Id');
        $grid->thumbnail('图标')->image(config('app.url').'/uploads', 50, 50);
        $grid->key_name('商户标识');
        $grid->name('商户名称');
        $grid->max_limit('限额');
        $grid->description('广告语');
        $grid->rate('利息低至');
        $grid->count_click('点击数');
        $grid->count_week('本周下款');
        $grid->created_at('创建时间');

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
        $show = new Show(Merchant::findOrFail($id));

        $show->id('Id');
        $show->thumbnail('图标')->image(config('app.url').'/uploads/', 50, 50);
        $show->key_name('商户标识');
        $show->name('商户名称');
        $show->max_limit('限额');
        $show->description('广告语');
        $show->rate('利息低至');
        $show->url('推广URL');
        $show->label_first('标签1');
        $show->label_second('标签2');
        $show->label_third('标签3');
        $show->count_click('点击数');
        $show->count_week('本周下款');
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
        $form = new Form(new Merchant);

        $form->image('thumbnail', '图标')->move('/images/merchants');
        $form->text('key_name', '商户标识');
        $form->text('name', '名称');
        $form->number('max_limit', '限额');
        $form->text('description', '广告语');
        $form->decimal('rate', '利息低至');
        $form->url('url', '推广URL');
        $form->text('label_first', '标签1');
        $form->text('label_second', '标签2');
        $form->text('label_third', '标签3');
        $form->number('count_click', '创建时间');
        $form->number('count_week', '更新时间');

        return $form;
    }

    // 商户列表
    public function merchantGroup()
    {
        return collect((Merchant::paginate(null, ['id', 'name as text']))->all());
    }
}
