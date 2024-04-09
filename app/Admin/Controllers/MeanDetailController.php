<?php

namespace App\Admin\Controllers;


use App\Models\Trend;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\DB;

class MeanDetailController extends AdminController
{
    protected function title()
    {
        return trans('商机发现-算法');
    }

    public function grid()
    {
        $grid = new Grid(new Trend());
        $grid->model()->orderBy('time','desc');
        $grid->model()->orderBy('id','desc');
        $grid->model()
            ->join('warehouse_by_detection', 'admin_trend.warehouse_id', '=', 'warehouse_by_detection.id')
            ->join('warehouse_link_data', 'admin_trend.warehouse_id', '=', 'warehouse_link_data.warehouse_id')
            ->where('warehouse_link_data.data_id', $_GET['data_id'])
            ->select('warehouse_by_detection.query_name','admin_trend.*')
            ->whereNotIn('admin_trend.warehouse_id', function ($query) {
                $query->select('warehouse_id')
                    ->from('arithmetic_blacklist');
            });
        $grid->filter(function (Grid\Filter $filter) {
            $filter->where(function ($query) {
                $list=$this->input;
                if ($list) {
                    foreach ($list as $k => $v) {
                        $query->Where($list[$k], 1);
                    }
                }
            }, 'TAG-AND', 'tag')-> checkbox([
                'trend_status' => '均值突破',
                'up_status' => '稳定上升5天',
                'up_status_10' => '稳定上升10天',
                'up_status_15' => '稳定上升15天',
                'is_new' => 'NEW',
                'is_new_high' => '新高',
            ]);
            $filter->where(function ($query) {
                $list=$this->input;
                if ($list) {
                    foreach ($list as $k => $v) {
                        $query->orWhere($list[$k], 1);
                    }
                }
            }, 'TAG-OR', 'tag1')-> checkbox([
                'trend_status' => '均值突破',
                'up_status' => '稳定上升5天',
                'up_status_10' => '稳定上升10天',
                'up_status_15' => '稳定上升15天',
                'is_new' => 'NEW',
                'is_new_high' => '新高',
            ]);
            $filter->column(1/2, function ($filter) {
                $filter->between('time','时间')->date();
            });
        });
        $grid->column('id', 'ID');
        $grid->column('query_name', '词')->display(function($code) {
            $id = $this->warehouse_id;
            $a="/admin/keywords/showData?keyword_id=".$id;
            $url='<a target="_blank" href="'.$a.'">'.$code.'</a>';
            return $url;
        });
        $grid->column('', 'TAG')
            ->display(function($code) {
                $id = $this->id;
                $str="";
                if($this->trend_status){
                    $str=$str."<span style='background-color:#70B603;border-radius: 5px;font-size: 11px;padding-left:3px;padding-right:3px;padding-top:3px;padding-bottom:3px;color: white'>均值突破</span>&nbsp;&nbsp;";
                }

                if($this->up_status==1){
                    $str=$str."<span style='background-color:#00BFBF;border-radius: 5px;font-size: 11px;padding-left:3px;padding-right:3px;padding-top:3px;padding-bottom:3px;color: white'>稳定上升5天</span>&nbsp;&nbsp;";
                }
                if($this->up_status==2){
                    $str=$str."<span style='background-color:#BFBF00;border-radius: 5px;font-size: 11px;padding-left:3px;padding-right:3px;padding-top:3px;padding-bottom:3px;color: white'>显著上升</span>&nbsp;&nbsp;";
                }
                if($this->up_status_10==1){
                    $str=$str."<span style='background-color:#00BFBF;border-radius: 5px;font-size: 11px;padding-left:3px;padding-right:3px;padding-top:3px;padding-bottom:3px;color: white'>连续上升10天</span>&nbsp;&nbsp;";
                }
                if($this->up_status_15==1){
                    $str=$str."<span style='background-color:#00BFBF;border-radius: 5px;font-size: 11px;padding-left:3px;padding-right:3px;padding-top:3px;padding-bottom:3px;color: white'>连续上升15天</span>&nbsp;&nbsp;";
                }
                if($this->is_new==1){
                    $str=$str."<span style='background-color:#A30014;border-radius: 5px;font-size: 11px;padding-left:3px;padding-right:3px;padding-top:3px;padding-bottom:3px;color: white'>NEW!</span>&nbsp;&nbsp;";
                }
                if($this->is_new_high==1){
                    $str=$str."<span style='background-color:#B8741A;border-radius: 5px;font-size: 11px;padding-left:3px;padding-right:3px;padding-top:3px;padding-bottom:3px'>新高</span>&nbsp;&nbsp;";
                }
                return $str;
            });
        $grid->column('time', 'time');
        $grid->column('添加黑名单')->display(function($code) {
            $str = "<button onclick='insertBlackList(" . $this->warehouse_id . ")'>+</button>";
            return $str;
        });
        $grid->column('getArithmetic.data', '缩略图')
            ->display(function($code) {
                if($code){
                    return view("Admin/Arithmetic/thumbnail")->with("str",trim($code, '[]'))->with("trend_name",$this->warehouse_id);
                }

            });
        //$grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableRowSelector();
        //$grid->disableActions();
        $grid->disableColumnSelector();
        $grid->actions(function ($actions) {
            // 去掉删除
            //$actions->disableDelete();

            // 去掉编辑
            $actions->disableEdit();

            // 去掉查看
            $actions->disableView();
        });
        $grid->tools(function (Grid\Tools $tools) {
            $js="<script>$(document).ready(function () {
                    $('.fields-group > div:first-child').remove();
                });</script>";
            $tools->append($js);
        });
        return $grid;
    }
    public function insert_black_list(){
        $warehouse_id=$_POST['warehouse_id'];
        $count=DB::table("arithmetic_blacklist")->where("warehouse_id",$warehouse_id)->count();
        if(!$count){
            DB::table("arithmetic_blacklist")->insert(["warehouse_id"=>$warehouse_id]);
        }
        return json_encode(["status"=>1]);
    }


}
