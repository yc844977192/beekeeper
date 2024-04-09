<?php

namespace App\Admin\Controllers;


use App\Models\Business;
use App\Models\Construction;
use App\Models\Source;
use App\Models\Type;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class BusinessController extends AdminController
{
    protected function title()
    {
        return trans('商机');
    }
    public function grid()
    {
        $grid = new Grid(new Business());
        $grid->filter(function($filter){
            $filter->disableIdFilter();

            $filter->column(1/2 ,function ($filter){
                $filter->equal('c_id', '三级分类')->select( function (){
                    return  Type::where('type_sort', 3)->pluck('type_name', 'id');
                });
                $filter->like('getCustomer.customer_name', '客户姓名');
                $filter->equal('province_id', '省')->select( function (){
                    $name = DB::table('admin_area')->where("area_parent_id",0)->pluck('area_name', 'id');
                    return $name;
                })->load('city_id', 'api/getAreaNameByParentId');
                $filter->equal('district_id', '区')->select( function (){
                    if(array_key_exists('district_id',$_GET)&&$_GET['district_id']){
                        $city_id=$_GET['city_id'];
                        $name = DB::table('admin_area')->where("area_parent_id",$city_id)->pluck('area_name', 'id');
                        return $name;
                    }
                });
                $filter->group('business_today_number',"今日跟踪次数", function ($group) {
                    $group->gt('大于');
                    $group->equal('等于');
                });
                $filter->like('business_tracker', '跟踪人');
            });
            $filter->column(1/2, function ($filter) {
                $filter->in('business_states','商机状态')
                    ->multipleSelect(['0'=>'待分配','1'=>'已分配','3'=>'跟进中','4'=>'失败','5'=>'暂缓','6'=>'成交','7'=>"放弃"]);
                $filter->equal('getCustomer.customer_mobile','客户电话');
                $filter->equal('city_id', '市')->select( function (){
                    if(array_key_exists('city_id',$_GET)&&$_GET['city_id']){
                        $province_id=$_GET['province_id'];
                        $name = DB::table('admin_area')->where("area_parent_id",$province_id)->pluck('area_name', 'id');
                        return $name;
                    }

                })->load('district_id', 'api/getAreaNameByParentId');
                $filter->equal('business_level','商机等级')->select( function (){
                    return ['1'=>'1级','2'=>'2级','3'=>'3级'];
                });
                $filter->between('created_at','提交时间')->date();
            });
        });
        $grid->model()->orderBy('id','desc');
        $grid->column('id', 'ID')
            ->expand(function () {
                return "<div style='margin-bottom: 10px;padding-left: 10px;'>". $this->business_requirement_description."</div>";
            })->sortable();
        $grid->column('getCustomer.customer_name','客户姓名');
        $grid->column('getCustomer.customer_mobile', '客户电话');
        $grid->column('typeC_name.type_name', '三级分类');
        $grid->column('business_states', '商机状态')->display(function ($code){
            switch ($code){
                case 0:
                    return "待分配";
                    break;
                case 1:
                    return "已分配";
                    break;
                case 3:
                    return "跟进中";
                    break;
                case 4:
                    return "失败";
                    break;
                case 5:
                    return "暂缓";
                    break;
                case 6:
                    return "成交";
                    break;
                case 7:
                    return "放弃";
                    break;
            }
        });
        $grid->column('business_level','商机等级')
            ->display(function($code) {
                if($code){
                    switch($code){
                        case 1;return'一级';
                            break;
                        case 2;return'二级';
                            break;
                        case 3;return'三级';
                            break;
                    }
                }
                else{
                    return '未设置';
                }

            });
        $grid->column('created_at', '提交时间');
        $grid->column('province_name.area_name', '省')->display(function ($code){
            if($code){
                return $code;
            }
            else{
                return "NULL";
            }
        });
        $grid->column('city_name.area_name', '市')->display(function ($code){
            if($code){
                return $code;
            }
            else{
                return "NULL";
            }
        });
        $grid->column('district_name.area_name', '区')->display(function ($code){
            if($code){
                return $code;
            }
            else{
                return "NULL";
            }
        });
        $grid->actions(function ($actions) {
            // 没有`delete-button`权限的角色不显示删除按钮
            if (!Admin::user()->can('delete-button')) {
                $actions->disableDelete();
            }
        });
        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });
        $grid->disableExport();
        return $grid;
    }
    public function form()
    {
        $form = new Form(new Business());
        if($form->isCreating()){
            $form->select('customer_id', ('客户电话'))
                ->options(function (){
                    return DB::table("admin_customer")->pluck('customer_mobile', 'id');
                })->load('business_customer_name', '/admin/api/getCustomerName')
                ->rules('required', ['required' => '必填',]);
            $form->select('business_customer_name', ('客户姓名')) ->options(function (){})->readOnly();
            $form->ignore("business_customer_name");
        }
        else{
            $form->select('customer_id', ('客户电话'))
                ->options(function (){
                    return DB::table("admin_customer")->pluck('customer_mobile', 'id');
                })->load('business_customer_name', '/admin/api/getCustomerName')
                ->rules('required', ['required' => '必填',]);
            $form->select('business_customer_name', '客户姓名') ->options(function (){
                return DB::table("admin_customer")->where('id',$this->customer_id)->pluck(DB::raw('customer_name as text'));
            });
            $form->ignore("business_customer_name");
        }

        $form->select('business_private_sphere_id', ('私域'))
            ->options(function (){
                return DB::table("admin_private_sphere")->pluck('private_sphere_name', 'id');
            });
        $form->select('source_id', ('商机来源'))
            ->options(function (){
                return Source::pluck('source_name', 'id');
            })
            ->rules('required', ['required' => '必填',]);
        $form->select('business_states', ('商机状态'))
            ->options(function ($code){
                if($code!=0){
                    return ['1'=>'已分配','3'=>'跟进中','4'=>'失败','5'=>'暂缓','6'=>'成交','7'=>"放弃"];
                }
                else{
                    return ['0'=>'待分配','1'=>'已分配','3'=>'跟进中','4'=>'失败','5'=>'暂缓','6'=>'成交','7'=>"放弃"];
                }

            })
            ->rules('required', ['required' => '必填',]);
        $form->select('business_tracker', ('跟踪人'))
            ->options(function (){
                return ['李广振'=>'李广振','唐修权'=>'唐修权','陈凯明'=>'陈凯明','华安检测'=>'华安检测','三方服务商'=>'三方服务商'];
            });
        $form->text('recipient', '接收人');
        $form->select('business_construction_id', ('承接方'))
            ->options(function (){
                return Construction::pluck('construction_name', 'id');
            });
        $form->text('business_states_log_into', '跟踪记录');
        //$form->radio('business_not_follow', '是否刷新规则时间')->options(["0"=>"否","1"=>"是"])->default(1);
        $form->textarea('business_states_log', '')->readonly();
        $form->textarea('business_requirement_description', '需求描述');
        $form->select('business_level', "商机等级")
            ->options(function (){
                return ['1'=>'一级','2'=>'二级','3'=>'三级'];
            });
        $form->distpicker(['province_id', 'city_id', 'district_id']);
        $form->select('c_id', '三级类目')->options(function (){
            return Type::where('type_sort', 3)->pluck('type_name', 'id');})
            ->load('g_id', '/admin/api/getType')
            ->rules('required', ['required' => '必填',]);

        $form->select('g_id', '二级类目')->options(function ($g_id){
            return Type::where('id',$g_id)->pluck('type_name', 'id');})
            ->load('p_id', '/admin/api/getType')
            ->rules('required', ['required' => '必填',]);

        $form->select('p_id', '一级类目')
            ->options(function ($code){
                return Type::where('id',$code)->pluck('type_name', 'id');
            })
            ->rules('required', ['required' => '必填',]);
        //保存前回调
//        $form->saving(function (Form $form) {
//            $data["customer_id"] = $form->input("customer_id");
//            $data["business_private_sphere_id"] = $form->input("business_private_sphere_id");
//            $data["source_id"] = $form->input("source_id");
//            $data["business_states"] = $form->input("business_states");
//            $data["business_tracker"] = $form->input("business_tracker");
//            $data["recipient"] = $form->input("recipient");
//            $data["business_construction_id"] = $form->input("business_construction_id");
//            $data["business_states_log"] = $form->input("business_states_log");
//            $data["business_not_follow"] = $form->input("business_not_follow");
//            $data["business_requirement_description"] = $form->input("business_requirement_description");
//            $data["business_level"] = $form->input("business_level");
//            $data["province_id"] = $form->input("province_id");
//            $data["city_id"] = $form->input("city_id");
//            $data["district_id"] = $form->input("district_id");
//            $data["c_id"] = $form->input("c_id");
//            $data["g_id"] = $form->input("g_id");
//            $data["p_id"] = $form->input("p_id");
//            //比对是否修改
//            $data_older["customer_id"] = $form->model()->customer_id;
//            $data_older["business_private_sphere_id"] = $form->model()->business_private_sphere_id;
//            $data_older["source_id"] = $form->model()->source_id;
//            $data_older["business_states"] = $form->model()->business_states;
//            $data_older["business_tracker"] = $form->model()->business_tracker;
//            $data_older["recipient"] = $form->model()->recipient;
//            $data_older["business_construction_id"] = $form->model()->business_construction_id;
//            $data_older["business_states_log"] = $form->model()->business_states_log;
//            $data_older["business_not_follow"] = $form->model()->business_not_follow;
//            $data_older["business_requirement_description"] = $form->model()->business_requirement_description;
//            $data_older["business_level"] = $form->model()->business_level;
//            $data_older["province_id"] = $form->model()->province_id;
//            $data_older["city_id"] = $form->model()->city_id;
//            $data_older["district_id"] = $form->model()->district_id;
//            $data_older["c_id"] = $form->model()->c_id;
//            $data_older["g_id"] = $form->model()->g_id;
//            $data_older["p_id"] = $form->model()->p_id;
//            //将2个数组的字段，逐个对比，并组合字符串详细记录修改记录
//            $into["change"]="";
//            if($data["customer_id"]!=$data_older["customer_id"]){
//                $into["change"]=$into["change"]."客户：".$data_older["customer_id"]."->".$data["customer_id"];
//            }
//
//        });
        return $form;
    }
    protected function detail($id)
    {
        $report =new Business();
        $show = new Show($report::findOrFail($id));
        $show->field('getCustomer.customer_mobile', '客户电话');
        $show->field('getCustomer.customer_name','客户姓名');
        $show->field('get_private_sphere.private_sphere_name', '私域');
        $show->field('get_business_source.source_name', '商机来源');
        $show->field('business_states', '商机状态')->as(function ($code) {
            switch($code){
                case 0;return'待分配';
                break;
                case 1;return'已分配';
                    break;
                case 3;return'跟进中';
                    break;
                case 4;return'失败';
                    break;
                case 5;return'暂缓';
                    break;
                case 6;return'成交';
                    break;
                case 7;return'成交';
                    break;
            }
        });
        $show->field('business_tracker', '跟踪人');
        $show->field('recipient', '接收人');
        $show->field('get_business_construction.construction_name', '承接方');
        //$show->field('business_states_log', '跟踪记录');
        $show->field('business_states_log', '跟踪记录')->unescape()->as(function ($value) {
            return '<div style="white-space: pre-line;">' . $value . '</div>';
        });
        $show->field('business_requirement_description', '需求描述');
        $show->field('business_level', "商机等级")->as(function ($code) {
            switch($code){
                case 1;return'一级';
                break;
                case 2;return'二级';
                break;
                case 3;return'三级';
                break;
            }
        });
        $show->field('province_name.area_name', '省');
        $show->field('city_name.area_name', '市');
        $show->field('district_name.area_name', '区');
        $show->field('typeC_name.type_name', '三级分类');
        $show->field('typeG_name.type_name', '二级分类');
        $show->field('typeP_name.type_name', '一级分类');
//        $show->field('district_name.area_name', '区');
        return $show;
    }
    public function store()
    {
        $data = request()->all();
        if($data["business_states_log_into"]){
            $data['business_states_log'] .=  date("Y-m-d H:i:s").'  '. $data["business_states_log_into"];
        }
        $data["created_at"] = date("Y-m-d H:i:s");
        $data["updated_at"] = date("Y-m-d H:i:s");
        $data["business_states_log_into"] = "";
        unset($data["_token"]);
        unset($data["_method"]);
        unset($data["_previous_"]);
        unset($data["s"]);
        $id=DB::table("admin_business")->insertGetId($data);
        admin_toastr(trans('admin.save_succeeded'));
        $url='admin/business/'.$id.'/edit';
        return redirect($url);
    }

    public function update($id, $data = null)
    {
        $data = ($data) ?: request()->all();
        if($data["business_states_log_into"]){
            $data['business_states_log'] .= "&#10;" .date("Y-m-d H:i:s").'  '. $data["business_states_log_into"];
        }
        $data["business_states_log_into"] = "";
        $data["updated_at"] = date("Y-m-d H:i:s");
        unset($data["_token"]);
        unset($data["_method"]);
        unset($data["_previous_"]);
        unset($data["s"]);
        $older_list=(array)DB::table("admin_business")->where("id",$id)->first();
        Business::where("id",$id)->update($data);


        admin_toastr(trans('admin.save_succeeded'));
        $url='admin/business/'.$id.'/edit';
        return redirect($url);
    }
}
