<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use Encore\Admin\Controllers\AdminController;
use App\Models\Supplier;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use App\Models\Type;
use App\Models\Commodity;
use Illuminate\Pagination\LengthAwarePaginator;

class CommodityController extends AdminController {
    protected function title()
    {
        return trans('商品');
    }
    protected function grid()
    {

        $grid = new Grid(new Commodity());
        $grid->model()->orderBy('id','desc');
        $grid->filter(function (Grid\Filter $filter) {
            $filter->column(1/2 ,function ($filter){
                $filter->like('commodity_name', "商品名称");
            });
            $filter->column(1/2, function ($filter) {
                $filter->between('created_at','提交时间')->date();
            });
        });
        $grid->column('id', 'ID')->sortable();
        $grid->column('commodity_name','商品名称')->display(function($code) {
            $id = $this->id;
            return '<a href="/admin/commodity/'.$id.'/edit" >'.$code.'</a>';
        });
        $grid->column('p_id', '一级分类')
            ->display(function($code) {
                $name = DB::table('admin_type')->where('id', $code)->value('type_name');
                return $name;
            });
        $grid->column('g_id', '二级分类')
            ->display(function($code) {
                $name = DB::table('admin_type')->where('id', $code)->value('type_name');
                return $name;
            });
        $grid->column('c_id', '三级分类')
            ->display(function($code) {
                $name = DB::table('admin_type')->where('id', $code)->value('type_name');
                return $name;
            });
        $grid->column('created_at', '提交时间');
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
        $grid->disableExport();
        return $grid;
    }
    public function form()
    {
        $form = new Form(new Commodity());
        $form->text('commodity_name', '商品名称')->rules('required');
        $form->multipleSelect("get_commodity_supplier_id","供应商") ->options(function (){
            return Supplier::pluck('supplier_name', 'id');
        });
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

        $form->ueditor('commodity_price',__('商品价格'));
        $form->ueditor('web_business_article', '业务描述');
        $form->text('commodity_url', '专题URL');
        $form->text('commodity_label', '标签');
        $form->select('commodity_hot', ('设为热门'))
            ->options(function (){
                return ['1'=>'是','0'=>'否'];
            });
        $form->select('commodity_shown', ('是否显示'))
            ->options(function (){
                return ['1'=>'是','0'=>'否'];
            });
        $form->select('is_region', ('开启定位'))
            ->options(function (){
                return ['1'=>'是','0'=>'否'];
            });
        $form->textarea('commodity_scenarios', '场景');
        $form->textarea('commodity_details', '商品详情');
        $form->file('commodity_thumb','缩略图')->move('/uploads/image/'.date("Y").'/'.date("m").'/'.date("d"));
        $form->text('commodity_seo_keywords', '商品SEO关键字');
        $form->textarea('commodity_seo_description', '商品SEO描述');
        if ($form->isCreating()) {
            $form->html(view("Admin/commodity/fqa"),"问题QA");
        }else{
            $id=request()->route()->parameters()["commodity"];
            $commodity_fqa=DB::table("commodity_fqa")->where("commodity_id",$id)->get()->toArray();
            if(!empty($commodity_fqa)){
                foreach ($commodity_fqa as $k=>$v){
                    $commodity_fqa[$k]=(array)$commodity_fqa[$k];
                }
            }
            $form->html(view("Admin/commodity/fqa")->with("commodity_fqa",$commodity_fqa),"问题FQA");
        }

        $form->saved(function (Form $form) {
            $id = $form->model()->getKey();
            DB::table("commodity_fqa")->where("commodity_id",$id)->delete();
            // 获取表单提交的数据
            $fqa = $form->input('question');
            $answer = $form->input('answer');
            foreach ($fqa as $k=>$v){
                $into_data['title']=$fqa[$k];
                $into_data['content']=$answer[$k];
                $into_data['commodity_id']=$id;
                DB::table("commodity_fqa")->insert($into_data);
            }
        });
        return $form;
    }
    protected function detail($id)
    {
        $commodityLogList=DB::table('admin_commodity')
            ->where('id',$id)
            ->get()->toArray();
        $commodityLogList=(Array)$commodityLogList[0];
        $getApi=new ApiController();
        if($commodityLogList['p_id']){
            $commodityLogList['p_id']=$getApi->getTypeName($commodityLogList['p_id']);
        }
        if($commodityLogList['g_id']){
            $commodityLogList['g_id']=$getApi->getTypeName($commodityLogList['g_id']);
        }
        if($commodityLogList['c_id']){
            $commodityLogList['c_id']=$getApi->getTypeName($commodityLogList['c_id']);
        }


        if($commodityLogList['commodity_hot']==1){
            $commodityLogList['commodity_hot']='是';
        }
        else{
            $commodityLogList['commodity_hot']='否';
        }
        if($commodityLogList['commodity_shown']==1){
            $commodityLogList['commodity_shown']='是';
        }
        else{
            $commodityLogList['commodity_shown']='否';
        }
        //供应商列表
        $supplierLists=[];
        $temp=[];
        $api=new ApiController();
        foreach ($supplierLists as $k=>$v){
            $supplierLists[$k]['province_id']=$api->getArea($v['province_id']);
            $supplierLists[$k]['city_id']=$api->getArea($v['city_id']);
            $supplierLists[$k]['district_id']=$api->getArea($v['district_id']);
        }
        return view('commodity.commodity_show')->with('commodityLogList',$commodityLogList)->with('supplierLists',$supplierLists);

    }
    public function getSupplier($supplierId){
        $supplierList=DB::table('admin_supplier')
            ->where('id',$supplierId)
            ->first();
        return (Array)$supplierList;
    }
    public function supplierAjax(){
        $pageSize=$_POST['pageSize'];
        $pageNum=$_POST['pageNum'];
        $id=$_POST['id'];
        //根据商品id获取typeid
        $typeId=$this->getTypeIdByCommodityId($id);

        $relationList=DB::table('admin_commodity_supplier')
            ->where('commodity_id',$typeId)
            ->get()->toArray();
        foreach ($relationList as $k=>$v){
            $relationList[$k]=(Array)$relationList[$k];
            $temp=$this->getSupplier($relationList[$k]['supplier_id']);
            if($temp){
                $supplierLists[$k]=$this->getSupplier($relationList[$k]['supplier_id']);
            }

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
        return view('commodity.supplierAjax')
            ->with("supplierLists",$supplierLists)
            ->with("resProduct",$resProduct)
            ->with("id",$id)->with("cpage",$pageNum);
    }
    public  function offererAjax(){
        $pageSize=$_POST['pageSize'];
        $pageNum=$_POST['pageNum'];
        $id=$_POST['id'];
        //根据商品id获取typeid
        $typeId=$this->getTypeIdByCommodityId($id);
        $relationList=DB::table('admin_commodity_offerer')
            ->where('commodity_id',$typeId)
            ->get()->toArray();
        foreach ($relationList as $k=>$v){
            $relationList[$k]=(Array)$relationList[$k];
            $temp=$this->getOfferer($relationList[$k]['offerer_id']);
            if($temp){
                $offererLists[$k]=$this->getOfferer($relationList[$k]['offerer_id']);
            }
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
        return view('commodity.offererAjax')
            ->with("offererLists",$offererLists)
            ->with("resProduct",$resProduct)
            ->with("id",$id)->with("cpage",$pageNum);
    }
    public function getOfferer($offererId){
        $offererList=DB::table('admin_offerer')->where('id',$offererId)->first();
        return (Array)$offererList;
    }
    public  function getTypeIdByCommodityId($commmodity_id){
        $list=(array)DB::table('admin_commodity')
            ->where('id',$commmodity_id)
            ->first();
        if($list){
            if($list['c_id']==0){
                return 0;
            }
            else{
                return $list['c_id'];
            }
        }
        else{
            return 0;
        }
    }
    public function setCommodityStaticById(){
        $client = new Client();
        $response = $client->post('https://www.jiancefeng.com/api/setCommodityStaticById', [
            'verify' => false, // 跳过 SSL 验证
            'form_params' => [
                'id' => $_POST["id"],
                // 添加其他需要的 POST 数据
            ],
        ]);

        $response2 = $client->post('https://m.jiancefeng.com/api/setCommodityStaticById', [
            'verify' => false, // 跳过 SSL 验证
            'form_params' => [
                'id' => $_POST["id"],
                // 添加其他需要的 POST 数据
            ],
        ]);

        $content = $response->getBody()->getContents(); // 获取响应内容
        $content2 = $response2->getBody()->getContents(); // 获取响应内容
        return $content2;
    }
    public function test(){
        $client = new Client();
        $response = $client->post('https://www.jiancefeng.com/api/setCommodityStaticById', [
            'verify' => false, // 跳过 SSL 验证
            'form_params' => [
                'id' => 239,
                // 添加其他需要的 POST 数据
            ],
        ]);
        $content = $response->getBody()->getContents();
        var_dump($content) ;
    }

}