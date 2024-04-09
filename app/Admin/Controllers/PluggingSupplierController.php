<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;

use App\Models\Plugging_supplier;
use App\Models\Tripartite_supplier;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Facades\Admin;

class PluggingSupplierController extends AdminController {

    protected function title()
    {
        return trans('实时数据');
    }
    protected function grid()
    {
        $grid = new Grid(new Plugging_supplier());
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
        });
        $grid->disableExport();
        return $grid;
    }
    public function form()
    {
        $form = new Form(new Plugging_supplier());
        $form->text('company_name', '公司名称')->rules('required');
        $form->text('company_url', 'url');
//        $form->text('company_title', '广告标题');
        $form->text('company_title_name', '广告标题');
        $form->text('company_get_time', '获取时间');
        $form->text('business_key', '业务关键词');

        return $form;
    }
    protected function detail($id)
    {
        $businessModel = new Plugging_supplier();
        $show = new Show($businessModel::findOrFail($id));
        $show->field('id', 'ID');
        $show->field('company_name', '公司名称');
        $show->field('company_url', 'url');
        $show->field('company_title_name', '广告标题');
        $show->field('company_get_time', '获取时间');
        $show->field('business_key', '业务关键词');
        return $show;
    }
    //更新所属行业类别,手动更新用
    public function updateSort(){
        $list=DB::table("admin_type")->get()->toArray();
        $api=new ApiController();
        foreach ($list as $k=>$v){

            $list[$k]=(array)$list[$k];
            $data['type_sort']=$api->getSortByTypeId($list[$k]['id']);
            DB::table("admin_type")->where('id',$list[$k]['id'])->update($data);
        }
    }


}