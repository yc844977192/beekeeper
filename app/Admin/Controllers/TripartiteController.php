<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Models\Bid;
use App\Models\Tripartite;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Facades\DB;

class TripartiteController extends AdminController {


    protected function title()
    {
        return trans('检测行业三方竞品公司数据:');
    }
    public function index(Content $content){
        return $content
            ->title('检测行业三方竞品公司数据:')
            ->description('数据显示')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append($this->showTripartite());
                });
                $row->column(12, function (Column $column) {
                    $column->append($this->grid());
                });
            });
    }
    protected function grid()
    {
        $grid = new Grid(new Tripartite());
        $grid->model()->orderBy('query_time','desc');
        $grid->column('query_time', '时间');
        $grid->column('query_count', '总量');
        $grid->column('query_simple_count', '今日新增');
        $grid->actions(function ($actions) {
            // 隐藏编辑按钮
            $actions->disableEdit();
            // 没有`delete-button`权限的角色不显示删除按钮
            if (!Admin::user()->can('delete-button')) {
                $actions->disableDelete();
            }
        });
        // 禁用新增、导出和筛选按钮
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableFilter();
        // 禁用显示字段按钮
        $grid->disableColumnSelector();
        // 禁用最前面的批量删除选择框
        $grid->disableRowSelector();
        // 删除操作列
        $grid->disableActions();
        return $grid;
    }
    public function showTripartite(){
        set_time_limit(0);
        //时间轴
        $times = DB::table("data_tripartite_data")
            ->select("time")
            ->distinct()
            ->get()->toArray();
        $totime=[];
        foreach ($times as $k=>$v){
            $times[$k]=(array)$times[$k];
            array_push($totime,$times[$k]['time']);
        }
        //上海
        $todata_sh=[];
        $data_sh=DB::table("data_tripartite_data")->where("area","上海")->get()->toArray();
        foreach ($data_sh as $k=>$v){
            $data_sh[$k]=(array)$data_sh[$k];
            array_push($todata_sh,$data_sh[$k]['sum']);
        }

        //南京
        $todata_nj=[];
        $data_nj=DB::table("data_tripartite_data")->where("area","南京")->get()->toArray();
        foreach ($data_nj as $k=>$v){
            $data_nj[$k]=(array)$data_nj[$k];
            array_push($todata_nj,$data_nj[$k]['sum']);
        }

        //济南
        $todata_jn=[];
        $data_jn=DB::table("data_tripartite_data")->where("area","济南")->get()->toArray();
        foreach ($data_jn as $k=>$v){
            $data_jn[$k]=(array)$data_jn[$k];
            array_push($todata_jn,$data_jn[$k]['sum']);
        }
        //广州
        $todata_gz=[];
        $data_gz=DB::table("data_tripartite_data")->where("area","广州")->get()->toArray();
        foreach ($data_gz as $k=>$v){
            $data_gz[$k]=(array)$data_gz[$k];
            array_push($todata_gz,$data_gz[$k]['sum']);
        }


        //按业务
        $data2=DB::table("data_tripartite_by_type")->get()->toArray();
        foreach ($data2 as $k=>$v){
            $data2[$k]=(array)$data2[$k];
        }

        //按地域
        $data=DB::table("data_tripartite_by_area")->get()->toArray();
        foreach ($data as $k=>$v){
            $data[$k]=(array)$data[$k];
        }
       return view("Admin/Data/showTripartite")
           ->with("data",json_encode($data,JSON_UNESCAPED_UNICODE))
           ->with("data2",json_encode($data2,JSON_UNESCAPED_UNICODE))
           ->with("times",json_encode($totime))
           ->with("data_sh",json_encode($todata_sh))
           ->with("data_nj",json_encode($todata_nj))
           ->with("data_jn",json_encode($todata_jn))
           ->with("data_gz",json_encode($todata_gz));
    }
}