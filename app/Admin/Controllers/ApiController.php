<?php
/**
 * 一些通用方法类
 */
namespace App\Admin\Controllers;


use App\Models\Customer;
use App\Models\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ApiController
{
    //省市区三级联动
    public function getAreaNameByParentId(Request $request){
        $id = $request->get('q');
        $list=DB::table("admin_area")->where('area_parent_id',$id)->get()->toArray();
        $arr=[];
        foreach ($list as $k=>$v){
            $list[$k]=(array)$list[$k];
            $temp["id"]=$list[$k]['id'];
            $temp["text"]=$list[$k]['area_name'];
            array_push($arr,$temp);
        }
        return $arr;
    }
    //获取客户名称
    public function getCustomerName(Request $request){
        $id = $request->get('q');
        $list=Customer::where('id',$id)->get([DB::raw('customer_name as text'),DB::raw('customer_name as text')]);
        return $list;
    }
    //获取类别名称
    public function getType(Request $request){
        $id = $request->get('q');
        $p_id=DB::table('admin_type')->where('id',$id)->pluck('parent_id');
        $list=Type::where('id',$p_id)->get(['id', DB::raw('type_name as text')]);
        return $list;
    }
    //根据客户获取商机
    public function getBusinessByCustomerId(Request $request){
        $id = $request->get('q');
        $list=DB::table('admin_business')->where('customer_id', $id)->get()->toArray();
        $toArray=[];
        foreach ($list as $k=>$v){
            $list[$k]=(Array)$list[$k];
            $list[$k]['c_id']=$this->getTypeName($list[$k]['c_id']);
            $list[$k]['p_id']=$this->getTypeName($list[$k]['p_id']);
            $list[$k]['g_id']=$this->getTypeName($list[$k]['g_id']);
            $list[$k]['business_states']=$this->getBusinessStatus($list[$k]['business_states']);
            $list[$k]['te']= $list[$k]['id'].'||'.$list[$k]['created_at'].'||'. $list[$k]['p_id'].'-'. $list[$k]['g_id'].'-'.$list[$k]['c_id'].'||'.$list[$k]['business_states'];
            $toArray[$k]['id']= $list[$k]['id'];
            $toArray[$k]['text']= $list[$k]['te'];
        }
        return $toArray;
    }
    //获取类别名称
    public function getTypeName($id)
    {
        $list=(array)DB::table('admin_type')->where('id',$id)->first();
        if($list){
            return $list['type_name'];
        }
        else{
            return "空";
        }

    }
    public function getBusinessStatus($status)
    {
        switch ($status){
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
    }
    //获取地区名称
    public function getArea($id)
    {
        $list=DB::table('admin_area')->where('id',$id)->pluck('area_name')->toArray();
        if($list){
            return $list[0];
        }
        else{
            return "空";
        }
    }
    //获取分类等级
    public function getSortByTypeId($typeId){
        //判断typeid分类是属于几级？，一级，二级，删除，查询三级分类对应的商品
        $typeList=(Array)DB::table("admin_type")->where("id",$typeId)->first();
        //一级分类
        if($typeList['parent_id']==0){
            return 1;
        }
        else{
            $count=DB::table("admin_type")->where("parent_id",$typeId)->count();
            if($count>0){
                //二级
                return 2;
            }
            if($count==0){
                //三级
                return 3;
            }
        }


    }
    public function getCommodityListByTypeId($typeId){
        $where['c_id']=$typeId;
        $where['commodity_shown']=1;
        $commodityList=(Array)DB::table("admin_commodity")->where("c_id",$typeId)->first();
        return $commodityList;
    }

}
