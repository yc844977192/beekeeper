<?php

namespace App\Admin\Controllers;

use App\Models\BaiduKey;
use App\Models\IndustrType;
use App\Models\Roles;
use App\Models\ShowData;
use App\Models\TripartiteKeywords;
use App\Models\WebRoles;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Form;
use Illuminate\Support\Facades\DB;


class TripartiteKeywordsController extends AdminController
{
    protected function title()
    {
        return trans('搜索（关键词）');
    }

    public function grid()
    {
        $grid = new Grid(new TripartiteKeywords());
        $grid->model()->orderBy('id','desc');
        $grid->column('id', 'ID');
        $grid->column('business_key', '关键词');
        $grid->column('business_name', '业务名');
        $grid->column('status', '状态')->display(function ($code){
            switch ($code){
                case 0:
                    return "<div style='color: red'>暂停中</div>";
                    break;
                case 1:
                    return "启用中";
                    break;
            }
        });
        $grid->column('created_at', 'create_time');
        $grid->disableColumnSelector();
        $grid->actions(function ($actions) {
            if (!Admin::user()->can('delete-button')) {
                $actions->disableDelete();
            }
            $actions->disableView();
            $actions->disableEdit();
        });
        return $grid;
    }
    public function form()
    {
        $form = new Form(new TripartiteKeywords());
        $form->text('business_key', '关键词');
        $form->select('f_id', '行业类目')->options(function (){
            return IndustrType::where('type_sort', 0)->pluck('type_name', 'id');})
            ->load('p_id', '/admin/api/getIndustryType')
            ->rules('required', ['required' => '必填',]);
        $form->select('p_id', '一级类目')->options(function ($code){
            if($code){
                return IndustrType::where('type_sort', 1)->pluck('type_name', 'id');
                }
            })
            ->load('g_id', '/admin/api/getIndustryType')
            ->rules('required', ['required' => '必填',]);
        $form->select('g_id', '二级级类目')->options(function ($code){
            if($code){
                return IndustrType::where('type_sort', 2)->pluck('type_name', 'id');
            }
           })
            ->load('c_id', '/admin/api/getIndustryType')
            ->rules('required', ['required' => '必填',]);
        $form->select('c_id', '三级类目')->options(function ($code){
            if($code){
                return IndustrType::where('type_sort', 3)->pluck('type_name', 'id');
            }
        });
        return $form;
    }

    public function business_name_deal(){
        set_time_limit(0);
       $list=DB::table("baidu_key")->get()->toArray();
       foreach ($list as $k=>$v){
           $list[$k]=(array)$list[$k];

               $type_list=(array)DB::table("admin_industry_type")
                   ->where("id", $list[$k]["g_id"])
                   ->where("type_sort",2)
                   ->first();
               if($type_list){
                   DB::table("baidu_key")->where("id", $list[$k]["id"])->update(['p_id'=>$type_list['parent_id']]);
               }
       }
       var_dump("1");exit;
    }

}
