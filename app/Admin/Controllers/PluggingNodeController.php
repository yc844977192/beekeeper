<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;

use App\Models\BaiduWordData;
use App\Models\PluggingNode;
use App\Models\TripartiteNode;
use App\Models\Type;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Facades\Admin;

class PluggingNodeController extends AdminController {

    protected function title()
    {
        return trans('搜索（数据）');
    }
    protected function grid()
    {
        $grid = new Grid(new PluggingNode());
        $grid->model()->orderBy('id','desc');
        $grid->column('id', 'ID')->sortable();
        $grid->column('node_name', '节点名称');
        $grid->column('ip_address', 'ip');
        $grid->column('del_url', '处理地址');
        $grid->column('123', '今日数量')
            ->display(function() {
                $ip_address = $this->ip_address;
                $count=DB::table("baidu_words_data_plugging")
                    ->where("business_region",$ip_address)
                    ->where("company_get_date",date("Y-m-d"))
                    ->count();
                return $count;
            });
        $grid->column('456', '总计')
            ->display(function() {
                $ip_address = $this->ip_address;
                $count=DB::table("baidu_words_data_plugging")
                    ->where("business_region",$ip_address)
                    ->count();
                return $count;
            });
        $grid->column('status', 'status')->display(function($code) {
            if($code==0){
                return '<span style="color: red">Off</span>';
            }
            if($code==1){
                return 'On';
            }
        });

        $grid->column('frequency', '频率')
            ->display(function($code) {
                return $code.'分钟/次';
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
        $form = new Form(new PluggingNode());
        $form->text('node_name', '类别名称');
        $form->text('ip_address', 'IP');
        $form->text('frequency', '频率');
        $form->select('status', ('状态'))
            ->options(function (){
                return ['0'=>'Off','1'=>'On'];
            });
        $form->text('del_url', "处理地址");
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