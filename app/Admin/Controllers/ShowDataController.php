<?php

namespace App\Admin\Controllers;


use App\Models\ShowData;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ShowDataController extends AdminController
{
    protected function title()
    {
        return trans('数据可视化');
    }
    public function grid()
    {
        $grid = new Grid(new showData());
        $grid->model()->orderBy('id','desc');
        $grid->column('id', 'ID');
        $grid->column('data_name', 'dataName')->display(function($code) {
            $id = $this->id;
            $a="/admin/meanDetail?data_id=".$id;
            $url='<a target="_blank" href="'.$a.'">'.$code.'</a>';
            return $url;
        });
        $grid->column('data_key', 'dataKey');
        $grid->column('status', 'status')->display(function($code) {
                if($code==0){
                    return '<span style="color: red">Off</span>';
                }
                if($code==1){
                    return 'On';
                }
            });
        $grid->column('remark', 'remark');
        $grid->column('', '今日发现')
            ->display(function() {
                $list=Cache::get("data_gather_sum");
                return $list[$this->id];
            });
        $grid->column('created_at', 'create_time');
        $grid->actions(function ($actions) {

            // 去掉删除
            $actions->disableDelete();

            // 去掉编辑
            $actions->disableEdit();

            // 去掉查看
            $actions->disableView();
        });
        return $grid;
    }

    public function setSumCache(){

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
