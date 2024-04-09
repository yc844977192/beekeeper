<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;

use App\Models\BaiduWordData;
use App\Models\DetectionTripartiteData;
use App\Models\PluggingData;
use App\Models\Type;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Facades\Admin;

class PluggingDataController extends AdminController {

    protected function title()
    {
        return trans('实时数据');
    }
    protected function grid()
    {
        $grid = new Grid(new PluggingData());
        $grid->model()->orderBy('id','desc');
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->column(1/2 ,function ($filter){
                $filter->like('company_name', '公司名');
                $filter->equal('business_region','所属节点')->select(function (){
                    return ['43.138.28.76'=>'北京节点','1.13.163.13'=>'南京节点','1.14.124.223'=>'成都节点','43.138.146.116'=>'广州节点'];
                });
            });
            $filter->column(1/2, function ($filter) {
                $filter->between('company_get_time','获取时间')->date();
            });
        });
        $grid->column('id', 'ID');
        $grid->column('company_name', '公司名称');
        $grid->column('company_url', '网址url')->display(function ($code){
            $str = "<a target='_blank' href='{$code}' style='overflow: hidden; display: block; text-overflow: ellipsis; white-space: nowrap;'>{$code}</a>";
            return $str;
        })->width(800);
        $grid->column('get_node.node_name', '所属节点');
        $grid->column('company_get_time', '入库时间');
        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            // 没有`delete-button`权限的角色不显示删除按钮
            if (!Admin::user()->can('delete-button')) {
                $actions->disableDelete();
            }
            $actions->disableView();
            $actions->disableEdit();
        });
        $grid->disableExport();
        return $grid;
    }
}