<?php

namespace App\Admin\Controllers;


use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GatherController extends AdminController
{
    protected function title()
    {
        return trans('趋势洞察');
    }
    public function index(Content $content){
        return $content
            ->title('趋势洞察:')
            ->description('图表显示')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append($this->showGather());
                });
            });
    }
    public function  showGather(){
        //$data=DB::table("data_gather_into_data")->get()->toArray();

        $now = Carbon::now();
        $expireTime = $now->setTime(3, 0, 0);
        if ($now >= $expireTime) {
            $expireTime->addDay();
        }
        $data = Cache::remember('data_gather', $expireTime, function () {
            return DB::table("data_gather_into_data")->get()->toArray();
        });
        unset($data[2]);
        $data2=[];
        foreach ($data as $k=>$v) {
            $data[$k] = (array)$data[$k];
            $data2[$data[$k]['key']] = [
                "time" => $data[$k]['time'],
                "data" =>(string)$data[$k]['data']
            ];
        }
        return view("Admin.Data.gather")
            ->with("data",$data2);
    }
    //处理锚似
    public function delOneDayDate(){
        $list=DB::table("data_gather_into_data")->get()->toArray();
        foreach ($list as $k=>$v){

            $list[$k]=(array)$list[$k];
            $arr_time=json_decode($list[$k]["time"]);
            $arr_date=json_decode($list[$k]["data"]);

            $temp_time=[];
            $temp_data=[];
            foreach ($arr_time as $kk=>$vv){

                if($arr_time[$kk]=="2023-12-19"){
                   unset($arr_time[$kk]);
                   unset($arr_date[$kk]);
                }
            }
            $temp_time = array_merge($temp_time,$arr_time);
            $temp_data= array_merge($temp_data,$arr_date);
            $into['time']=json_encode($temp_time);
            $into['data']=json_encode($temp_data);
            DB::table("data_gather_into_data")->where("id",$list[$k]['id'])->update($into);


        }



    }
}
