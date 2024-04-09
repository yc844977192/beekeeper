<?php
namespace App\Modules\Api\Controllers;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;

class DemoController
{
    public function index(){
        $keywordList[0]=[
            'keywordName'=>'检测',
            'matchType'=>2,
            'phraseType'=>3,
        ];
        $area = [19000];
        $json = '{
    "header": {
        "userName": "sh华夏安信",
        "accessToken": "eyJhbGciOiJIUzM4NCJ9.eyJzdWIiOiJhY2MiLCJhdWQiOiJCRUVLRUVQRVIiLCJ1aWQiOjM5MzU2NDgzLCJhcHBJZCI6IjNiYTNiODhhYWI0MjEzMDc3YzI4ZTliZDRlYmViZDk5IiwiaXNzIjoi5ZWG5Lia5byA5Y-R6ICF5Lit5b-DIiwicGxhdGZvcm1JZCI6IjQ5NjAzNDU5NjU5NTg1NjE3OTQiLCJleHAiOjQxMDI0MTYwMDAsImp0aSI6Ijc3NzMwMDUwODA0MzIyMzg4NTkifQ.7nB5xZhfEVIgxWMfQFAuw_Qenps6Uwzi5c8cGDD6QadyeVKG6WP3TthaGoQkUy8-"
    },
    "body": {
        "searchRegions": ' . json_encode($area) . ',
        "bidWordSource": "wordList",
        "device": 0,
        "orderBy": "price",
        "order": "desc",
        "keywordList": ' . json_encode($keywordList) . '
    }
}';

        $url = 'https://api.baidu.com/json/sms/service/PvSearchFunction/getPvSearch';
        $res = $this->post_curl($url, $json);
        var_dump($res[0]['data']);
        exit;


    }
    public function zh(){
       $list=DB::table("data_gather_into_data")->where("key",'like',"%土木建筑%")->get()->toArray();
       foreach ($list as $k=>$v){
           $list[$k]=(array)$list[$k];
           $time=json_decode($list[$k]['time']);
           $data=json_decode($list[$k]['data']);
           $into=[];
           foreach ($time as $kk=>$vv){
               $into[$kk]['data_name']=$list[$k]["key"];
               $into[$kk]['time']=$time[$kk];
               $into[$kk]['sum_data']=$data[$kk];
           }
           DB::table("data_gather_into_data_detail")->insert($into);
       }

    }
    function post_curl($url, $json)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json); //$data是每个接口的json字符串
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不加会报证书问题
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不加会报证书问题
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        $data = json_decode(curl_exec($ch), true);
        curl_close($ch);
        if(!$data){
            return 0;
        }
        if ($data['header']['desc'] != 'success') {
            //请求失败 直接终止
            return 0;
        }
        if(is_array($data)){
            if(sizeof($data)>0){
                return $data['body']['data'];
            }
            else{
                return 0;
            }

        }
        else{
            return 0;
        }

    }
    public function testBaiDu(){
        $html=file_get_contents("t1.txt");
        //公司名
        $Pattern = '/<div class="ec_desc">(.*?)><\/div>/ism';
        preg_match_all($Pattern, $html,$company_name);

        $data = trim(strip_tags($company_name[0][0]));

        //公司名
    /// 定义正则表达式，匹配 class 为 ec-showurl-line 的内容
//            $pattern = '/<span class="ec-showurl-line">(.*?)<\/span>/is';
//
//    // 执行匹配
//            preg_match_all($pattern, $html, $matches);
//
//            $desc=trim(strip_tags($matches[0][0]));

    var_dump($data);exit;
    }




}