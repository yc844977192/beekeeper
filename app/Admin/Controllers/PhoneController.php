<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;

use App\Models\Member;
use App\Models\Phone;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class PhoneController  extends AdminController {
    protected function title()
    {
        return trans('电话显示');
    }
    public function grid()
    {
        $grid = new Grid(new Phone());
        $grid->column('id', 'ID');
        $grid->column('page', '页面');
        $grid->column('phone', '电话');
        $grid->column('created_at', '提交时间');
        $grid->actions(function ($actions) {
            // 没有`delete-button`权限的角色不显示删除按钮
            if (!Admin::user()->can('delete-button')) {
                $actions->disableDelete();

            }
        });
        $grid->disableExport();
        $grid->disableFilter();

        return $grid;
    }
    public function form()
    {
        $form = new Form(new Phone());
        $form->text('page', '页面');
        $form->text('phone', '电话');
        return $form;
    }
    protected function detail($id)
    {
        $show = new Show(Phone::findOrFail($id));
        $show->field('page', '页面');
        $show->field('phone', '电话');
        return $show;
    }


}