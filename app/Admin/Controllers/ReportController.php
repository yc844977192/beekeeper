<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Rubbings;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class ReportController extends AdminController
{
    protected function title()
    {
        return trans('更新日志');
    }
    public function index(Content $content){
        return $content
            ->title('更新日志:')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append($this->showWeek());
                });
                $row->column(12, function (Column $column) {
                    $column->append($this->grid());
                });
            });
    }
    protected function grid()
    {
        $grid = new Grid(new Report());
        $grid->model()->orderBy('created_at',"desc");
        $grid->column('id',"id");
        $grid->column('title',"标题");
        $grid->column('created_at',"提交时间");
        return $grid;

    }
    public function showWeek(){
        $list=(array)DB::table("admin_report")->orderBy('created_at',"desc")->first();
        return view("Admin/Report/index")->with("list",$list);
    }
    public function form()
    {
        $form = new Form(new Report());
        $form->text('title', '标题');
        $form->ueditor('week_plan', '更新日志');;
        return $form;
    }
    protected function detail($id)
    {
        $report =new Report();
        $show = new Show($report::findOrFail($id));
        $show->field('title', '标题');
        $show->field('week_plan', '更新日志');
        return $show;
    }
}