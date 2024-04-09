<?php

namespace App\Admin\Controllers;


use App\Jobs\TripartitePlan;
use App\Models\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class OrderController extends AdminController
{
    protected function title()
    {
        return trans('订单管理');
    }
    public function grid()
    {
        $grid = new Grid(new Order());
        $grid->model()->orderBy('id','desc');
        $grid->filter(function (Grid\Filter $filter) {
            $filter->column(1/2 ,function ($filter){

            });
            $filter->column(1/2, function ($filter) {
                $filter->between('created_at','提交时间')->date();
            });
        });
        $grid->column('id', 'ID')->sortable();
        $grid->column('order_name', '订单名');
        $grid->column('order_status','状态')->display(function($code) {
            if($code){
                switch($code){
                    case 0;return'未分配';
                        break;
                    case 1;return'进行中';
                        break;
                    case 2;return'已结束';
                        break;
                }
            }
            else{
                return '未分配';
            }

        });
        return $grid;

    }
    public function form()
    {
        $form = new Form(new Order());

        $form->text('order_name', '订单名');
        $form->text('order_price', '价格');
        $form->select('order_status', ('订单状态'))
            ->options(function (){
                return ['0'=>'未分配','1'=>'进行中','3'=>'已结束'];
            })
            ->rules('required', ['required' => '必填',]);
        $form->select('staff_id', '指定给员工') ->options(function (){
            return DB::table("admin_users")->pluck('name', 'id');
        });
        $form->text('customer_name', '客户姓名');
        $form->text('customer_tel', '客户电话');
        $form->distpicker(['province_id', 'city_id', 'district_id']);
        $form->textarea('order_address', '详细地址');
        $form->date("to_time","上门时间")->width("230px")->format('YYYY-MM-DD HH:mm:ss');
        $form->textarea('order_describe', '描述');
        return $form;
    }
    public function demo(){
        // 从数据库获取任务列表
        $tasks = DB::table('detection_tripartite_node')->get();

        foreach ($tasks as $task) {
            // 将任务添加到调度中
            new TripartitePlan($task->del_url);
        }
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
