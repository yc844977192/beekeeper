<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Models\GetDetection;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Messages;

class GetDetectionController extends AdminController {
    protected function title()
    {
        return trans('数据预挖-查询申请');
    }
    protected function grid()
    {
        $grid = new Grid(new GetDetection());
        $grid->model()->orderBy('id','desc');
        $grid->column('id', 'ID')->sortable();
        $grid->column('name','姓名');
        $grid->column('where','条件');
        return $grid;
    }
    public function form()
    {
        $form = new Form(new GetDetection());
        $form->text('name', '姓名');
        $form->text('where', '条件');
        return $form;
    }
    protected function detail($id)
    {
        $show = new Show(GetDetection::findOrFail($id));
        $show->field('id', 'ID');
        $show->field('name', '姓名');
        $show->field('where', '条件');
        return $show;
    }
}