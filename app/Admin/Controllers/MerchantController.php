<?php

namespace App\Admin\Controllers;

use App\Models\MerchantStatuses;
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
            ->header('商户详情')
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
        $grid->description('广告语')->limit(10);
        $grid->count_click('点击数');
        $grid->count_week('本周下款');
        $grid->created_at('创建时间');
        // $grid->sort('排序')->sortable();
        // $top = [
        //     'on'  => ['value' => 0, 'text' => '关闭', 'color' => 'default'],
        //     'off' => ['value' => 1, 'text' => '置顶', 'color' => 'primary'],
        // ];
        // $grid->top('置顶')->switch($top)->sortable();
        // $putaway = [
        //     'on'  => ['value' => 0, 'text' => '关闭', 'color' => 'default'],
        //     'off' => ['value' => 1, 'text' => '上架', 'color' => 'primary'],
        // ];
        // $grid->putaway('上架')->switch($putaway)->sortable();

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
        $show->min_limit('最小限额');
        $show->max_limit('最大限额');
        $show->description('广告语');
        $show->rate('利率');
        $show->url('链接');
        $show->label_first('标签1');
        $show->label_second('标签2');
        $show->label_third('标签3');
        $show->count_click('点击数');
        $show->count_week('本周下款');
        $show->statuses('状态', function ($statuses) {
            // 多选列
            $statuses->disableRowSelector();
            $statuses->expandFilter();
            $statuses->disableCreateButton();
            $statuses->disableFilter();
            $statuses->disablePagination();
            $statuses->disableExport();
            $statuses->disableActions();
            $statuses->type('类型')->display(function ($value) {
                return MerchantStatuses::$typeMap[$value];
            });
            $statuses->top('置顶')->display(function ($value) {
                return $value ? "<span class='label label-success'>已置顶</span>" : '未置顶';
                
            });
            $statuses->putaway('上架')->display(function ($value) {
                if($value === 'on'){
                    return "<span class='label label-success'>已上架</span>";
                }
                return '未上架';
            });
            $statuses->sort('排序');
            $statuses->created_at('创建时间');
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
        
        $form = new Form(new Merchant);

        $form->image('thumbnail', '图标')->move('/images/merchants');
        $form->text('key_name', '商户标识');
        $form->text('name', '名称');
        $form->number('min_limit', '最小限额');
        $form->number('max_limit', '最大限额');
        $form->text('description', '广告语');
        $form->decimal('rate', '利率');
        $form->url('url', '链接');
        $form->text('label_first', '标签1');
        $form->text('label_second', '标签2');
        $form->text('label_third', '标签3');
        $form->hasMany('statuses', '状态', function(Form\NestedForm $form) {
            $form->number('sort', '排序');
            $top = [
                'on'  => ['value' => 1, 'text' => '打开', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '关闭', 'color' => 'danger'],
            ];
            $form->switch('top', '置顶')->states($top);
            $putaway = [
                'on'  => ['value' => 'on', 'text' => '打开', 'color' => 'success'],
                'off' => ['value' => 'off', 'text' => '关闭', 'color' => 'danger'],
            ];
            $form->switch('putaway', '上架')->states($putaway);         
            $form->select('type', '图片类型')->options(MerchantStatuses::$typeMap);
        });
        return $form;
    }

    // 商户列表
    public function merchantGroup()
    {
        return collect((Merchant::paginate(null, ['id', 'name as text']))->all());
    }
}
