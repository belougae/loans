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
            ->header('商户列表')
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

        // 多选列
        $grid->disableRowSelector();
        $grid->expandFilter();


        $grid->filter(function($filter){
            $filter->column(1/2, function ($filter) {
                // 设置datetime类型
                $filter->between('created_at', '创建日期')->date();
            });
            $filter->column(1/2, function ($filter) {
                $filter->equal('merchant_id', '商户')->select('statistics/merchants/group');
            });
        
        });

        $grid->name('名称');
        $grid->thumbnail('LOGO')->image(config('app.url').'/uploads', 50, 50);
        $grid->url('链接')->limit(20);
        $grid->key_name('唯一标识');
        $grid->description('广告语')->limit(10);
        $grid->count_click('点击数');
        $grid->count_week('本周下款');
        $grid->created_at('创建时间');
        $grid->sort('排序')->sortable();
        $top = [
            'on'  => ['value' => 0, 'text' => '关闭', 'color' => 'default'],
            'off' => ['value' => 1, 'text' => '置顶', 'color' => 'primary'],
        ];
        $grid->top('置顶')->switch($top)->sortable();
        $putaway = [
            'on'  => ['value' => 0, 'text' => '关闭', 'color' => 'default'],
            'off' => ['value' => 1, 'text' => '上架', 'color' => 'primary'],
        ];
        $grid->putaway('上架')->switch($putaway)->sortable();

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
        $show->sort('排序');
        $show->top('置顶');
        $show->putaway('上线');
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
        $form->number('sort', '排序');
        $form->switch('top', '置顶')->states([0 => '不置顶', 1 => '置顶']);
        $form->switch('putaway', '上线')->states([0 => '下线', 1 => '上线']);
        return $form;
    }

    // 商户列表
    public function merchantGroup()
    {
        return collect((Merchant::paginate(null, ['id', 'name as text']))->all());
    }
}
