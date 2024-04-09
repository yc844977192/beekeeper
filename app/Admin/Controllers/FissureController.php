<?php

namespace App\Admin\Controllers;


use App\Jobs\TripartitePlan;
use App\Models\Fissure;
use App\Models\Order;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class FissureController extends AdminController
{
    protected function title()
    {
        return trans('裂缝检测');
    }
    public function grid()
    {
        $grid = new Grid(new Fissure());
        $grid->model()->orderBy('id','desc');
//        $grid->filter(function (Grid\Filter $filter) {
//            $filter->column(1/2 ,function ($filter){
//
//            });
//            $filter->column(1/2, function ($filter) {
//                $filter->between('created_at','提交时间')->date();
//            });
//        });
        $grid->column('id', 'ID');
        $grid->column('order_code', '订单编号');
        $grid->column('proprietor_organization', '业主单位');
        $grid->column('proprietor_tel', '业主电话');
        $grid->column('construction_time', '施工时间');
//        $grid->column('order_status','状态')->display(function($code) {
//            if($code){
//                switch($code){
//                    case 0;return'未分配';
//                        break;
//                    case 1;return'进行中';
//                        break;
//                    case 2;return'已结束';
//                        break;
//                }
//            }
//            else{
//                return '未分配';
//            }
//
//        });
        return $grid;

    }
    public function form()
    {
        $form = new Form(new Fissure());
        if($form->isEditing()){
            $form->display('order_code', '订单编号');
        }
        $form->text('construction_organization', '施工单位');
        $form->text('construction_tel', '联系电话');
        $form->text('construction_address', '公司地址');
        $form->text('proprietor_organization', '业主单位');
        $form->text('proprietor_tel', '业主电话');
        $form->text('proprietor_address', '业主地址');
        $form->date("construction_time","施工时间")->format('YYYY-MM-DD HH:mm:ss')->width("230px");
       if($form->isCreating()){
            $form->saved(function (Form $form) {
                $id= $form->model()->id;
                $count=DB::table('admin_fissure')
                    ->whereDate('created_at', Carbon::today())
                    ->count();
                $str='LF'.date("Ymd")."-".str_pad($count, 4, '0', STR_PAD_LEFT);
                DB::table('admin_fissure')->where("id",$id)->update(["order_code"=>$str]);
            });
        }

        $form->text('component_num', '构件个数');
        $form->textarea('remark', '备注信息');
        return $form;
    }
    protected function detail($id)
    {
        $report =new Order();
        $show = new Show($report::findOrFail($id));
        $show->field('order_name', '订单名');
        $show->field('order_price', '价格');
        $show->field('order_status', '订单状态');
        $show->field('order_status', '订单状态')->unescape()->as(function ($value) {
            switch ($value){
                case 0;return "未分配";
                break;
                case 1;return "进行中";
                    break;
                case 3;return "已结束";
                    break;
            }

        });
        $show->field('staff_id', '指定给员工')->unescape()->as(function ($value) {
            $list=(array)DB::table("admin_users")->where("id",$value)->first();
            return $list["name"];

        });
        $show->field('customer_name', '客户姓名');
        $show->field('customer_tel', '客户电话');
        $show->field('province_id', '省')->unescape()->as(function ($value) {
            $list=(array)DB::table("admin_area")->where("id",$value)->first();
            return $list["area_name"];
        });
        $show->field('city_id', '市')->unescape()->as(function ($value) {
            $list=(array)DB::table("admin_area")->where("id",$value)->first();
            return $list["area_name"];
        });
        $show->field('district_id', '区')->unescape()->as(function ($value) {
            $list=(array)DB::table("admin_area")->where("id",$value)->first();
            return $list["area_name"];
        });
        $show->field('order_address', '详细地址');
        $show->field('to_time', '上门时间');
        $show->field('order_describe', '描述');
        return $show;
    }

}
