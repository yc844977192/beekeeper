<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Messages;

class MessagesController extends AdminController {
    protected function title()
    {
        return trans('留言');
    }
    protected function grid()
    {
        $grid = new Grid(new Messages());
        $grid->model()->orderBy('id','desc');
        $grid->filter(function (Grid\Filter $filter) {
            $filter->column(1/2 ,function ($filter){
                $filter->where(function ($query) {$query->where('name', $this->input);}, '姓名');
            });
            $filter->column(1/2, function ($filter) {
                $filter->between('created_at','提交时间')->date();
            });
        });
        $grid->column('id', 'ID')->sortable();
        $grid->column('name','姓名');
        $grid->column('mobile','手机');
        $grid->column('ip','ip地址');
        $grid->column('source', '客户端')
            ->display(function($code) {

                switch ($code){
                    case "m";return"手机";
                    break;
                    case  "pc";return "电脑";
                    break;
                }

            });
        $grid->column('title', '留言页面')
            ->display(function($code) {
                if($code){
                    $str=explode("-",$code);
                   return $str[0];
                }

            });
        $grid->column('status', '状态')
            ->display(function($code) {
                switch ($code){
                    case 0;return "<span style='color: red'>未处理</span>";
                    break;
                    case 1;return "已处理";
                        break;
                }
            });
        $grid->column('addtime','提交时间');
        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
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
        $form = new Form(new Messages());
        $form->text('name', '姓名');
        $form->text('mobile', '电话');
//        $form->text('sex', '性别');
        $form->text('wechat', '微信');
        $form->text('consult_problem', '咨询问题');
        $form->text('choose_problem', '选择问题');
        $form->text('route_node', '请求节点');
        $form->text('addtime', '添加时间')->readonly();
        //$form->text('source', '数据来源');
        $form->select('source', '数据来源') ->options(function (){
            return ['m'=>'手机','pc'=>'电脑'];
        });
        $form->text('ip', 'ip地址');
        $form->text('title', 'title');
        $form->select('status', '状态') ->options(function (){
            return ['1'=>'已处理','0'=>'未处理'];
        });
        return $form;
    }
    protected function detail($id)
    {
        $show = new Show(Messages::findOrFail($id));
        $show->field('id', 'ID');
        $show->field('name', '姓名');
        $show->field('mobile', '电话');
        $show->field('sex', '性别');
        $show->field('wechat', '微信');
        $show->field('consult_problem', '咨询问题');
        $show->field('choose_problem', '选择问题');


        $show->field('route_node', '请求节点')->unescape()->as(function ($route_node) {
            return '<div style=" white-space: normal;overflow :auto">' . $route_node . '</div>';
        });

        $show->field('status', '预留状态');
        $show->field('addtime', '添加时间');
        $show->field('source', '数据来源');
        $show->field('ip', 'ip地址');
        return $show;
    }
}