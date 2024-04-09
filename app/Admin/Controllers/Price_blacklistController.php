<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;

use App\Models\Price_blacklist;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\DB;

class Price_blacklistController extends AdminController {
    protected function title()
    {
        return trans('比价黑名单');
    }
    public function grid()
    {
        $grid = new Grid(new Price_blacklist());
        $grid->model()->orderBy('id','desc');
        $grid->filter(function($filter){

        });
        $grid->column('id', 'ID');
        $grid->column('keywords', '关键字');

        $grid->column('created_at', '提交时间');
        $grid->column('updated_at', '修改时间');
        //$grid->disableCreateButton();
        //$grid->disablePagination();
        //$grid->disableFilter();
        $grid->disableExport();
        $grid->disableRowSelector();
        //$grid->disableActions();
        $grid->disableColumnSelector();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            // 没有`delete-button`权限的角色不显示删除按钮
            if (!Admin::user()->can('delete-button')) {
                $actions->disableDelete();
            }

        });
        $grid->disableFilter();
        return $grid;
    }
    public function form()
    {
        $form = new Form(new Price_blacklist());

        $form->text('keywords', '关键字');

        //$form->password('password', '密码');
        $form->tools(function (Form\Tools $tools) {

            // 去掉`查看`按钮
            $tools->disableView();
        });
        //保存后回调
        $form->saved(function (Form $form) {
            $id=$form->model()->id;
            $list=(array)DB::table('admin_price_blacklist')->where('id',$id)->first();
            DB::table('baidu_words_sphere')->where("company_name",$list['keywords'])->delete();
        });
        return $form;
    }
}