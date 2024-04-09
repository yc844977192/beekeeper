<?php

namespace App\Admin\Controllers;

use App\Models\Question;
use App\Models\Quote;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Form;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;


class QuoteController extends AdminController
{
    protected function title()
    {
        return trans('报价');
    }

    public function grid()
    {
        $grid = new Grid(new Quote());
        $grid->filter(function($filter) {
            $filter->equal('t_id', '分类')->select( function (){
                $list=DB::table('admin_type')->where("parent_id",0)->get()->toArray();
                $data=[];
                foreach ($list as $k=>$v){
                    $list[$k]=(array)$list[$k];
                    $data[$list[$k]['id']]= $list[$k]['type_name'];
                }
               return  $data;
            });
             //搜索多个字段
            $filter->where(function ($query) use ($filter) {
                $value = $this->input;
                if (isset($value)) {
                    $query->where('product_name', 'like', "%" . $value . "%")
                        ->orWhere('question', 'like', "%" . $value . "%")
                        ->orWhere('price', 'like', "%" . $value . "%")
                        ->orWhere('remark', 'like', "%" . $value . "%");
                }
            }, '搜索');
        });

        $grid->model()->orderBy('id','desc');
        $grid->column('id', 'ID');
        $grid->column('product_name', '产品名称');
        $grid->column('t_id', '分类')->display(function($code) {
            $list=(array)DB::table('admin_type')->where("id",$code)->first();
            return $list["type_name"];
        });
        $grid->column('question', '问题');
        $grid->column('price', '价格');
        $grid->column('remark', '备注');
        $grid->expandFilter(); // 默认打开筛选器
        $grid->tools(function (Grid\Tools $tools) {
            $js="<script>$(document).ready(function () {
                    $('.fields-group > div:first-child').remove();
                });</script>";
            $tools->append($js);
        });
        $grid->actions(function ($actions) {
            // 没有`delete-button`权限的角色不显示删除按钮
            if (!Admin::user()->can('delete-button')) {
                $actions->disableDelete();
            }
        });
        return $grid;
    }
    public function form()
    {
        $form = new Form(new Quote());
        $form->select('t_id', ('分类'))
            ->options(function (){
                $list=DB::table('admin_type')->where("parent_id",0)->get()->toArray();
                $data=[];
                foreach ($list as $k=>$v){
                    $list[$k]=(array)$list[$k];
                    $data[$list[$k]['id']]= $list[$k]['type_name'];
                }
                return $data;
            });
        $form->text('product_name', '产品名称');
        $form->textarea("question","问题");
        $form->textarea("price","价格");
        $form->textarea("remark","备注");
        return $form;
    }
    protected function detail($id)
    {
        $show = new Show(Quote::findOrFail($id));
        $show->field('id', 'ID');

        $show->field('t_id', '分类名称')->unescape()->as(function ($value) {
            // 根据 id 查询显示名称，并返回
            $name = DB::table('admin_type')->where('id', $value)->value('type_name');
            return $name;
        });
        $show->field('question', '问题')->unescape()->as(function ($value) {
        return "<pre>$value</pre>";
    });
        $show->field('price', '价格')->unescape()->as(function ($value) {
            return "<pre>$value</pre>";
        });
        $show->field('remark', '备注')->as(function ($value) {
            return "<pre>$value</pre>";
        });
        return $show;
    }
}

