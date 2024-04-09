<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Models\Offerer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\DB;

class OffererController extends AdminController {
    protected function title()
    {
        return trans('报价人');
    }
    protected function grid()
    {
        $grid = new Grid(new Offerer());
        $grid->model()->orderBy('id','desc');
        $grid->filter(function (Grid\Filter $filter) {
            $filter->column(1/2 ,function ($filter){
                $filter->where(function ($query) {$query->where('offerer_name', $this->input);}, '姓名');
                $filter->where(function ($query) {$query->where('offerer_mobile', $this->input);}, '电话');
            });
            $filter->column(1/2, function ($filter) {
                $filter->equal('get_t_id.commodity_id', '一级分类')->select( function (){
                    $name = DB::table('admin_type')->where("type_sort",1)->pluck('type_name', 'id');
                    return $name;
                }) ->load('get_g_id.commodity_id', '/admin/api/group');
                $filter->equal('get_g_id.commodity_id', '二级分类')->select( function (){
                    if(array_key_exists('get_g_id',$_GET)){
                        $name = DB::table('admin_type')->where("parent_id",$_GET['get_t_id']['commodity_id'])->pluck('type_name', 'id');
                        return $name;
                    }
                }) ->load('get_c_id.commodity_id', '/admin/api/group');
                $filter->equal('get_c_id.commodity_id', '二级分类')->select( function (){
                    if(array_key_exists('get_c_id',$_GET)){
                        $name = DB::table('admin_type')->where("parent_id",$_GET['get_g_id']['commodity_id'])->pluck('type_name', 'id');
                        return $name;
                    };
                });
            });
        });
        $grid->column('id', 'ID')->sortable();
        $grid->column('offerer_name', '姓名')->display(function($code) {
            $id = $this->id;
            return '<a href="/admin/offerer/'.$id.'/edit" >'.$code.'</a>';
        });
        $grid->column('offerer_mobile','电话');
        $grid->column('created_at','提交时间');
        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });
        $grid->disableExport();
