<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Models\Supplier;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
class SupplierController extends AdminController {
    protected function title()
    {
        return trans('供应商');
    }
    protected function grid()
    {
        $grid = new Grid(new Supplier());
        $grid->filter(function (Grid\Filter $filter) {
            $filter->column(1/2 ,function ($filter){
                $filter->like('supplier_name', "名称");
                $filter->where(function ($query) {$query->where('supplier_mobile', $this->input);}, '电话');
                $filter->equal('province_id', '省')->select( function (){
                    $name = DB::table('admin_area')->where("area_parent_id",0)->pluck('area_name', 'id');
                    return $name;
                })->load('city_id', 'api/getAreaNameByParentId');
                $filter->equal('district_id', '区')->select( function (){
                    if(array_key_exists('city_id',$_GET)){
                        $city_id=$_GET['city_id'];
                        $name = DB::table('admin_area')->where("area_parent_id",$city_id)->pluck('area_name', 'id');
                        return $name;
                    }
                });
            });
            $filter->column(1/2, function ($filter) {
                $filter->equal('get_commodity_commodity_id.commodity_id', '一级分类')->select( function (){
                    $name = DB::table('admin_type')->where("type_sort",1)->pluck('type_name', 'id');
                    return $name;
                }) ->load('get_g_id.commodity_id', '/admin/api/group');
                $filter->equal('get_g_id.commodity_id', '二级分类')->select( function (){
                    if(array_key_exists('get_g_id',$_GET)){
                        $name = DB::table('admin_type')->where("parent_id",$_GET['get_commodity_commodity_id']['commodity_id'])->pluck('type_name', 'id');
                        return $name;
                    }

                }) ->load('get_c_id.commodity_id', '/admin/api/group');
                $filter->equal('get_c_id.commodity_id', '二级分类')->select( function (){
                    if(array_key_exists('get_c_id',$_GET)){
                        $name = DB::table('admin_type')->where("parent_id",$_GET['get_g_id']['commodity_id'])->pluck('type_name', 'id');
                        return $name;
                    };
                });
                $filter->equal('city_id', '市')->select( function (){
                    if(array_key_exists('province_id',$_GET)){
                        $province_id=$_GET['province_id'];
                        $name = DB::table('admin_area')->where("area_parent_id",$province_id)->pluck('area_name', 'id');
                        return $name;
                    }
                })->load('district_id', 'api/getAreaNameByParentId');
                $filter->equal('supplier_cooperation_state', '合作状态')->select( function (){
                    return ['0'=>'未联系','1'=>'洽谈中','3'=>'已合作','4'=>'暂不合作'];
                });
            });
        });
        $grid->model()->orderBy('id','desc');
        $grid->column('id', 'ID')->sortable();
        $grid->column('supplier_name','公司名称')->display(function($code) {
            $id = $this->id;
            return '<a href="/admin/supplier/'.$id.'/edit" >'.$code.'</a>';
        });
        $grid->column('supplier_mobile','公司电话');
        $grid->column('supplier_cooperation_state', '合作状态')
            ->display(function($code) {
                //return ['0'=>'未联系','1'=>'洽谈中','3'=>'已合作'];
                if($code==0){
                    return '未联系';
                }
                if($code==1){
                    return '洽谈中';
                }
                if($code==3){
                    return '已合作';
                }
                if($code==4){
                    return '暂不合作';
                }
            });
        //省名
        $grid->column('province_id', '省')
            ->display(function($code) {
                $name = DB::table('admin_area')->where('id', $code)->value('area_name');
                return $name;
            });
        //市名
        $grid->column('city_id', '市')
            ->display(function($code) {
                $name = DB::table('admin_area')->where('id', $code)->value('area_name');
                return $name;
            });
        //区名
        $grid->column('district_id', '区')
            ->display(function($code) {
                $name = DB::table('admin_area')->where('id', $code)->value('area_name');
                return $name;
            });
        $grid->column('created_at', '提交时间')
            ->display(function($code) {
                //$name = date("Y-m-d H:i:s",mktime($code));
                return $code;
            });
        //$grid->column('detectionSnapshots.company_get_time', '快照时间');
        $grid->column('', '最后一次快照时间')->display(function () {
            $snapshot = $this->detectionSnapshots()->orderByDesc('company_get_time')->first(); // 按 company_get_time 字段倒序排序，然后获取第一个关联的快照记录
            $data=$snapshot ? $snapshot->company_get_time : null;
            $str = "<a href='/admin/snapshot?supplier_id=" . $this->id . "'>" . $data . "</a>";
            return $str;
        });
        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });
