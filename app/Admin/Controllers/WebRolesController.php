<?php

namespace App\Admin\Controllers;

use App\Models\Roles;
use App\Models\ShowData;
use App\Models\WebRoles;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Form;


class WebRolesController extends AdminController
{
    protected function title()
    {
        return trans('官网-角色设置');
    }

    public function grid()
    {
        $grid = new Grid(new WebRoles());
        $grid->model()->orderBy('id','desc');
        $grid->column('id', 'ID');
        $grid->column('title', 'title');
        $grid->column('created_at', 'create_time');
        $grid->disableExport();
        $grid->disableCreateButton();
        $grid->disableFilter();
        //$grid->disableRowSelector();
        //$grid->disableActions();
        $grid->disableColumnSelector();
        $grid->actions(function ($actions) {
            // 隐藏编辑按钮
            $actions->disableView();
            if (!Admin::user()->can('delete-button')) {
                $actions->disableDelete();
            }
        });
        return $grid;
    }
    public function form()
    {
        $form = new Form(new WebRoles());
        $form->text('title', 'title');
        $form->textarea("content","content");
        $form->textarea("role_description","role_description");
        $form->file('avatar','头像上传')->move('/uploads/image/'.date("Y").'/'.date("m").'/'.date("d"));
        $form->select('status', "状态")
            ->options(function (){
                return ['0'=>'关闭','1'=>'开启'];
            });
        $form->text('sort', '排序');
        return $form;
    }

}
