<?php

namespace App\Admin\Controllers;

use App\Models\Arithmetic;
use App\Models\Arithmetic_blacklist;
use App\Models\Wallet;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\DB;

class ArithmeticBlackListController extends AdminController
{
    protected function title()
    {
        return trans('算法黑名单');
    }

    public function grid()
    {
        $grid = new Grid(new Arithmetic_blacklist());
        $grid->model()->join('warehouse_by_detection', 'warehouse_by_detection.id', '=', 'arithmetic_blacklist.warehouse_id')
            ->select('arithmetic_blacklist.*','warehouse_by_detection.query_name as query_word');
        //$grid->model()->orderBy('time','desc');
        $grid->column('id', 'ID');
        $grid->column('query_word', '关键词');
        $grid->column('移除黑名单')->display(function($code) {
            $warehouse_id = $this->warehouse_id;
            return view("Arithmetic/arithmeticBlackList")->with("warehouse_id",$warehouse_id);
        });
        $grid->actions(function ($actions) {
            // 去掉查看
            $actions->disableView();
        });
        return $grid;
    }
    public function delete_blackList(){
        $warehouse_id=$_POST['warehouse_id'];
        $count=DB::table("arithmetic_blacklist")->where("warehouse_id",$warehouse_id)->count();

        if($count){
            DB::table("arithmetic_blacklist")->where(["warehouse_id"=>$warehouse_id])->delete();
        }
        return json_encode(["status"=>1]);
    }

}
