<?php
namespace App\Modules\Api\Controllers;


use Illuminate\Support\Facades\DB;
/**
 * Class DetectionController
 * @package App\Modules\Api\Controllers
 * 正式上线后，在计划任务添加域名/Api/detection
 */
class DetectionController
{
    public function index(){
        set_time_limit(0);
        while (true){
            $this->dealPlan("baidu_words_data_detection","detection_snapshot");
        }
    }
    public function dealPlan($table,$table_snapshot){
        $list=(array)DB::table($table)->first();
        if($list){
            if($this->is_in_blacklist($list["company_name"])){
                $count=DB::table("admin_supplier")->where("supplier_name",$list["company_name"])->count();
                if($count==0){
                    //第一步，将供应商信息入库
                    $data["supplier_name"]=$list["company_name"];
                    $data["supplier_website"]=$list["company_url"];
                    $data["supplier_scope_service"]=$list["business_name"];
                    $data["created_at"]=date("Y-m-d H:i:s");
                    $supplier_id=DB::table("admin_supplier")->insertGetId($data);
                    //第二步，删除实时库该条数据
                    DB::table($table)->where("id",$list["id"])->delete();
                    //第三步，将该条数据添加到快照库
                    $list["supplier_id"]=$supplier_id;
                    unset($list["id"]);
                    DB::table($table_snapshot)->insert($list);
                }
                else{
                    $supplier_list=(array)DB::table("admin_supplier")->where("supplier_name",$list["company_name"])->first();
                    //第二步，删除实时库该条数据
                    DB::table($table)->where("id",$list["id"])->delete();
                    //第三步，将该条数据添加到快照库
                    $list["supplier_id"]=$supplier_list["id"];
                    unset($list["id"]);
                    DB::table($table_snapshot)->insert($list);
                }
            }
            else{
                //第二步，删除实时库该条数据
                DB::table($table)->where("id",$list["id"])->delete();
                //第三步，将该条数据添加到快照库
                unset($list["id"]);
                DB::table($table_snapshot)->insert($list);
            }
        }
        else{
            exit;
        }

    }
    public function is_in_blacklist($company_name){
        $key = true;
        $list = DB::table("supplier_blacklist")->where("status", 1)->pluck('name')->toArray();
        foreach ($list as $blacklisted_company) {
            if (strpos($company_name, $blacklisted_company) !== false) {
                $key = false;
                break;
            }
        }
        return $key;
    }

}