<?php

namespace App\Admin\Controllers;


use App\Models\StaticWeb;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\DB;

class StaticWebController extends AdminController
{
    protected function title()
    {
        return trans('缓存设置');
    }

    public function grid()
    {
        $grid = new Grid(new StaticWeb());
        $grid->column('id', 'ID');
        $grid->column('title', 'title');
        $grid->column('key', 'key');
        $grid->column('created_at', 'create_time');
        $grid->column('c', '生成静态')->display(function (){
            $list=(array)DB::table("static_web")->where("id",$this->id)->first();
            $is_wap=$list["is_wap"];
            return view("Admin/static-web/index")->with("id",$this->id)->with("is_wap",$is_wap);
        });
//        $grid->disableExport();
//        $grid->disableCreateButton();
//        $grid->disableFilter();
        //$grid->disableRowSelector();
        //$grid->disableActions();
        $grid->disableColumnSelector();
        $grid->actions(function ($actions) {
            // 隐藏编辑按钮
            $actions->disableView();
            if (!Admin::user()->can('delete-button')) {
                $actions->disableDelete();
            }
        });
        return $grid;
    }
    public function form()
    {
        $form = new Form(new StaticWeb());
        $form->text('title', 'title');
        $form->text("url","url");
        $form->text("key","key");
//        $form->textarea("role_description","role_description");
        $form->select('is_wap', "是否有手机静态")
            ->options(function (){
                return ['0'=>'否','1'=>'有'];
            });
//        $form->text('sort', '排序');
        return $form;
    }
    public function detail(){
        echo 1;exit;
    }
    public function setStatic(){

        $id= $_POST["id"];
        $list=(array)DB::table("static_web")->where("id",$id)->first();
        $url = 'http://static.jiancefeng.com/api/'.$list["key"];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        //curl_setopt ($curl, CURLOPT_HTTPHEADER, $header );
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        $data = curl_exec($curl);
        return json_encode($data);
    }

    public function setWapStatic(){
        $id= $_POST["id"];
        $list=(array)DB::table("static_web")->where("id",$id)->first();
        $url = 'http://wapstatic.jiancefeng.com/api/'.$list["key"];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        //curl_setopt ($curl, CURLOPT_HTTPHEADER, $header );
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        $data = curl_exec($curl);
        return json_encode($data);
    }



}
