<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 13:35
 */
namespace Encore\Admin\Controllers;
use Encore\Admin\Auth\Database\Commodity;
use Encore\Admin\Auth\Database\Type;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
class SupplierController extends Admin2Controller{
    protected function title()
    {
        return trans('供应商');
    }
    protected function grid()
    {
        $supplier = 'Encore\Admin\Auth\Database\Supplier';
        $grid = new Grid(new $supplier());
        $grid->model()->orderBy('id','desc');
        $grid->column('id', 'ID')->sortable();
        $grid->column('supplier_name','公司名称');
        $grid->column('supplier_mobile','公司电话');
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
            });;
        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });
        return $grid;
    }
    public function form()
    {
        $supplier = 'Encore\Admin\Auth\Database\Supplier';
        $form = new Form(new $supplier());
        $form->text('supplier_name', '公司名称')->rules('required');
        $form->mobile('supplier_mobile', '公司电话') ->creationRules (['required','min:11','min:11',"unique:admin_supplier",'numeric'],['unique' => '手机号码必须位为数字，11位，不能重复'])
            ->updateRules (['required','min:11','min:11','numeric',"unique:admin_supplier,supplier_mobile,{{id}}"], ['unique' => ' 手机号码必须位为数字，11位，不能重复']);

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
        if($form->isEditing()){
            $id=request()->route()->parameters()['supplier'];
            $offerer_supplier_list=DB::table('admin_supplier_offerer')->where('supplier_id',$id)->get()->toArray();
            //循环获取报价人信息
            $offerer_list=[];
            foreach ($offerer_supplier_list as $k=>$v){
                $offerer_supplier_list[$k]=(array)$offerer_supplier_list[$k];
                $temp=(array)DB::table('admin_offerer')->where('id',$offerer_supplier_list[$k]['offerer_id'])->first();
                array_push($offerer_list,$temp);
            }
        }
        $form->html(view("admin::supplier.get_commodity")->with("tlist",json_encode($tlist,true))->with("offerer_list",$offerer_list)->with("id",$id),"关联商品");
        $form->textarea('supplier_scope_service', '业务范围');
        $form->select('supplier_nature', ('公司性质'))
            ->options(function (){
                return ['国企'=>'国企','民企'=>'民企','外企'=>'外企'];
            });
        $form->url('supplier_website', '公司网址');
        $form->text('supplier_state', '公司状态');
        $form->text('supplier_capital', '注册资本');
        $form->text('supplier_address', '公司地址');
        $form->select('supplier_cooperation_state', '合作状态')
            ->options(function (){
                return ['0'=>'未联系','1'=>'洽谈中','3'=>'已合作'];
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
    protected function detail($id,Request $request)
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
        return view('admin::supplier.supplier_show')->with('supplierList',$supplierList)
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
        return view('admin::supplier.commodityAjax')
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
        return view('admin::supplier.offererAjax')
            ->with("offererLists",$offererLists)
            ->with("resProduct",$resProduct)
            ->with("id",$id)->with("cpage",$pageNum);
    }
    public  function getOffererAjax(){
        $id=$_POST['id'];
        $newnodes=$_POST['newnodes'];
        $newnodes=json_decode($newnodes,true);
        $tempCount=$_POST['tempCount'];
        $offerer_supplier_list=DB::table('admin_supplier_offerer')->where('supplier_id',$id)->get()->toArray();
        foreach ($offerer_supplier_list as $k=>$v){
            $offerer_supplier_list[$k]=(array)$offerer_supplier_list[$k];
            $offerer_list[$k]=(array)DB::table('admin_offerer')->where('id',$offerer_supplier_list[$k]['offerer_id'])->first();
        }
        if($tempCount>0){
            return view('admin::supplier.relation')->with("offerer_list",$offerer_list);
        }
        else{
            //循环获取报价人信息

            foreach ($offerer_list as $k=>$v){
                $temp=[];
                $temp_offerer=[];
                foreach ($newnodes as $key =>$value){
                    $newnodes[$key]=(array)$newnodes[$key];
                }

                $where['offerer_id']=$offerer_list[$k]['id'];
                $relax=DB::table("admin_commodity_offerer")->where($where)->get()->toArray();
                foreach ($relax as $key=>$value){
                    $relax[$key]=(array)$relax[$key];
                    array_push($temp_offerer,$relax[$key]['commodity_id']);
                }

                $temp=$this->issetNodes($newnodes,$offerer_list[$k]['id']);
                //判断提交过来的节点是否在该用户关联的节点中含有
                if(!array_intersect($temp,$temp_offerer)){
                    unset($offerer_list[$k]);
                }
            }
            return view('admin::supplier.relation')->with("offerer_list",$offerer_list);
        }

    }
    //判断报价人是否在该节点有关联
    public function issetNodes($newnodes,$offerer_id){
        $getApi=new ApiController();
        $temp=[];
        foreach ($newnodes as $k=>$v ){
            $sort=$getApi->getSortByTypeId($newnodes[$k]['id']);
            if($sort==3){
                array_push($temp,$newnodes[$k]['id']);
            }
        }
        return  $temp;
    }
    //同步报价人数据
    public  function  offererCommodity(){
        $ids=$_POST['ids'];
        $id=$_POST['id'];
        $newnodes=$_POST['newnodes'];
        $newnodes=json_decode($newnodes,true);
        $tempCount=$_POST['tempCount'];
        if($ids){
            $ids  =  rtrim ( $ids,"," );
            $ids=explode(",",$ids);
            if($tempCount>0){
                //新增
               for ($i=0;$i<count($ids);$i++){
                   $where['offerer_id']=$ids[$i];
                   foreach ($newnodes as $key =>$value){
                       $newnodes[$key]=(array)$newnodes[$key];
                       $where['commodity_id']=$value['id'];
                       if(!DB::table("admin_commodity_offerer")->where($where)->count()){
                           DB::table("admin_commodity_offerer")->insert($where);
                       }
                   }

               }
                $msg['message']="新增成功";
                $msg['status']=1;
                return json_encode($msg);

            }
            if($tempCount<0){
                for ($i=0;$i<count($ids);$i++){
                    $where['offerer_id']=$ids[$i];
                    foreach ($newnodes as $key =>$value){
                        $newnodes[$key]=(array)$newnodes[$key];
                        $where['commodity_id']=$value['id'];
                        if(DB::table("admin_commodity_offerer")->where($where)->count()){
                            $rs=DB::table("admin_commodity_offerer")->where($where)->delete();
                        }
                    }
                }
                $msg['message']="删除成功";
                $msg['status']=1;
                return json_encode($msg);
            }

        }

    }
}