<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Models\Webchat;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class WebchatController extends AdminController {
    protected function title()
    {
        return trans('聊天记录');
    }
    public function grid(){
        $grid = new Grid(new Webchat());
        $grid->model()->groupBy('session_id');
        $grid->model()->orderBy('id','desc');
        $grid->column('id', 'ID');
//        $grid->column('', '聊天记录')->modal('聊天记录', function ($model) {
//            $id = $this->id;
//            $list=(array)DB::connection('mysql_aliyun')->table('chat_record')->where("id",$id)->first();
//            $list2=DB::connection('mysql_aliyun')->table('chat_record')->where("session_id",$list['session_id'])->get()->toArray();
//            $str='';
//            if(sizeof($list2)>0){
//                foreach ($list2 as $k=>$v){
//                    $list2[$k]=(array)$list2[$k];
//                    $arr=json_decode($list2[$k]['chat_record'],true);
//                    if(!array_key_exists("AI.Chat",$arr)){
//                        $arr['AI.Chat']="";
//                    }
//                    $str=$str.'user:'.$arr['user'].'<br /> AI.Chat:'.$arr['AI.Chat'].'<br />';
//                }
//            }
//            return $str;
//        });
        $grid->column('created_at', '提交时间');
        $grid->column('session_id', 'session_id');
//        $grid->column('s', '聊天数量')->display(function($code) {
//            $id = $this->id;
//            $list=(array)DB::connection('mysql_aliyun')->table('chat_record')->where("id",$id)->first();
//            $count=DB::connection('mysql_aliyun')->table('chat_record')->where("session_id",$list['session_id'])->count();
//            return $count;
//        });

        $grid->actions(function ($actions) {
            // 隐藏编辑按钮
            $actions->disableEdit();
            if (!Admin::user()->can('delete-button')) {
                $actions->disableDelete();
            }
        });
        $grid->disableExport();
        $grid->disableCreateButton();
        $grid->disableFilter();
        //$grid->disableRowSelector();
        //$grid->disableActions();
        $grid->disableColumnSelector();
        return $grid;

    }

    protected function detail($id)
    {
        $Webchat = 'App\Models\Webchat';
        $show = new Show($Webchat::findOrFail($id));
        $show->field('session_id', '聊天记录')->unescape()->as(function ($value) {
            // 根据 id 查询显示名称，并返回
            $list2=DB::connection('mysql_aliyun')->table('chat_record')->where("session_id",$value)->get()->toArray();
            $str='';
            if(sizeof($list2)>0){
                foreach ($list2 as $k=>$v){
                    $list2[$k]=(array)$list2[$k];
                    $arr=json_decode($list2[$k]['chat_record'],true);
                    if(!array_key_exists("AI.Chat",$arr)){
                        $arr['AI.Chat']="";
                    }
                    $str=$str.'user:'.$arr['user'].'<br /> AI.Chat:'.$arr['AI.Chat'].'<br />';
                }
            }
            return $str;

        });
        return $show;

    }


}