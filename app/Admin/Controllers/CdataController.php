<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Imports\ExcelImportClass;
use App\Models\Call;
use App\Models\Fuma;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Storage;

class CdataController {


    protected function title()
    {
        return trans('控制中心:');
    }
    public function index(Content $content){
        return $content
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append($this->showData());
                });
            });
    }
    public function showData(){
        return view("Admin/Cdata/cdata");
    }
    public  function  delAjax3_5(){
        if(array_key_exists('gptinput',$_POST)){
            $data["prompt"]=$_POST['gptinput'];
            $data["roles_prompt"]="";
            if(array_key_exists('intoRoles',$_POST)){
                $data["roles_prompt"]=$_POST['intoRoles'];
            }
            $jsonList=[];
            $jsonList["max_tokens"]=2000;
            $jsonList["model"]="gpt-3.5-turbo";
            $jsonList["temperature"]=0.8;
            $jsonList["top_p"]=1;
            $jsonList["presence_penalty"]=1;
            $jsonList["stream"]=false;
            $jsonList["messages"]=[
                ["role"=>"system","content"=>$data["roles_prompt"]],
                ["role"=>"user","content"=>$data["prompt"]]
            ];
            $jsonData=json_encode($jsonList);
            $response=$this->getByDocker($jsonData);
            return $response;
            $a=json_decode($response,true);
            $msg=[];
            if($a['choices']){
                $msg['data']['html']=trim($a['choices'][0]['message']['content']);
                //存库，扣积分
                $chatRecordList['user']=$data["prompt"];
                $chatRecordList['AI.Chat']=$msg['data']['html'];

                if($a['choices'][0]['message']['content']==""){
                    $msg['data']['html']="服务器开小差了，请再次提问";
                }
                $msg['code']=200;
                $msg['data']['title']="土狗AI";
                $msg['data']['msg']=$response;
                $msg['data']['toData']=json_encode($data);
            }
            else{
                $msg['code']=500;
                $msg['data']['html']='';
                $msg['data']['title']="土狗AI";
                $msg['data']['msg']=$response;
                $msg['data']['toData']=json_encode($data);
            }
            return json_encode($msg);
        }


    }

    //请求docker
    public function getByDocker($jsonData){
        $headers = array(
            'Content-Type: application/json',
            'Authorization:419649000a7849308a40f3e61ba41eaf'
        );
        // 发送 API 请求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "aidocket.jiancefeng.com/v1/chat/completions");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER , $headers);
        $response = curl_exec($ch);
        return $response;
    }




}