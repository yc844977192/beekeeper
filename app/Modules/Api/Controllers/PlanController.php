<?php
namespace App\Modules\Api\Controllers;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PlanController
{
    public function index(){
        Cache::forget("data_gather_sum");
        $now = Carbon::now();
        $expireTime = $now->setTime(3, 0, 0);
        if ($now >= $expireTime) {
            $expireTime->addDay();
        }
        Cache::remember('data_gather_sum', $expireTime, function () {
            $list=DB::table("admin_show_data")->get()->toArray();
            $data=[];
            foreach ($list as $k=>$v){
                $list[$k]=(array)$list[$k];
                $trend_count=DB::table("admin_trend")
                    ->join("warehouse_link_data","admin_trend.warehouse_id","=","warehouse_link_data.warehouse_id")
                    ->where("admin_trend.time",date("Y-m-d"))
                    ->where("warehouse_link_data.data_id",$list[$k]["id"])
                    ->count();
                $data[$list[$k]['id']]=$trend_count;
            }
            return $data;
        });

    }



}