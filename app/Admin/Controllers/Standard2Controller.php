<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Models\Standard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Show;


class Standard2Controller  extends AdminController {


    protected function title()
    {
        return trans('行业标准:');
    }
    protected function grid()
    {
        $grid = new Grid(new Standard());
        $grid->filter(function($filter){
            // 搜索多个字段
            $filter->where(function ($query) use ($filter) {
                $value = $this->input;
                if (isset($value)) {
                    $query->where('title', 'like', "%" . $value . "%");
                }
            }, '全局搜索');
        });
        $grid->column('id', 'ID');
        $grid->column('title', '名称');
//        $grid->column('industry', '行业分类');
//        $grid->column('charge_dept', '批准发布部门');
//        $grid->column('status', '标准状态');
        $grid->column('url', 'URL');
        $grid->column('file_path', 'PDF');
        $grid->expandFilter(); // 默认打开筛选器
        $grid->tools(function (Grid\Tools $tools) {
            $js="<script>$(document).ready(function () {
                    $('.fields-group > div:first-child').remove();
                });</script>";
            $tools->append($js);
        });

        $grid->actions(function ($actions) {
            // 隐藏编辑按钮
            $actions->disableView();
            $actions->disableEdit();
            $actions->disableDelete();
            // 没有`delete-button`权限的角色不显示删除按钮
            if (!Admin::user()->can('delete-button')) {
                $actions->disableDelete();
            }
        });
        return $grid;
    }
    protected function detail($id)
    {
        $standard = 'App\Models\Standard';
        $show = new Show($standard::findOrFail($id));
        $show->field('id', 'ID');
        $show->field('ch_name', '名称');
        $show->field('charge_dept', '批准发布部门');
        $show->field('code', '标准号');
        $show->field('industry', '行业分类');
        $show->field('record_no', '备案号');
        $show->field('revise_std_codes', '代替标准')->raw(5);
        $show->field('status', '标准状态');
        //https://hbba.sacinfo.org.cn/stdDetail/
        $show->field('pk', 'URL')->unescape()->as(function ($value) {
            // 根据 id 查询显示名称，并返回
            return "https://hbba.sacinfo.org.cn/attachment/onlineRead/".$value;
        });
        return $show;
    }


}