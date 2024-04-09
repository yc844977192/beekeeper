<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class DataController extends Controller{

    public function index(Content $content)
    {
        return $content
            ->title($this->title())
            ->description("所有数据图表汇总")
            ->body($this->showIndex());
    }
    protected function title()
    {
        return "控制面板";
    }
    public function showIndex(){
        //大库
        $rubbings_list=(array)Db::table('baidu_query_count')->where("query_time",date("Y-m-d"))->first();
        //大库图表数据
        $rubbings_list_tb=Db::table('baidu_query_count')->get()->toArray();
        $rubbings_query_times = [];
        $rubbings_query_total=[];
        foreach ($rubbings_list_tb as $k=>$v){
            $rubbings_list_tb[$k]=(array)$rubbings_list_tb[$k];
            array_push($rubbings_query_times,$rubbings_list_tb[$k]['query_time']);
            array_push($rubbings_query_total,number_format($rubbings_list_tb[$k]['query_total']/100000000, 2));
        }
        //赋码数据
        $fuma_list=(array)Db::table('admin_fuma')->where("query_time",date("Y-m-d"))->first();
        $fuma_list_tb=Db::table('admin_fuma')->get()->toArray();
        $fuma_query_times = [];
        $fuma_query_total=[];
        foreach ($fuma_list_tb as $k=>$v){
            $fuma_list_tb[$k]=(array)$fuma_list_tb[$k];
            array_push($fuma_query_times,$fuma_list_tb[$k]['query_time']);
            array_push($fuma_query_total,$fuma_list_tb[$k]['query_count']);
        }
        //投标数据
        $bid_list=(array)Db::table('admin_bid')->where("query_time",date("Y-m-d"))->first();
        $bid_list_tb=Db::table('admin_bid')->get()->toArray();
        $bid_query_times = [];
        $bid_query_total=[];
        foreach ($bid_list_tb as $k=>$v){
            $bid_list_tb[$k]=(array)$bid_list_tb[$k];
            array_push($bid_query_times,$bid_list_tb[$k]['query_time']);
            array_push($bid_query_total,$bid_list_tb[$k]['query_count']);
        }
        //三方数据数据
        $tripartite_list=(array)Db::table('admin_tripartite')->where("query_time",date("Y-m-d"))->first();
        $tripartite_list_tb=Db::table('admin_tripartite')->get()->toArray();
        $tripartite_query_times = [];
        $tripartite_query_total=[];
        foreach ($tripartite_list_tb as $k=>$v){
            $tripartite_list_tb[$k]=(array)$tripartite_list_tb[$k];
            array_push($tripartite_query_times,$tripartite_list_tb[$k]['query_time']);
            array_push($tripartite_query_total,$tripartite_list_tb[$k]['query_count']);
        }
        //检测数据
        $detection_list=(array)Db::table('admin_detection')->where("query_time",date("Y-m-d"))->first();
        $detection_list_tb=Db::table('admin_detection')->get()->toArray();
        $detection_query_times = [];
        $detection_query_total=[];
        foreach ($detection_list_tb as $k=>$v){
            $detection_list_tb[$k]=(array)$detection_list_tb[$k];
            array_push($detection_query_times,$detection_list_tb[$k]['query_time']);
            array_push($detection_query_total,$detection_list_tb[$k]['query_count']);
        }
        //cdata关键词
        $data_keywords = Db::table('cdata_keyword')->get()->toArray();
        foreach ($data_keywords as $k=>$v){
            $data_keywords[$k]=(array)$data_keywords[$k];
        }
        //集合
        $data_keywords_list = Db::table('cdata_gather')->limit(20)->get()->toArray();
        foreach ($data_keywords_list as $k=>$v){
            $data_keywords_list[$k]=(array)$data_keywords_list[$k];
        }
        //检测全量
        $detection_gather_list_tb=Db::table('admin_dettction_gather')->get()->toArray();
        $detection_gather_query_times = [];
        $detection_gather_query_total=[];
        foreach ($detection_gather_list_tb as $k=>$v){
            $detection_gather_list_tb[$k]=(array)$detection_gather_list_tb[$k];
            array_push($detection_gather_query_times,$detection_gather_list_tb[$k]['query_time']);
            array_push($detection_gather_query_total,$detection_gather_list_tb[$k]['query_count']);
        }
        return view("Admin/Data/index")
            ->with("rubbings_list",$rubbings_list)
            ->with("rubbings_query_times",json_encode($rubbings_query_times))
            ->with("rubbings_query_total",json_encode($rubbings_query_total))
            ->with("fuma_list",$fuma_list)
            ->with("fuma_query_times",json_encode($fuma_query_times))
            ->with("fuma_query_total",json_encode($fuma_query_total))
            ->with("bid_list",$bid_list)
            ->with("bid_query_times",json_encode($bid_query_times))
            ->with("bid_query_total",json_encode($bid_query_total))
            ->with("tripartite_list",$tripartite_list)
            ->with("tripartite_query_times",json_encode($tripartite_query_times))
            ->with("tripartite_query_total",json_encode($tripartite_query_total))
            ->with("detection_list",$detection_list)
            ->with("detection_query_times",json_encode($detection_query_times))
            ->with("detection_query_total",json_encode($detection_query_total))
            ->with("detection_gather_query_times",json_encode($detection_gather_query_times))
            ->with("detection_gather_query_total",json_encode($detection_gather_query_total))
            ->with("data_keywords",$data_keywords)
            ->with("data_keywords_list",$data_keywords_list);


    }
}