//        $grid->tools(function ($tools) {
//            $tools->append(view('offerer.setCommodity'));
//
//        });
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

        $form = new Form(new Offerer());
        $form->text('offerer_name', '姓名')->rules('required');
        $form->mobile('offerer_mobile', '电话')
            ->creationRules(['required','min:11',"unique:admin_offerer",'numeric'],['unique' => '手机号码必须位为数字，11位，不能重复'])
            ->updateRules(['required','min:11','min:11','numeric',"unique:admin_offerer,offerer_mobile,{{id}}"], ['unique' => '手机号码必须位为数字，11位,不能重复']);
        $supp='';
        if($form->isEditing()){
            $id=request()->route()->parameters()['offerer'];
            $relex=DB::table("admin_supplier_offerer")->where("offerer_id",$id)->get()->toArray();

            foreach ($relex as $k=>$v){
                $v=(array)$v;
                //循环查出
                $s_list[$k]=(array)DB::table("admin_supplier")->where('id',$v['supplier_id'])->first();
                if($s_list[$k]){
                    $supp=$supp.''.$s_list[$k]['supplier_name'].'--';
                }

            }
            $supp = rtrim($supp, "--");

        }
        $form->html(view("Admin/offerer/get_supplier")->with('supp',$supp),"供应商");
        $type_list=DB::table('admin_type')->get()->toArray();
        $id=0;
        $temp_count=0;
        if($form->isEditing()){
            $id=request()->route()->parameters()['offerer'];
            $temp_count=DB::table('admin_supplier_offerer')->where('offerer_id',$id)->count();
            $relextion_list=DB::table('admin_commodity_offerer')->where('offerer_id',$id)->get()->toArray();
            foreach ($relextion_list as $k=>$v){
                $relextion_list[$k]=(Array)$relextion_list[$k];
            }
            foreach ($type_list as $k=>$v){
                $type_list[$k]=(Array)$type_list[$k];
                $tlist[$k]['id']=$type_list[$k]['id'];
                $tlist[$k]['pId']=$type_list[$k]['parent_id'];
                $tlist[$k]['name']=$type_list[$k]['type_name'];
                if($relextion_list){
                    foreach ($relextion_list as $key=>$value){
                        if($relextion_list[$key]['commodity_id']==$tlist[$k]['id']){
                            $tlist[$k]['checked']='true';
                        }
                    }
                }
            }
        }
        if($form->isCreating()){
            foreach ($type_list as $k=>$v){
                $type_list[$k]=(Array)$type_list[$k];
                $tlist[$k]['id']=$type_list[$k]['id'];
                $tlist[$k]['pId']=$type_list[$k]['parent_id'];
                $tlist[$k]['name']=$type_list[$k]['type_name'];
            }
        }
        $form->html(view("Admin/offerer/get_commodity")->with('temp_count',$temp_count)->with("tlist",json_encode($tlist,true))->with("id",$id),"关联商品");
        $form->text('offerer_position', '职位');
        $form->email('offerer_email', '邮箱');
        $form->textarea('offerer_address', '地址');
        $form->text('offerer_wechat', '微信');
        $form->textarea('offerer_remark', '备注');
        $form->saving(function (Form $form) {
        });
        $form->text('offerer_states_log', '跟踪记录');
        $form->textarea('temp', '')->readonly();
        return $form;
    }
    protected function detail($id)
    {
        $offererList=DB::table('admin_offerer')
            ->where('id',$id)
            ->first();
        $offererList=(Array)$offererList;

        //供应商列表
        $supplierLists=[];
        $temp=[];
        $relationList=DB::table('admin_supplier_offerer')
            ->where('offerer_id',$id)
            ->get()->toArray();
        foreach ($relationList as $k=>$v){
            $relationList[$k]=(Array)$relationList[$k];
            array_push($temp,$relationList[$k]['supplier_id']);
        }
        foreach ($temp as $k=>$v){
            array_push($supplierLists,$this->getSupplier($v));
        }
        $api=new ApiController();
        foreach ($supplierLists as $k=>$v){
            $supplierLists[$k]['province_id']=$api->getArea($v['province_id']);
            $supplierLists[$k]['city_id']=$api->getArea($v['city_id']);
            $supplierLists[$k]['district_id']=$api->getArea($v['district_id']);
        }
        return view('Admin/offerer/offerer_show')->with('offererList',$offererList)->with('supplierLists',$supplierLists);
    }
    public function getSupplier($supplierId){
        $supplierList= (Array)DB::table('admin_supplier')
            ->where('id',$supplierId)
            ->first();
        return $supplierList;
    }
    public function commodityAjax(){
        $pageSize=$_POST['pageSize'];
        $pageNum=$_POST['pageNum'];
        $id=$_POST['id'];
        $offererList=(array)DB::table('admin_offerer')
            ->where('id',$id)
            ->first();
        $getApi=new ApiController();
        //获取商品
        $commodityLists=[];
        $temp=[];
        $relationList=DB::table("admin_commodity_offerer")->where("offerer_id",$id)->get()->toArray();
        foreach ($relationList as $k=>$v){
            $relationList[$k]=(Array)$relationList[$k];
            //根据typeid获取商品列表

            $sort=$getApi->getSortByTypeId($relationList[$k]['commodity_id']);
            if($sort==1){
                unset( $relationList[$k]);
            }
            if($sort==2){
                unset( $relationList[$k]);
            }
            if($sort==3){
                //查出与三级分类一一对应的商品（四级）
                $tempList=$getApi->getCommodityListByTypeId($relationList[$k]['commodity_id']);
                if($tempList){
                    array_push($commodityLists,$tempList);
                }
            }
        }
        $resProduct['total']=count($commodityLists);
        $resProduct['totalPage'] = ceil($resProduct['total']/$pageSize);
        //分页显示
        $page = $pageNum;
        //每页的条数
        $perPage = $pageSize;
        //计算每页分页的初始位置
        $offset = ($page * $perPage) - $perPage;

        //实例化LengthAwarePaginator类，并传入对应的参数

        $commodityLists = new LengthAwarePaginator(array_slice($commodityLists, $offset, $perPage, true), count($commodityLists), $perPage,

            $page);
        return view('Admin/offerer/commodityAjax')
            ->with("commodityLists",$commodityLists)
            ->with("resProduct",$resProduct)
            ->with("id",$id)->with("cpage",$pageNum);
    }
    public function supplierAjax(){
        $pageSize=$_POST['pageSize'];
        $pageNum=$_POST['pageNum'];
        $id=$_POST['id'];
        $relationList=DB::table('admin_supplier_offerer')
            ->where('offerer_id',$id)
            ->get()->toArray();
        $api=new ApiController();
        foreach ($relationList as $k=>$v){
            $relationList[$k]=(Array)$relationList[$k];
            $supplierLists[$k]=$this->getSupplier($relationList[$k]['supplier_id']);
            //array_push($supplierLists,$this->getSupplier($relationList[$k]['supplier_id']));
        }
        foreach ($supplierLists as $k=>$v){
            $supplierLists[$k]['province_id']=$api->getArea($v['province_id']);
            $supplierLists[$k]['city_id']=$api->getArea($v['city_id']);
            $supplierLists[$k]['district_id']=$api->getArea($v['district_id']);
        }

        $resProduct['total']=count($supplierLists);
        $resProduct['totalPage'] = ceil($resProduct['total']/$pageSize);
        //分页显示
        $page = $pageNum;
        //每页的条数
        $perPage = $pageSize;
        //计算每页分页的初始位置
        $offset = ($page * $perPage) - $perPage;

        //实例化LengthAwarePaginator类，并传入对应的参数

        $supplierLists = new LengthAwarePaginator(array_slice($supplierLists, $offset, $perPage, true), count($supplierLists), $perPage,

            $page);
        return view('Admin/offerer/supplierAjax')
            ->with("supplierLists",$supplierLists)
            ->with("resProduct",$resProduct)
            ->with("id",$id)->with("cpage",$pageNum);

    }
    public  function getOffererAjax(){
        $id=$_POST['id'];
        $newnodes=$_POST['newnodes'];
        $newnodes=json_decode($newnodes,true);

        $offerer_supplier_list=DB::table('admin_supplier_offerer')->where('offerer_id',$id)->get()->toArray();
        foreach ($offerer_supplier_list as $k=>$v){
            $offerer_supplier_list[$k]=(array)$offerer_supplier_list[$k];
            $supplier_list[$k]=(array)DB::table('admin_supplier')->where('id',$offerer_supplier_list[$k]['supplier_id'])->first();
        }
        $toList=[];
        foreach ($newnodes as $k=>$v){
            $newnodes[$k]=(array)$newnodes[$k];
            if($v['checked']){
                //新增数据
                $toList[$k]['id']=$newnodes[$k]['id'];
                $toList[$k]['name']=$newnodes[$k]['name'];
                $toList[$k]['method']="insert";
            }
            else{
                //删除数据
                $toList[$k]['name']=$newnodes[$k]['name'];
                $toList[$k]['method']="delete";
                $toList[$k]['id']=$newnodes[$k]['id'];
            }
        }
        $api=new ApiController();
        $insert_data='';
        $delete_data='';
        foreach ($toList as $k=>$v){
            if($v['method']=='insert'){
                //判断分级
                $sort=$api->getSortByTypeId($v['id']);
                if($sort==1){
                    $insert_data=$insert_data.'<span style="font-weight:bold;color: red">'.$v["name"].'  ></span>  ';
                }
                if($sort==2){
                    $insert_data=$insert_data."<span style='font-weight:bold;'>".$v["name"].'  > </span> ';
                }
                if($sort==3){
                    $insert_data=$insert_data.$v["name"].',';
                }

            }
            if($v['method']=='delete'){
                //判断分级
                $sort=$api->getSortByTypeId($v['id']);
                if($sort==1){
                    $delete_data=$delete_data."<span style='font-weight:bold;color: red'>".$v["name"].' > </span>';
                }
                if($sort==2){
                    $delete_data=$delete_data."<span style='font-weight:bold;'>".$v["name"].' ></span> ';
                }
                if($sort==3){
                    $delete_data=$delete_data.$v["name"].',';
                }
            }
        }
        return view('Admin/offerer/relation')->with("supplier_list",$supplier_list)->with("insert_data",$insert_data)->with("delete_data",$delete_data)->with("jsontoList",json_encode($toList));
    }
    //同步报价人数据
    public  function  offererCommodity()
    {
        $id = $_POST['id'];
        $ids = $_POST['ids'];
        $jsontoList = $_POST['jsontoList'];
        $jsontoList = json_decode($jsontoList, true);
        $where_supplier['offerer_id'] = $id;
        if ($ids) {
            $ids = rtrim($ids, ",");
            $ids = explode(",", $ids);
            for ($i = 0; $i < count($ids); $i++) {
                $where['supplier_id'] = $ids[$i];
                foreach ($jsontoList as $key => $value) {
                    $where['commodity_id'] = $value['id'];
                    $where_supplier['commodity_id'] = $value['id'];
                    if ($value['method'] == "insert") {
                        if (!DB::table("admin_commodity_supplier")->where($where)->count()) {
                            DB::table("admin_commodity_supplier")->insert($where);
                        }
                        if (!DB::table("admin_commodity_offerer")->where($where_supplier)->count()) {
                            DB::table("admin_commodity_offerer")->insert($where_supplier);
                        }
                    }
                    if ($value['method'] == "delete") {
                        if (DB::table("admin_commodity_supplier")->where($where)->count()) {
                            DB::table("admin_commodity_supplier")->where($where)->delete();
                        }
                        if (DB::table("admin_commodity_offerer")->where($where_supplier)->count()) {
                            DB::table("admin_commodity_offerer")->where($where_supplier)->delete();
                        }
                    }
                }
            }

        }
        $msg['message']="同步处理成功！";
        $msg['status']=1;
        return json_encode($msg);
    }
    //更新报价人商品状态值
    public function setCommodity(){
        $data=[];
        $temp=[];
        $api=new ApiController();
        $commodity_supplier_list=DB::table("admin_commodity_offerer")->get()->toArray();
        $offerer_list=DB::table("admin_offerer")->get()->toArray();
        foreach ($offerer_list as $k=>$v){
            $t_id='';
            $g_id='';
            $c_id='';
            $offerer_list[$k]=(array)$offerer_list[$k];

            foreach ($commodity_supplier_list as $key=>$value){
                $commodity_supplier_list[$key]=(array)$commodity_supplier_list[$key];

                if($offerer_list[$k]['id']== $commodity_supplier_list[$key]['offerer_id']){
                    $sort=$api->getSortByTypeId($commodity_supplier_list[$key]['commodity_id']);
                    if($sort==1){
                        $t_id=$t_id.','.$commodity_supplier_list[$key]['commodity_id'];
//                        $t=$api->getTypeName($commodity_supplier_list[$key]['commodity_id']);
//                        $t_id=$t_id.','.$t;

                    }
                    if($sort==2){
                        $g_id=$g_id.','.$commodity_supplier_list[$key]['commodity_id'];
//                        $t=$api->getTypeName($commodity_supplier_list[$key]['commodity_id']);
//                        $g_id=$g_id.','.$t;

                    }
                    if($sort==3){
                        $c_id=$c_id.','.$commodity_supplier_list[$key]['commodity_id'];
//                        $t=$api->getTypeName($commodity_supplier_list[$key]['commodity_id']);
//                        $c_id=$c_id.','.$t;
                    }

                }
            }
            $temp['t_id']=substr($t_id,1);
            $temp['g_id']=substr($g_id,1);
            $temp['c_id']=substr($c_id,1);
            set_time_limit(0);
            DB::table("admin_offerer")->where("id",$offerer_list[$k]['id'])->update($temp);
            //循环更新数据库
        }
        $msg['status']=1;
        $msg['msg']="更新成功";
        return json_encode($msg);
    }


}