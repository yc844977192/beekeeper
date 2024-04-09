<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Models\Construction;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ConstructionController extends AdminController {
    protected function title()
    {
        return trans('承接方');
    }
    protected function grid()
    {
        $grid = new Grid(new Construction());
        $grid->model()->orderBy('id','desc');
        $grid->filter(function (Grid\Filter $filter) {
            $filter->column(1/2 ,function ($filter){
                $filter->where(function ($query) {$query->where('construction_name', $this->input);}, '承接方');
            });
            $filter->column(1/2, function ($filter) {
                $filter->between('created_at','提交时间')->date();
            });
        });
        $grid->column('id', 'ID')->sortable();
        $grid->column('construction_name','承接方');
        $grid->column('created_at','提交时间');
        return $grid;
    }
    public function form()
    {
        $form = new Form(new Construction());
        $form->display('id', 'ID');
        $form->text('construction_name', '承接方')->rules('required');

        $form->saving(function (Form $form) {
        });
        return $form;
    }
    protected function detail($id)
    {
        $show = new Show(Construction::findOrFail($id));
        $show->field('id', 'ID');
        $show->field('construction_name', '承接方');
        return $show;
    }


}