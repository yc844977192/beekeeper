<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Models\United;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UnitedController extends AdminController {


    protected function title()
    {
        return trans('黄页:');
    }
    protected function grid()
    {
        $grid = new Grid(new United());
        $grid->filter(function($filter){
            $filter->equal('united_city_code', '市')->select( function (){
                $name = DB::table('data_district')->where("parent_code",13)->pluck('name', 'code');
                return $name;
            })->load('united_area_code', 'united/get_united_area_code');
            $filter->equal('united_area_code', '区')->select( function ($united_area_code){
                $name = DB::table('data_district')->where("code",$united_area_code)->pluck('name', 'code');
                return $name;
            });
            $filter->equal('united_type','机构类型')->select( function (){
                //1-政府机构 2-医院 3-学校 4-设计院 5-银行
                return ['1'=>'政府机构','2'=>'医院','3'=>'学校','4'=>'设计院','5'=>'银行'];
            });
            $filter->where(function ($query) use ($filter) {
                $value = $this->input;
                if (isset($value)) {
                    $query->where('united_name', 'like', "%" . $value . "%")
                        ->orWhere('united_nickname', 'like', "%" . $value . "%")
                        ->orWhere('united_site', 'like', "%" . $value ."%");
                }
            }, '全局搜索');

        });
        $grid->model()->orderBy('id','desc');
        $grid->column('id', 'ID');
        $grid->column('united_name', '机构单位名称');
        $grid->column('united_nickname', '机构单位简称');
        $grid->column('united_site', '机构网址');
        $grid->column('united_edittime', '修改时间');
        $grid->expandFilter(); // 默认打开筛选器
        $grid->tools(function (Grid\Tools $tools) {
            $js="<script>$(document).ready(function () {
                    $('.fields-group > div:first-child').remove();
                });</script>";
            $tools->append($js);
        });
        $grid->actions(function ($actions) {
            // 隐藏编辑按钮
            $actions->disableEdit();
            // 没有`delete-button`权限的角色不显示删除按钮
            if (!Admin::user()->can('delete-button')) {
                $actions->disableDelete();
            }
        });
        return $grid;
    }

    protected function detail($id)
    {
        $united = 'App\Models\United';
        $show = new Show($united::findOrFail($id));
        $show->field('united_name', '机构单位名称');
        $show->field('united_nickname', '机构单位简称');
        $show->field('united_info', '企业介绍');
        $show->field('united_address', '企业详细地址');
        $show->field('united_site', '机构网址');
        $show->field('united_phone', '机构联系电话');
        $show->field('united_phone_explain', '机构联系电话');
        $show->field('united_email', '邮箱');
        $show->field('united_email_explain', '邮箱说明');
        $show->field('united_fax', '传真号');
        $show->field('united_fax_explain', '传真号说明');
        $show->field('united_contact_phone', '联系人电话');
        $show->field('app_explain', 'app说明');
        $show->field('united_contact_position', '联系人职位');
        $show->field('united_contact_wechat', '联系人微信号');
        $show->field('united_contact_email', '联系人邮箱');
        $show->field('united_contact_address', '联系人地址');
        $show->field('united_edittime', '最后一次修改时间');
        return $show;
    }
    public function get_united_area_code(Request $request){
        $id = $request->get('q');
        //$name = DB::table('data_district')->where("parent_code",$id)->pluck('name', 'code');
        $list=DB::table('data_district')->where('parent_code',$id)->get()->toArray();
        $arr=[];
        foreach ($list as $k=>$v){
            $list[$k]=(array)$list[$k];
            $temp["id"]=$list[$k]['code'];
            $temp["text"]=$list[$k]['name'];
            array_push($arr,$temp);
        }
        return $arr;
    }

}