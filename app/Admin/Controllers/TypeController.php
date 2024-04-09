<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;

use App\Models\Type;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Facades\Admin;

class TypeController extends AdminController {

    protected function title()
    {
        return trans('行业类别');
    }
    protected function grid()
    {
        $grid = new Grid(new Type());
        $grid->model()->orderBy('id','desc');
        $grid->filter(function (Grid\Filter $filter) {
            $filter->column(1/2 ,function ($filter){
                $filter->where(function ($query) {$query->where('type_name','like', "%".$this->input."%");}, '类别名称');
            });
            $filter->column(1/2, function ($filter) {
                $filter->between('created_at','提交时间')->date();
            });
        });
        $grid->column('id', 'ID')->sortable();
        $grid->column('type_name','类别名称');
        $grid->column('type_sort','分类等级');
        $grid->column('parent_id', '上级分类')
            ->display(function($code) {
                $name = DB::table('admin_type')->where('id', $code)->value('type_name');
                return $name;
            });
        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            // 没有`delete-button`权限的角色不显示删除按钮
            if (!Admin::user()->can('delete-button')) {
                $actions->disableDelete();
            }
            $actions->disableView();
        });
        $grid->disableExport();
        return $grid;
    }
    public function form()
    {
        $form = new Form(new Type());
        $form->text('type_name', '类别名称')->rules('required');
        $form->select('type_sort', '分类等级')
            ->options(function (){
                return ['1'=>'一级','2'=>'二级','3'=>'三级'];
            });

        $form->saving(function (Form $form) {
        });
        $form->select('parent_id', '所属分类')
            ->options(function (){
                $list=DB::table("admin_type")->where('type_sort',1)->orwhere('type_sort',2)->get()->toArray();
                $r_list=[];
                foreach ($list as $k=>$v){
                    $temp=[];
                    $list[$k]=(array)$list[$k];
                    $list[$k]['te']=$list[$k]['type_name'].'('.$list[$k]['type_sort'].'级'.')';

                }
                $plucked = collect($list)->pluck('te','id');
                $plucked->all();
                return $plucked;

            });
        //保存后回调
        $form->saved(function (Form $form) {
            $data = $form->model()->toArray();
            $id=$form->model()->id;
            if($data["type_sort"]==2){
                $count=DB::table("admin_commodity")->where("p_id",$data['parent_id'])->where("g_id",$id)->where("c_id",0)->count();
                if(!$count){
                    $into['commodity_name']=$data['type_name'];
                    $into['p_id']=$data['parent_id'];
                    $into['g_id']=$id;
                    $into['c_id']=0;
                    $into['commodity_shown']=1;
                    DB::table("admin_commodity")->insert($into);
                }

            }
        });
        return $form;
    }
    protected function detail($id)
    {
        $businessModel = 'Encore\Admin\Auth\Database\Type';
        $show = new Show($businessModel::findOrFail($id));
        $show->field('id', 'ID');
        $show->field('type_name', '类别名称');
        return $show;
    }
    //更新所属行业类别,手动更新用
    public function updateSort(){
        $list=DB::table("admin_type")->get()->toArray();
        $api=new ApiController();
        foreach ($list as $k=>$v){

            $list[$k]=(array)$list[$k];
            $data['type_sort']=$api->getSortByTypeId($list[$k]['id']);
            DB::table("admin_type")->where('id',$list[$k]['id'])->update($data);
        }
    }


}