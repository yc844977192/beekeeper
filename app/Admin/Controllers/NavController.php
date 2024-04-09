<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Models\Nav;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class NavController extends AdminController {
    protected function title()
    {
        return trans('导航管理');
    }
    protected function grid()
    {
        $grid = new Grid(new Nav());
        $grid->model()->orderBy('id','desc');
        $grid->filter(function (Grid\Filter $filter) {
            $filter->column(1/2 ,function ($filter){
                $filter->where(function ($query) {$query->where('Nav_name', $this->input);}, '名称');
            });
            $filter->column(1/2, function ($filter) {
                $filter->between('created_at','提交时间')->date();
            });
        });
        $grid->tools(function ($tools) {
            $tools->append(view('admin/nav/MenuCache'));

        });
        $grid->column('id', 'ID')->sortable();
        $grid->column('nav_name','名称');
        $grid->column('nav_url','URL');
        $grid->column('nav_sort','排序');
        $grid->column('nav_shown','是否显示')  ->display(function($code) {
            if($code==0){
                return "否";
            }
            if($code==1){
                return "是";
            }
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
        $form = new Form(new Nav());
        $form->display('id', 'ID');
        $form->text('nav_name', '名称')->rules('required');
        $form->image('nav_ico')->move('/uploads/image/'.date("Y").'/'.date("m").'/'.date("d"));
        $form->text('nav_url', 'URL')->rules('required');
        $form->radio("nav_sort",'排序') ->options(["1"=>1,"2"=>2,"3"=>3,"4"=>4,"5"=>5]);
        $form->select('nav_shown', "是否显示")
            ->options(function (){
                return ['0'=>'否','1'=>'是'];
            });
        $form->saving(function (Form $form) {
        });
        return $form;
    }
    protected function detail($id)
    {
        $show = new Show(Nav::findOrFail($id));
        $show->field('id', 'ID');
        $show->field('type_name', '类别名称');
        return $show;
    }
    public function putMenuCache(){
        $url=config("system.PUT_CACHE")."home/getTypeMenu";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $list=json_decode($output,true);
        return $list;
    }
}