<?php

namespace App\Admin\Controllers;

use App\Models\BaiduKey;
use App\Models\DetectionTripartiteKeywords;
use App\Models\IndustrType;
use App\Models\PluggingKeywords;
use App\Models\Roles;
use App\Models\ShowData;
use App\Models\TripartiteKeywords;
use App\Models\WebRoles;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Form;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;


class PluggingKeywordsController extends AdminController
{
    protected function title()
    {
        return trans('搜索（关键词）');
    }

    public function grid()
    {
        $grid = new Grid(new PluggingKeywords());
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
        });
        return $grid;
    }
    public function form()
    {
        $form = new Form(new PluggingKeywords());
        $form->text('business_key', '关键词');
        return $form;
    }
    protected function detail($id)
    {
        $pluggingKeywords =new PluggingKeywords();
        $show = new Show($pluggingKeywords::findOrFail($id));
        $show->field('id', 'ID');
        $show->field('business_key', '关键词');
//        $show->field('customer_type', '性质');
//        $show->field('customer_category', '所属行业');
//        $show->field('customer_website', '网址');
//        $show->field('customer_email', '邮箱');
//        $show->field('customer_wechat', '微信');
//        $show->field('customer_applet', '小程序');
//        $show->field('customer_accounts', '公众号');
//        $show->field('province_name.area_name', '省');
//        $show->field('city_name.area_name', '市');
//        $show->field('district_name.area_name', '区');
        return $show;
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
