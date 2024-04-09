<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Models\Fm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FmDataController  extends AdminController {


    protected function title()
    {
        return trans('赋码:');
    }
    protected function grid()
    {
        $grid = new Grid(new fm());
        //$grid->column('id', 'ID');
        $grid->filter(function($filter){
            $filter->like('project_build_district_ids', '市')->select( function (){
                $name = DB::table('data_district')->where("parent_code",13)->pluck('name', 'code');
                return $name;
            })->load('project_build_district_id', 'fm_data/get_united_area_code');
            $filter->equal('project_build_district_id', '区')->select( function ($project_region_district_id){
                $name = DB::table('data_district')->where("code",$project_region_district_id)->pluck('name', 'code');
                return $name;
            });
            $filter->equal('project_industry_class_id', '投资行业')->select( function (){

                return  ["A0000501"=>"稀土、铁矿、有色矿山开发",
                    "A0000209"=>"煤制燃料",
                    "A0000601"=>"汽车",
                    "A0000307"=>"民航",
                    "A0000503"=>"化工",
                    "A0000305"=>"集装箱专用码头",
                    "A0000902"=>"城市道路桥梁、隧道",
                    "A0000205"=>"风电站",
                    "A0000203"=>"火电站",
                    "A0000101"=>"农业",
                    "A0000208"=>"煤矿",
                    "A0000207"=>"电网工程",
                    "A00099"=>"其他",
                    "A0000302"=>"公路",
                    "A0000505"=>"黄金",
                    "A0000303"=>"独立公(铁)路桥梁、隧道",
                    "A0000506"=>"煤化工",
                    "A0000213"=>"输气管网",
                    "A0001003"=>"其他社会事业项目",
                    "A0000304"=>"煤炭、矿石、油气专用泊位",
                    "A0000206"=>"核电站",
                    "A0001002"=>"旅游",
                    "A0000204"=>"热电站",
                    "A0000210"=>"液化石油气接收、存储设施(不含油气田、炼油厂的配套项目)",
                    "A0000701"=>"烟草",
                    "A0000104"=>"水利工程",
                    "A0000215"=>"变性燃料乙醇",
                    "A0000214"=>"炼油",
                    "A0000401"=>"电信",
                    "A0000106"=>"水库",
                    "A0000903"=>"其他城建项目",
                    "A0000502"=>"石化",
                    "A0001001"=>"主题公园",
                    "A0000801"=>"民用航空航天",
                    "A0000202"=>"抽水蓄能电站",
                    "A0000306"=>"内河航运",
                    "A0000103"=>"其他水事工程",
                    "A0000212"=>"输油管网",
                    "A0000504"=>"稀土",
                    "A0000301"=>"新建(含增建)铁路",
                    "A0000901"=>"城市快速轨道交通项目",
                    "A0000211"=>"进口液化天然气接收、储运设施",
                    "A00001"=>"农业水利",
                    "A00002"=>"能源",
                    "A00003"=>"交通运输",
                    "A00004"=>"信息产业",
                    "A00005"=>"原材料",
                    "A00006"=>"机械制造",
                    "A00007"=>"轻工",
                    "A00008"=>"高新技术",
                    "A00009"=>"城建",
                    "A00010"=>"社会事业",
                    "A00011"=>"外商投资",
                    "A00012"=>"境外投资",
                    ''=>''];
            });
            $filter->equal('project_nature_id', '建设性质')->select( function (){

                return [ '01'=>'基本建设项目',
                    '02'=>'技术改造项目',
                    '03'=>'单纯购置项目',
                    '04'=>'信息化项目',
                    '05'=>'其他项目',
                    ''=>''];
            });
            $filter->between('project_get_time','提交时间')->date();
            // 搜索多个字段
            $filter->where(function ($query) use ($filter) {
                $value = $this->input;
                if (isset($value)) {
                    $query->where('project_name', 'like', "%" . $value . "%")
                        ->orWhere('project_contractor', 'like', "%" . $value . "%")
                        ->orWhere('project_build_site_info', 'like', "%" . $value ."%");
                }
            }, '全局搜索');

        });
        //$grid->disableFilter();  // 禁用所有列的筛选器
        $grid->model()->orderBy('project_get_time','desc');
        $grid->column('project_name', '项目名称');
        $grid->column('project_contractor', '法人单位');
        $grid->column('project_build_site_info', '建设地点');
        $grid->column('project_get_time', '时间');
        $grid->column('project_industry_class_name', '投资行业');
        $grid->column('project_nature_name', '项目类型');
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
            if (!Admin::user()->can('delete-button')) {
                $actions->disableDelete();
            }
        });
        return $grid;
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
    protected function detail($id)
    {
        $fm = 'App\Models\Fm';
        $show = new Show($fm::findOrFail($id));
        $show->field('project_administrative_area', '项目所属行政区划');
        $show->field('project_industry_class_name', '投资项目行业分类名');
        $show->field('project_approval_name', '行业核准目录');
        $show->field('project_build_nature_name', '建设性质名');
        $show->field('project_name', '项目名称');
        $show->field('project_leader', '项目负责人');
        $show->field('project_leader_phone', '负责人联系电话');
        $show->field('project_start_time', '开工时间');
        $show->field('project_built_time', '拟建成时间');
        $show->field('project_investment', '总投资（万元）');
        $show->field('project_build_site', '建设地点');
        $show->field('project_build_site_info', '建设地点详情');
        $show->field('project_guide_name', '产业结构调整指导目录');
        $show->field('project_industry_name', '项目所属行业');
        $show->field('project_declare_time', '项目申报日期');
        $show->field('project_info', '建设规模及内容')->rows(8);
        $show->field('project_stage_name', '项目阶段');
        $show->field('project_property_name', '项目属性');
        $show->field('project_folk_hot', '拟向民间资本推介项目');
        $show->field('project_nature_name', '项目性质');
        $show->field('project_contractor', '项目法人单位');
        $show->field('project_enterprise_nature_name', '法人证照类型');
        $show->field('project_enterprise_id', '法人证照号码');
        $show->field('project_contract_name', '法定代表人');
        $show->field('project_contract_phone', '法人联系电话');
        $show->field('project_enterprise_type_id', '项目单位性质id');
        $show->field('project_enterprise_type_name', '项目单位性质');
        $show->field('project_declare_contractor', '项目申报单位（申报）');
        $show->field('project_declare_enterprise_nature_name', '法人证照类型（申报）');
        $show->field('project_declare_enterprise_id', '法人证照号码（申报）');
        $show->field('project_declare_contract_name', '法定代表人（申报）');
        $show->field('project_declare_contract_phone', '联系电话（申报）');
        $show->field('project_declare_enterprise_type_name', '项目单位性质（申报）');
        //$show->field('project_url', 'URL');
        $show->field('project_get_time', '信息获取时间');
        return $show;

    }


}