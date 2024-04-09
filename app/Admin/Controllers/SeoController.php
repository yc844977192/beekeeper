<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Models\Seo;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SeoController extends AdminController {

    protected function title()
    {
        return trans('首页SEO');
    }
    protected function grid()
    {
        $grid = new Grid(new Seo());
        $grid->model()->orderBy('id','desc');
        $grid->column('id', 'ID')->sortable();
        $grid->column('title','首页标题');
        $grid->column('keywords','关键字');
        $grid->column('description','描述');
        $grid->column('updated_at','上次更新时间');
        $grid->disableCreation();
        $grid->actions(function ($actions) {
            // 去掉删除
            $actions->disableDelete();
            // 去掉查看
            $actions->disableView();
        });
        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });
        $grid->disableFilter();
        $grid->disableExport();
        // 禁用显示字段按钮
        $grid->disableColumnSelector();
        // 禁用最前面的批量删除选择框
        $grid->disableRowSelector();
        return $grid;
    }
    public function form()
    {
        $form = new Form(new Seo());
        $form->text('website', '网站')->readonly();
        $form->text('title', 'Title')->rules('required');
        $form->text('keywords', '关键字')->rules('required');
        $form->textarea('description', '描述')->rules('required');
        $form->saving(function (Form $form) {
        });
        return $form;
    }
    protected function detail($id)
    {
        $show = new Show(Seo::findOrFail($id));
        $show->field('id', 'ID');
        $show->field('type_name', '类别名称');
        return $show;
    }
}