//        $grid->tools(function ($tools) {
//            $tools->append(view('admin::supplier.setCommodity'));
//
//        });
        $grid->actions(function ($actions) {
            // 没有`delete-button`权限的角色不显示删除按钮
            if (!Admin::user()->can('delete-button')) {
                $actions->disableDelete();
            }
        });
        $grid->disableExport();
        return $grid;
    }
    public function form()
    {
        $form = new Form(new Supplier());
        $form->text('supplier_name', '公司名称');
        //$form->text('supplier_mobile','公司电话')->rules('/^[a-z0-9.-]*$/g');
        $form->text('supplier_mobile','电话')->rules('regex:/^[0-9-]*$/', [
            'regex' => '电话必须为数字和-',
        ])->placeholder('电话必须为数字和-');
        $form->multipleSelect("get_offerer_id","报价人") ->options(function (){
        $list=DB::table("admin_offerer")->get()->toArray();
        $list=(Array)$list;
        $temList=[];
        foreach ($list as $k=>$v){
            $list[$k]=(Array)$list[$k];
            $list[$k]['te']= $list[$k]['offerer_name'].'||'.$list[$k]['offerer_mobile'];
        }
        $plucked = collect($list)->pluck('te','id');
        $plucked->all();
        return $plucked;
    });
        $type_list=DB::table('admin_type')->get()->toArray();
        if($form->isEditing()){
            $id=request()->route()->parameters()['supplier'];
            $relextion_list=DB::table('admin_commodity_supplier')->where('supplier_id',$id)->get()->toArray();
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
        //获取报价人
        $offerer_list=[];
        $id=0;
        if($form->isEditing()){
            $id=request()->route()->parameters()['supplier'];
            $offerer_supplier_list=DB::table('admin_supplier_offerer')->where('supplier_id',$id)->get()->toArray();
            //循环获取报价人信息
            foreach ($offerer_supplier_list as $k=>$v){
                $offerer_supplier_list[$k]=(array)$offerer_supplier_list[$k];
                $temp=(array)DB::table('admin_offerer')->where('id',$offerer_supplier_list[$k]['offerer_id'])->first();
                array_push($offerer_list,$temp);
            }
        }
        $temp_count=DB::table('admin_supplier_offerer')->where('supplier_id',$id)->count();
        $form->html(view("Admin/supplier/get_commodity")->with('temp_count',$temp_count)->with("tlist",json_encode($tlist,true))->with("offerer_list",$offerer_list)->with("id",$id),"关联商品");
        $form->textarea('supplier_scope_service', '业务范围');
        $form->select('supplier_nature', ('公司性质'))
            ->options(function (){
                return ['国企'=>'国企','民企'=>'民企','外企'=>'外企'];
            });
        $form->text('supplier_website', '公司网址');
        $form->text('supplier_state', '公司状态');
        $form->text('supplier_capital', '注册资本');
        $form->text('supplier_address', '公司地址');
        $form->select('supplier_cooperation_state', '合作状态')
            ->options(function (){
                return ['0'=>'未联系','1'=>'洽谈中','3'=>'已合作','4'=>'暂不合作'];
            });
        $form->email('supplier_email', '邮箱');
        $form->text('supplier_wechat', '企业微信');
        $form->text('supplier_applet', '小程序');
        $form->text('supplier_accounts', '公众号');
        $form->distpicker(['province_id', 'city_id', 'district_id']);
        $form->saving(function (Form $form) {
        });
        return $form;
    }
    protected function detail($id)
    {
        $supplierList=DB::table('admin_supplier')
            ->where('id',$id)
            ->first();
        $supplierList=(Array)$supplierList;
        //合作状态处理
        $supplierList['supplier_cooperation_state']=$this->deal_supplier_cooperation_state($supplierList['supplier_cooperation_state']);
        //关联报价人
        $offererLists=[];
        $offererTemp=[];
        $offererRelationList=DB::table('admin_supplier_offerer')
            ->where('supplier_id',$id)
            ->get()->toArray();
        foreach ($offererRelationList as $k=>$v){
            $offererRelationList[$k]=(Array)$offererRelationList[$k];
            array_push($offererTemp,$offererRelationList[$k]['offerer_id']);
        }
        foreach ($offererTemp as $k=>$v){
            array_push($offererLists,$this->getOfferer($v));
        }
        //地区编码转换
        $api=new ApiController();
        if($supplierList["province_id"]){
            $supplierList["province_id"]=$api->getArea($supplierList["province_id"]);
        }
        if($supplierList["city_id"]){
            $supplierList["city_id"]=$api->getArea($supplierList["city_id"]);
        }
        if($supplierList["district_id"]){
            $supplierList["district_id"]=$api->getArea($supplierList["district_id"]);
        }
        return view('Admin/supplier/supplier_show')->with('supplierList',$supplierList)
            ->with('offererLists',$offererLists);
    }
    public function getCommodity($commodityId){
        $commodityList=DB::table('admin_commodity')
            ->where('id',$commodityId)
            ->first();
        return (Array)$commodityList;
    }
    public function getOfferer($offererId){
        $offererList=DB::table('admin_offerer')
            ->where('id',$offererId)
            ->first();
        return (Array)$offererList;
    }
    public function deal_supplier_cooperation_state($supplier_cooperation_state){
        switch ($supplier_cooperation_state){
            case 0:return "未联系";
                break;
            case 1:return "洽谈中";
                break;
            case 3:return "已合作";
                break;

        }
    }
    public function commodityAjax(){
        $pageSize=$_POST['pageSize'];
        $pageNum=$_POST['pageNum'];
        $id=$_POST['id'];
        $getApi=new ApiController();
        //获取商品
        $commodityLists=[];
        $temp=[];
        $relationList=DB::table("admin_commodity_supplier")->where("supplier_id",$id)->get()->toArray();
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
        return view('Admin/supplier/commodityAjax')
            ->with("commodityLists",$commodityLists)
            ->with("resProduct",$resProduct)
            ->with("id",$id)->with("cpage",$pageNum);
    }
    public function offererAjax(){
        $pageSize=$_POST['pageSize'];
        $pageNum=$_POST['pageNum'];
        $id=$_POST['id'];
        $relationList=DB::table('admin_supplier_offerer')
            ->where('supplier_id',$id)
            ->get()->toArray();
        foreach ($relationList as $k=>$v){
            $relationList[$k]=(Array)$relationList[$k];
            $offererLists[$k]=$this->getOfferer($relationList[$k]['offerer_id']);
        }
        $resProduct['total']=count($offererLists);
        $resProduct['totalPage'] = ceil($resProduct['total']/$pageSize);
        //分页显示
        $page = $pageNum;
        //每页的条数
        $perPage = $pageSize;
        //计算每页分页的初始位置
        $offset = ($page * $perPage) - $perPage;

        //实例化LengthAwarePaginator类，并传入对应的参数

        $offererLists = new LengthAwarePaginator(array_slice($offererLists, $offset, $perPage, true), count($offererLists), $perPage,
            $page);
        return view('Admin/supplier/offererAjax')
            ->with("offererLists",$offererLists)
            ->with("resProduct",$resProduct)
            ->with("id",$id)->with("cpage",$pageNum);
    }
    public  function getOffererAjax(){
        $id=$_POST['id'];
        $newnodes=$_POST['newnodes'];
        $newnodes=json_decode($newnodes,true);

        $offerer_supplier_list=DB::table('admin_supplier_offerer')->where('supplier_id',$id)->get()->toArray();
        foreach ($offerer_supplier_list as $k=>$v){
            $offerer_supplier_list[$k]=(array)$offerer_supplier_list[$k];
            $offerer_list[$k]=(array)DB::table('admin_offerer')->where('id',$offerer_supplier_list[$k]['offerer_id'])->first();
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
        //组合成字符串
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
        return view('Admin/supplier/relation')->with("offerer_list",$offerer_list)->with("insert_data",$insert_data)->with("delete_data",$delete_data)->with("jsontoList",json_encode($toList));
    }
    //同步报价人数据
    public  function  offererCommodity()
    {
        $id = $_POST['id'];
        $ids = $_POST['ids'];
        $jsontoList = $_POST['jsontoList'];
        $jsontoList = json_decode($jsontoList, true);
        if ($ids) {
            $ids = rtrim($ids, ",");
            $ids = explode(",", $ids);
            $where_supplier['supplier_id'] = $id;
            for ($i = 0; $i < count($ids); $i++) {
                $where['offerer_id'] = $ids[$i];
                foreach ($jsontoList as $key => $value) {
                    $where['commodity_id'] = $value['id'];
                    $where_supplier['commodity_id'] = $value['id'];
                    if ($value['method'] == "insert") {
                        if (!DB::table("admin_commodity_offerer")->where($where)->count()) {
                            DB::table("admin_commodity_offerer")->insert($where);
                        }
                        if (!DB::table("admin_commodity_supplier")->where($where_supplier)->count()) {
                            DB::table("admin_commodity_supplier")->insert($where_supplier);
                        }
                    }
                    if ($value['method'] == "delete") {
                        if (DB::table("admin_commodity_offerer")->where($where)->count()) {
                            DB::table("admin_commodity_offerer")->where($where)->delete();
                        }
                        if (DB::table("admin_commodity_supplier")->where($where_supplier)->count()) {
                            DB::table("admin_commodity_supplier")->where($where_supplier)->delete();
                        }
                    }
                }
            }

        }
        $msg['message']="同步处理成功！";
        $msg['status']=1;
        return json_encode($msg);
    }
    //更新供应商商品状态值
    public function setCommodity(){
        $temp=[];
        $api=new ApiController();
        $commodity_supplier_list=DB::table("admin_commodity_supplier")->get()->toArray();
        $supplier_list=DB::table("admin_supplier")->get()->toArray();
        foreach ($supplier_list as $k=>$v){
            $t_id='';
            $g_id='';
            $c_id='';
            $supplier_list[$k]=(array)$supplier_list[$k];
            foreach ($commodity_supplier_list as $key=>$value){
                $commodity_supplier_list[$key]=(array)$commodity_supplier_list[$key];
                if($supplier_list[$k]['id']== $commodity_supplier_list[$key]['supplier_id']){
                    $sort=$api->getSortByTypeId($commodity_supplier_list[$key]['commodity_id']);
                    if($sort==1){
                        $t_id=$t_id.','.$commodity_supplier_list[$key]['commodity_id'];
                        //$t=$api->getTypeName($commodity_supplier_list[$key]['commodity_id']);
                        //$t_id=$t_id.','.$t;

                    }
                    if($sort==2){
                        $g_id=$g_id.','.$commodity_supplier_list[$key]['commodity_id'];
                        //$t=$api->getTypeName($commodity_supplier_list[$key]['commodity_id']);
                        //$g_id=$g_id.','.$t;

                    }
                    if($sort==3){
                        $c_id=$c_id.','.$commodity_supplier_list[$key]['commodity_id'];
                        //$t=$api->getTypeName($commodity_supplier_list[$key]['commodity_id']);
                        // $c_id=$c_id.','.$t;
                    }
                }
            }
            $temp['t_id']=substr($t_id,1);
            $temp['g_id']=substr($g_id,1);
            $temp['c_id']=substr($c_id,1);
            set_time_limit(0);
            DB::table("admin_supplier")->where("id",$supplier_list[$k]['id'])->update($temp);
            //循环更新数据库
        }
        $msg['status']=1;
        $msg['msg']="更新成功";
        return json_encode($msg);
    }

}