<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rubbings;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\DB;

class RubbingsController extends AdminController
{
    protected function title()
    {
        return trans('拓词监控');
    }
    public function index(Content $content){
        return $content
            ->title('大库数据:')
            ->description('数据显示')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append($this->rubbing());
                });
                $row->column(12, function (Column $column) {
                    $column->append($this->grid());
                });
            });
    }

    protected function grid()
    {
        $customerModel = 'App\Models\Rubbings';
        $grid = new Grid(new $customerModel());
        $grid->model()->orderBy('query_time','desc');
        $grid->column('query_time',"时间");
        //$grid->column('query_count',"百度返回量");
        $grid->column('query_sum',"新增入库量");
        $grid->column('query_select_num',"今日查询次数");
        //$grid->column('query_sum',"今日去重总次数");
        $grid->column('query_total',"词库总计");
        $grid->disableExport();
        $grid->disableCreateButton();
        $grid->disableFilter();
        //$grid->disableRowSelector();
        $grid->disableActions();
        $grid->disableColumnSelector();

        return $grid;

    }
    public function rubbing(){
        //大库图表数据
        $rubbings_list_tb=Db::table('baidu_query_count')->get()->toArray();
        $rubbings_query_times = [];
        $rubbings_query_total=[];
        foreach ($rubbings_list_tb as $k=>$v){
            $rubbings_list_tb[$k]=(array)$rubbings_list_tb[$k];
            array_push($rubbings_query_times,$rubbings_list_tb[$k]['query_time']);
            array_push($rubbings_query_total,number_format($rubbings_list_tb[$k]['query_total']/100000000, 2));
        }
        return view("Admin/Data/rubbing")
            ->with("rubbings_query_total",json_encode($rubbings_query_total))
            ->with("rubbings_query_times",json_encode($rubbings_query_times));
    }
}