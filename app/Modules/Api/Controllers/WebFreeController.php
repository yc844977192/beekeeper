<?php
namespace App\Modules\Api\Controllers;

use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\DB;

class WebFreeController
{
    public function index(){
        set_time_limit(0);
        for ($i=1;$i<99;$i++){
            $url='https://www.webfree.net/difang-biaozhun/?cp='.$i;
            $this->getWebFreeFirst($url);
            sleep(120);
        }
    }
    public function downloadPdf(){
        set_time_limit(0);
       while (true){
           $this->getWebSecond();
           sleep(60);
       }
    }
    public function getWebSecond(){
        $list=(array)DB::table("webfree_standard")->where("status",0)->first();
        $path = parse_url($list["url"], PHP_URL_PATH);

        preg_match('/(\d+)$/', $path, $matches);

        $lastNumber = isset($matches[0]) ? $matches[0] : null;

        $code=$this->getCode();
        $url=$list["url"].'?wpdmdl='.$lastNumber.'&_wpdmkey='.$code;
        $r=$this->downloaded_pdf($url,$list["title"]);

        if($r){
            DB::table("webfree_standard")->where("id",$list['id'])->update(['status'=>1,"file_path"=>$r.".pdf"]);
        }

    }
    public function getWebFreeFirst($url){
        $html = file_get_contents($url);
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $divs = $xpath->query('//div[contains(@class, "col-lg-12 col-md-12 col-12")]');
        foreach ($divs as $div) {
            $arr=[];
            // 获取当前 div 下 class 为 "entry-head" 的 div
            $entryHeadDiv = $xpath->query('.//div[contains(@class, "entry-head")]', $div)->item(0);

            if ($entryHeadDiv) {
                // 获取 h2 下的 a 标签
                $aTag = $xpath->query('.//h2/a', $entryHeadDiv)->item(0);

                if ($aTag) {
                    // 获取 URL 和文字
                    $arr['url'] = $aTag->getAttribute('href');
                    $arr['title']= $aTag->nodeValue;
                    try{
                        DB::table("webfree_standard")->insert($arr);
                    }
                    catch (\Exception $e){
                        continue;
                    }

                }
            }
        }
    }
    public function getCode(){
        // 目标 URL
        $url = 'https://www.webfree.net/wp-json/wpdm/validate-password';

        // 初始化 cURL
        $ch = curl_init($url);

        // 设置 cURL 选项
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '');  // 不带参数的 POST 请求
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // 执行 cURL 请求并获取响应
        $response = curl_exec($ch);

        // 检查是否有错误发生
        if (curl_errno($ch)) {
            return 'Curl error: ' . curl_error($ch);
        }

        // 关闭 cURL 资源
        curl_close($ch);
        // 输出响应
        $alist=json_decode($response,true);
        // 获取当前 URL
        $currentUrl = $alist["downloadurl"];

        // 使用 parse_url 函数获取 URL 中的查询字符串部分
        $queryString = parse_url($currentUrl, PHP_URL_QUERY);

        // 使用 parse_str 函数将查询字符串解析为关联数组
        parse_str($queryString, $queryParams);

        // 获取 _wpdmkey 参数的值
        $wpdmkeyValue = isset($queryParams['_wpdmkey']) ? $queryParams['_wpdmkey'] : null;
        return $wpdmkeyValue;
    }
    public function downloaded_pdf($url,$fileName){
        // 原始字符串
        $string = $fileName;

        // 要替换的特殊字符串
        $specialChars = ['-', ':', '.', '/', '\\'];

        // 替换为空格
        $replacement = ' ';

        // 使用 str_replace 替换特殊字符串为空格
        $newString = str_replace($specialChars, $replacement, $string);

        // 指定保存目录和文件名
        $savePath = 'D:\wamp\www\www\beekeeper\public\uploads\pdf\gb/';
        $fileName = $newString.'.pdf';

        // 初始化 cURL
        $ch = curl_init($url);

        // 打开文件句柄以保存文件
        $fileHandle = fopen($savePath . $fileName, 'w+');

        // 设置 cURL 选项
        curl_setopt($ch, CURLOPT_FILE, $fileHandle);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // 执行 cURL 请求
        curl_exec($ch);


        // 检查是否有错误发生
        if (curl_errno($ch)) {
            return 0;
        } else {
           return $newString;
        }
        fclose($fileHandle);
        curl_close($ch);
    }

}