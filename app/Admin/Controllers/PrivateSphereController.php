<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Models\Private_sphere;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PrivateSphereController extends AdminController {
    protected function title()
    {
        return trans('私域');
    }
    protected function grid()
    {
        $grid = new Grid(new Private_sphere());
        $grid->model()->orderBy('id','desc');
        $grid->filter(function (Grid\Filter $filter) {
            $filter->column(1/2 ,function ($filter){
                $filter->where(function ($query) {$query->where('private_sphere_name', $this->input);}, '私域名称');
            });
            $filter->column(1/2, function ($filter) {
                $filter->between('created_at','提交时间')->date();
            });
        });
        $grid->column('id', 'ID')->sortable();
        $grid->column('private_sphere_name','私域名称');
        $grid->column('created_at','提交时间');
        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });
        return $grid;
    }
    public function form()
    {
       $form = new Form(new Private_sphere());
        $form->display('id', 'ID');
        $form->text('private_sphere_name', '私域名称')->rules('required');

        $form->saving(function (Form $form) {
        });
        return $form;
    }
    protected function detail($id)
    {
        $show = new Show(Private_sphere::findOrFail($id));
        $show->field('id', 'ID');
        $show->field('private_sphere_name', '私域名称');
        return $show;
    }
}