<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Models\Detection;
use App\Models\Detection_list;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Facades\DB;

class DetectionListController extends AdminController {
    protected function title()
    {
        return trans('数据预挖');
    }
    public function index(Content $content){
        return $content
            ->title('数据预挖:')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append($this->showl());
                });
            });
    }
    protected function grid()
    {
        $grid = new Grid(new Detection_list());
        $grid->filter(function($filter) {
            $filter->column(1/2 ,function ($filter){
                $filter->where(function ($query) {$query->where('query_word','like', "%".$this->input."%");}, 'query_word');
            });
        });
        //$grid->model()->orderBy('query_time','desc');
        $grid->column('query_id', 'id');
        $grid->column('query_word', 'query_word');
//        $grid->column('query_show', '显示量');

//        $grid->column('query_count', 'averageMonthPv总量');
//        $grid->actions(function ($actions) {
//            // 隐藏编辑按钮
//            $actions->disableEdit();
//            // 没有`delete-button`权限的角色不显示删除按钮
//            if (!Admin::user()->can('delete-button')) {
//                $actions->disableDelete();
//            }
//        });
//        // 禁用新增、导出和筛选按钮
//        $grid->disableCreateButton();
//        $grid->disableExport();
//        $grid->disableFilter();
//        // 禁用显示字段按钮
//        $grid->disableColumnSelector();
//        // 禁用最前面的批量删除选择框
//        $grid->disableRowSelector();
//        // 删除操作列
//        $grid->disableActions();
        $grid->tools(function (Grid\Tools $tools) {
            $js="<script>$(document).ready(function () {
                    $('.fields-group > div:first-child').remove();
                });</script>";
            $tools->append($js);
        });
        $grid->expandFilter(); // 默认打开筛选器
        return $grid;
    }

    public function detection(){
        //检测数据
        $detection_list_tb=Db::table('admin_detection')->get()->toArray();
        $detection_query_times = [];
        $detection_query_total=[];
        foreach ($detection_list_tb as $k=>$v){
            $detection_list_tb[$k]=(array)$detection_list_tb[$k];
            array_push($detection_query_times,$detection_list_tb[$k]['query_time']);
            array_push($detection_query_total,$detection_list_tb[$k]['query_count']);
        }
        return view("Data.detection")
            ->with("detection_query_times",json_encode($detection_query_times))
            ->with("detection_query_total",json_encode($detection_query_total));
    }

    public function showl(){
//        $list=DB::connection("mysql_detection")->table('warehouse')->where("query_word","like","%鉴定%")->count();
//        var_dump($list);exit;
        $t=209;
        $start_date = strtotime('2023-08-09');
        $end_date = date('Y-m-d');
        $diff_time = abs(strtotime($end_date) - $start_date);
        $diff_days = ceil($diff_time / (60 * 60 * 24));
        $d["jc"]=209+$diff_days;
        $d["jjc"]=$diff_days;
        $d["pg"]=$diff_days;
        $d["jd"]=$diff_days;

        $list['jc']= number_format(814763/10000, 0, '.', '');
        $list['jjc']=number_format(125252/10000, 0, '.', '');
        $list['pg']=number_format(126732/10000, 0, '.', '');
        $list['jd']=number_format(367656/10000, 0, '.', '');

        return view("Admin/Detection/show")->with("list",$list)->with("d",$d);
    }

    public function getSelectWhere(){
        $data["name"]=$_POST["name"];
        $data["where"]=$_POST["where"];
        DB::table('detection_list_where')->insert($data);
        $msg["status"]=1;
        $msg["message"]="提交成功";
        return json_encode($msg);
    }
}