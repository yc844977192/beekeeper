<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;

use App\Models\Call;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\DB;

class CallController extends AdminController {
    protected function title()
    {
        return trans('客服电话');
    }
    public function grid()
    {
        $grid = new Grid(new Call());
        $grid->model()->orderBy('call_start_time','desc');
        $grid->filter(function($filter){
            $filter->equal('call_number','客户电话');
        });

        $grid->column('id', 'ID');
        $grid->column('call_number', '来电号码');
        $grid->column('circuit_types', '线路类型');
        $grid->column('call_type', '呼叫类型');
        $grid->column('province_id', '省')
            ->display(function($code) {
                $name = DB::table('admin_area')->where('id', $code)->value('area_name');
                if(!$name){
                    $name="NULL";
                }
                return $name;
            });
        $grid->column('city_id', '市')
            ->display(function($code) {
                $name = DB::table('admin_area')->where('id', $code)->value('area_name');
                if(!$name){
                    $name="NULL";
                }
                return $name;
            });
        $grid->column('answer_number', '接听号码');
        $grid->column('call_start_time', '开始通话时间');
        $grid->column('call_end_time', '结束通话时间');

        $grid->column('record_file', '录音')->display(function($code) {

            return '<audio src="/'.$code.'" controls="controls"></audio>';
        });
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableColumnSelector();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
        });
        return $grid;
    }
    public function form()
    {
        $form = new Form(new Call());
        $form->text('call_number', '来电号码');
        $form->select('circuit_types', '线路类型')->options(function (){
            return ['手机号'=>'手机号','座机'=>'座机'];
        });
        $form->select('call_type', '呼叫类型')->options(function (){
          return ['400呼入'=>'400呼入'];
        });
        $form->distpicker(['province_id', 'city_id', 'district_id']);
        $form->text('answer_number', '接听号码');
        $form->datetime("call_start_time","开始通话时间");
        $form->datetime("call_end_time","结束通话时间");
        $form->file("record_file","文件上传");
        $form->textarea('record_describe', '备注');
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        return $form;
    }
}