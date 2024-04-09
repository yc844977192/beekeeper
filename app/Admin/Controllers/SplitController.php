<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Models\SplitBlacklist;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SplitController extends AdminController {
    protected function title()
    {
        return trans('分词黑名单');
    }
    protected function grid()
    {
        $grid = new Grid(new SplitBlacklist());
        $grid->model()->orderBy('id','desc');
        $grid->filter(function (Grid\Filter $filter) {
            $filter->column(1/2 ,function ($filter){
                $filter->where(function ($query) {$query->where('blacklist_name', $this->input);}, '名称');
            });
            $filter->column(1/2, function ($filter) {
                $filter->between('created_at','提交时间')->date();
            });
        });
        $grid->column('id', 'ID')->sortable();
        $grid->column('blacklist_name','名称');
        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });
        return $grid;
    }
    public function form()
    {
        $form = new Form(new SplitBlacklist());
        $form->display('id', 'ID');
        $form->text('blacklist_name', '名称')->rules('required');
        $form->saving(function (Form $form) {
        });
        return $form;
    }
    protected function detail($id)
    {
        $show = new Show(SplitBlacklist::findOrFail($id));
        $show->field('id', 'ID');
        $show->field('blacklist_name', '名称');
        return $show;
    }

    //定时任务，生成搜索拆分词索引
    public function setSplit(){
        $this->setSplitData();


    }
    //调用黑名单规则
    public function getSplitBlacklist(){
        $list=DB::table("admin_split_blacklist")->get()->toArray();
        $str='';
        foreach ($list as $k=>$v){
            $list[$k]=(array)$list[$k];
            $str=$str.','.$list[$k]['blacklist_name'];
        }
        return substr($str,1);
    }

    public function setSplitData(){
        //商机表
        $list=[];
        $temp_list=[];
        $business_list=DB::table("admin_business")->get()->toArray();
        foreach ($business_list as $k=>$v){
            $business_list[$k]=(array)$business_list[$k];
            if($business_list[$k]['business_requirement_description']){
                $temp_list['split_from_id']=$business_list[$k]['id'];
                $temp_list['split_from_table']="business";
                $temp_list["split_from_data"]=$business_list[$k]['business_requirement_description'];
                $temp_list["name"]='';
                $temp_list["mobile"]='';
                $temp_list["created_at"]=$business_list[$k]['created_at'];
                $temp_list["url"]="/admin/business/".$business_list[$k]['id'];
                array_push($list,$temp_list);
            }
        }
        //客户表
        $customer_list=DB::table("admin_customer")->get()->toArray();
        foreach ($customer_list as $k=>$v){
            $customer_list[$k]=(array)$customer_list[$k];
            $temp_list["split_from_data"]='';
            $temp_list['split_from_id']=$customer_list[$k]['id'];
            $temp_list['split_from_table']="customer";
            $temp_list["name"]=$customer_list[$k]['customer_name'];
            $temp_list["mobile"]=$customer_list[$k]['customer_mobile'];
            $temp_list["created_at"]=$customer_list[$k]['created_at'];
            $temp_list["url"]="/admin/customer/".$customer_list[$k]['id'];
            array_push($list,$temp_list);
        }
        //商品表
        $commodity_list=DB::table("admin_commodity")->get()->toArray();
        foreach ($commodity_list as $k=>$v){
            $commodity_list[$k]=(array)$commodity_list[$k];
            $temp_list["split_from_data"]=$commodity_list[$k]['commodity_name'];
            if($temp_list["split_from_data"]){
                $temp_list['split_from_id']=$commodity_list[$k]['id'];
                $temp_list['split_from_table']="commodity";
                $temp_list["name"]=$commodity_list[$k]['commodity_name'];
                $temp_list["mobile"]='';
                $temp_list["created_at"]=$commodity_list[$k]['created_at'];
                $temp_list["url"]="/admin/commodity/".$commodity_list[$k]['id'];
                array_push($list,$temp_list);
            }
        }
        //供应商
        $supplier_list=DB::table("admin_supplier")->get()->toArray();
        foreach ($supplier_list as $k=>$v){
            $supplier_list[$k]=(array)$supplier_list[$k];
            $temp_list["split_from_data"]=$supplier_list[$k]['supplier_scope_service'];
            $temp_list['split_from_id']=$supplier_list[$k]['id'];
            $temp_list['split_from_table']="supplier";
            $temp_list["name"]=$supplier_list[$k]['supplier_name'];
            $temp_list["mobile"]=$supplier_list[$k]['supplier_mobile'];
            $temp_list["created_at"]=$supplier_list[$k]['created_at'];
            $temp_list["url"]="/admin/supplier/".$supplier_list[$k]['id'];
            array_push($list,$temp_list);
        }
        //报价人
        $offerer_list=DB::table("admin_offerer")->get()->toArray();
        foreach ($offerer_list as $k=>$v){
            $offerer_list[$k]=(array)$offerer_list[$k];
            $temp_list["split_from_data"]="" ;
            $temp_list['split_from_id']=$offerer_list[$k]['id'];
            $temp_list['split_from_table']="offerer";
            $temp_list["name"]=$offerer_list[$k]['offerer_name'];
            $temp_list["mobile"]=$offerer_list[$k]['offerer_mobile'];
            $temp_list["created_at"]=$offerer_list[$k]['created_at'];
            $temp_list["url"]="/admin/offerer/".$offerer_list[$k]['id'];
            array_push($list,$temp_list);
        }
        foreach ($list as $k=>$v){
            //插入
            $data['split_from_id']=$list[$k]['split_from_id'];
            $data['split_from_table']=$list[$k]['split_from_table'];
            $temp=(array)DB::table("admin_split")->where($data)->first();
            if($temp){
                DB::table("admin_split")->where($data)->update(["split_from_data"=>$list[$k]['split_from_data']]);
            }
            else {
                DB::table("admin_split")->insert($list[$k]);

            }
        }
    }
    //遍历split表，循环存存分词关联内容
    public function setSplitContent(){
        set_time_limit(0);
        $list=DB::table("admin_split")->get()->toArray();
        foreach ($list as $k=>$v) {
            $list[$k] = (array)$list[$k];
            if ($list[$k]['split_from_data']) {
                $cut_array = $this->getSplitUserString($list[$k]['split_from_data']);
                $temp = DB::table("admin_split_content")->where('split_id', $list[$k]['id'])->get()->toArray();
                if (sizeof($temp)) {
                    DB::table("admin_split_content")->where('split_id', $list[$k]['id'])->delete();
                    foreach ($cut_array as $key => $value) {
                        //循环将分词存数据库
                        $content_data['split_id'] = $list[$k]['id'];
                        $content_data['split_name'] = $value;
                        $content_data['split_len'] = strlen($value);
                        DB::table("admin_split_content")->insert($content_data);
                    }
                }
                else {
                    foreach ($cut_array as $key => $value) {
                        //循环将分词存数据库
                        $content_data['split_id'] = $list[$k]['id'];
                        $content_data['split_name'] = $value;
                        $content_data['split_weight'] = strlen($value);
                        DB::table("admin_split_content")->insert($content_data);
                    }
                }
            }
            if ($list[$k]['name']) {
                $content_data['split_id'] = $list[$k]['id'];
                $content_data['split_name'] = $list[$k]['name'];
                $content_data['split_weight'] = strlen($list[$k]['name']);
                DB::table("admin_split_content")->insert($content_data);
            }
            if ($list[$k]['mobile']) {
                $content_data['split_id'] = $list[$k]['id'];
                $content_data['split_name'] = $list[$k]['mobile'];
                $content_data['split_weight'] = strlen($list[$k]['mobile']);
                DB::table("admin_split_content")->insert($content_data);
            }
        }
    }
    //分词获取
    public  function getSplitUserString($str){
        //调用黑名单规则
        $blacklist=$this->getSplitBlacklist();
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        //初始化
        $this->jieba = new Jieba();
        $this->finalseg = new Finalseg();
        $this->jieba->init();
        $this->finalseg->init();
        //使用$list[$k]['business_requirement_description']
        $cut_array = $this->jieba->cut($str,false);
        foreach ($cut_array as $key =>$value){
            if(strlen($value)<=3){
                unset($cut_array[$key]);
            }
            if(strpos($blacklist,$value)){
                unset($cut_array[$key]);
            }
        }
        if(strlen($str)<=18){
            array_push($cut_array,$str);
        }
        return $cut_array;

    }
    //更新分词在库中出现次数
    public function updateSplitNum(){
        set_time_limit(0);
        $list=DB::table("admin_split_content")->get()->toArray();
        foreach ($list as $k=>$v){
            $list[$k]=(array)$list[$k];
            //循环查询出出现次数
            $count=DB::table("admin_split_content")->where('split_name',$list[$k]['split_name'])->count();
            DB::table("admin_split_content")->where('split_name',$list[$k]['split_name'])->update(["split_num"=>$count,"split_weight"=>$count*$list[$k]['split_len']]);
        }
        echo("更新成功");exit;
    }

}