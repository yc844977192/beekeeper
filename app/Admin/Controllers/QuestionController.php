<?php

namespace App\Admin\Controllers;

use App\Models\Question;
use App\Models\Roles;
use App\Models\ShowData;
use App\Models\WebRoles;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Form;
use Encore\Admin\Show;


class QuestionController extends AdminController
{
    protected function title()
    {
        return trans('QA');
    }

    public function grid()
    {
        $grid = new Grid(new Question());
        $grid->filter(function($filter){
            // 搜索多个字段
            $filter->where(function ($query) use ($filter) {
                $value = $this->input;
                if (isset($value)) {
                    $query->where('answer', 'like', "%" . $value . "%")
                        ->orWhere('question', 'like', "%" . $value . "%");
                }
            }, '搜索');
        });
        $grid->model()->orderBy('id','desc');
        $grid->column('id', 'ID');
        $grid->column('question', '问题');
        $grid->column('answer', '答案');
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
        $form = new Form(new Question());
        $form->text('question', '问题');
        $form->textarea("answer","答案");
        return $form;
    }
    protected function detail($id)
    {
        $show = new Show(Question::findOrFail($id));
        $show->field('id', 'ID');
        $show->field('question', '问题')->unescape()->as(function ($value) {
            return "<pre>$value</pre>";
        });
        $show->field('answer', '答案')->unescape()->as(function ($value) {
            return "<pre>$value</pre>";
        });
        return $show;
    }

}
