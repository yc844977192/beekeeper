<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Models\Detection_snapshot;
use App\Models\Supplier;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
class SnapshotController extends AdminController {
    protected function title()
    {
        return trans('快照');
    }
    protected function grid()
    {
        $supplier_id =$_GET["supplier_id"];
        $grid = new Grid(new Detection_snapshot());
        $grid->model()->orderBy('id','desc')->where("supplier_id",$supplier_id);
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