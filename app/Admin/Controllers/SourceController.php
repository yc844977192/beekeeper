<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/20
 * Time: 9:43
 */
namespace App\Admin\Controllers;

use App\Models\Source;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SourceController extends AdminController {
    protected function title()
    {
        return trans('商机来源');
    }
    protected function grid()
    {
        $grid = new Grid(new Source());
        $grid->model()->orderBy('id','desc');
        $grid->filter(function (Grid\Filter $filter) {
            $filter->column(1/2 ,function ($filter){
                $filter->where(function ($query) {$query->where('source_name', $this->input);}, '类别名称');
            });
            $filter->column(1/2, function ($filter) {
                $filter->between('created_at','提交时间')->date();
            });
        });
        $grid->column('id', 'ID')->sortable();
        $grid->column('source_name','类别名称');
        $grid->column('created_at', '提交时间');
        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });
        return $grid;
    }
    public function form()
    {$form = new Form(new Source());
        $form->display('id', 'ID');
        $form->text('source_name', '类别名称')->rules('required');

        $form->saving(function (Form $form) {
        });
        return $form;
    }
    protected function detail($id)
    {
        $show = new Show(Source::findOrFail($id));
        $show->field('id', 'ID');
        $show->field('source_name', '类别名称');
        return $show;
    }
}