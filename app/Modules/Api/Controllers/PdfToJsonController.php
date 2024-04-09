<?php
namespace App\Modules\Api\Controllers;

use Maatwebsite\Excel\Facades\Excel;
class PdfToJsonController
{
    public function index(){
        $filePath = 'C:\Users\admin\Desktop/123.xlsx';

        // 读取 Excel 文件的第一个表格
        $data = Excel::toCollection([], $filePath)->first();

        // 处理读取到的数据
        $arrayData = $data->toArray();
        $toJson=[];
        foreach ($arrayData as $k=>$v){
            $toJson[$k]["standard_number"]=$arrayData[$k][0];
            $toJson[$k]["name"]=$arrayData[$k][1];

        }
        file_put_contents("./standard2.json", json_encode($toJson, JSON_UNESCAPED_UNICODE));

    }



}