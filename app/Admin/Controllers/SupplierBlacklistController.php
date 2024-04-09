<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;

use App\Models\Supplier_blacklist;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Show;

class SupplierBlacklistController extends AdminController {

    protected function title()
    {
        return trans('黑名单');
    }
    protected function grid()
    {
        $grid = new Grid(new Supplier_blacklist());
        $grid->model()->orderBy('id','desc');
        $grid->column('id', 'ID');
        $grid->column('name', '过滤词');
        $grid->column('status','状态')->display(function($code) {
                if($code){
                    switch($code){
                        case 1;return'生效';
                            break;
                        case 0;return'未生效';
                            break;
                    }
                }
                else{
                    return '未设置';
                }

            });
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '更新时间');
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            // 没有`delete-button`权限的角色不显示删除按钮
            if (!Admin::user()->can('delete-button')) {
                $actions->disableDelete();
            }
            $actions->disableView();
        });
        return $grid;
    }
    public function form()
    {
        $form = new Form(new Supplier_blacklist());
        $form->text('name', '过滤词')->rules('required');
        $form->select('status', '状态')
            ->options(function (){
                return ['1'=>'生效','0'=>'未生效'];
            });
        return $form;
    }
    protected function detail($id)
    {
        $businessModel = new Supplier_blacklist();
        $show = new Show($businessModel::findOrFail($id));
        $show->field('id', 'ID');
        $show->field('name', '过滤词');
        $show->field('status', '状态');
        return $show;
    }
}