<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;

use App\Models\Article;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;

class ArticleController extends AdminController {
    protected function title()
    {
        return trans('文章管理');
    }
    protected function grid()
    {
        $grid = new Grid(new Article());
        $grid->model()->orderBy('id','desc');
        $grid->filter(function (Grid\Filter $filter) {
            $filter->column(1/2 ,function ($filter){
                $filter->where(function ($query) {$query->where('title', $this->input);}, '文章名称');
            });
            $filter->column(1/2, function ($filter) {
                $filter->between('created_at','提交时间')->date();
            });
        });
        $grid->column('id', 'ID')->sortable();
        $grid->column('title','文章名称');
        $grid->column('cate_id','所属类别')
            ->display(function($code) {
                if($code==10){
                    return "资质/荣誉";
                }
                if($code==11){
                    return "媒体报道";
                }

            });
        $grid->column('created_at','提交时间');
        $grid->actions(function ($actions) {
            $actions->disableView();
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
        $form = new Form(new Article());
        $form->text('title', '文章名称')->rules('required');
        $form->text('keywords', '关键字');
        $form->textarea('description', '文章描述');
        $form->select('cate_id', "所属类别")
            ->options(function (){
                return ['11'=>'媒体报道','10'=>'资质/荣誉'];
            });
        $form->text('author', '发布人');
        $form->select('is_hidden', "是否隐藏")
            ->options(function (){
                return ['0'=>'是','1'=>'否'];
            });
        $form->ueditor('body',__('内容'));
        $form->text('created_at', '提交时间')->readonly();
        $form->saving(function (Form $form) {
        });
        return $form;
    }
    protected function detail($id)
    {
        $show = new Show(Article::findOrFail($id));
        $show->field('id', 'ID');
        $show->field('title', '文章名称');
        return $show;
    }

}