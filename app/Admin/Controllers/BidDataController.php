<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Models\BData;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Show;


class BidDataController  extends AdminController {


    protected function title()
    {
        return trans('投标:');
    }
    protected function grid()
    {
        $grid = new Grid(new BData());
        $grid->filter(function($filter){
            $filter->between('buy_addtime','时间')->date();
            // 搜索多个字段
            $filter->where(function ($query) use ($filter) {
                $value = $this->input;
                if (isset($value)) {
                    $query->where('buy_project_name', 'like', "%" . $value . "%")
                        ->orWhere('buy_name', 'like', "%" . $value . "%")
                        ->orWhere('buy_phone', 'like', "%" . $value ."%");
                }
            }, '全局搜索');
        });
        $grid->column('id', 'ID');
        $grid->model()->orderBy('id','desc');
        $grid->column('buy_project_name', '采购项目');
        $grid->column('buy_name', '采购人');
        $grid->column('buy_addtime', '时间');
        $grid->column('buy_phone', '采购人联系方式');
        $grid->expandFilter(); // 默认打开筛选器
        $grid->tools(function (Grid\Tools $tools) {
            $js="<script>$(document).ready(function () {
                    $('.fields-group > div:first-child').remove();
                });</script>";
            $tools->append($js);
        });
        $grid->actions(function ($actions) {
            // 隐藏编辑按钮
            $actions->disableEdit();
            // 没有`delete-button`权限的角色不显示删除按钮
            if (!Admin::user()->can('delete-button')) {
                $actions->disableDelete();
            }
        });
        return $grid;
    }
    protected function detail($id)
    {
        $united = 'App\Models\BData';
        $show = new Show($united::findOrFail($id));
        $show->field('buy_title', '采购标题');
        $show->field('buy_name', '采购人');
        $show->field('buy_district', '采购人地址');
        $show->field('buy_addtime', '发布时间');
        $show->field('buy_project_name', '采购项目名称');
        $show->field('buy_phone', '采购人联系方式');
        $show->field('buy_gettime', '获取时间');
        $show->field('buy_agent_name', '采购代理机构名称');
        $show->field('buy_agent_phone', '采购代理机构联系方式');
        $show->field('buy_agent_district', '采购代理机构地址');
        $show->field('buy_project_id', '采购项目编号');
        $show->field('buy_project_contact', '采购项目联系人');
        $show->field('buy_project_phone', '采购项目联系电话');
        //$show->field('buy_info', '采集的详情内容')->row(5);
        $show->field('buy_info', '采集的详情内容')->unescape()->as(function ($value) {
            return $value;
        });
        return $show;
    }
}