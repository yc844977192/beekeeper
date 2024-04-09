<?php
namespace App\Modules\Api\Controllers;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\DB;

class XzfgkController
{
    public function index()
    {
        set_time_limit(0);
        for ($i=0;$i<63;$i++){
            $url="http://xzfg.moj.gov.cn/search2.html?PageIndex=".$i;
            $this->delFirst($url);
            sleep(10);
        }
    }

    public function delFirst($url){
        $html = file_get_contents($url);
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $lis = $xpath->query('//li[contains(@class, "list-item")]');
        foreach ($lis as $li) {
            $arr = [];
            $titleDivs = $xpath->query('.//div[@class="title"]', $li);
            foreach ($titleDivs as $titleDiv) {
                // 获取目标 div 元素下的第一个 span 元素
                $spanTag = $titleDiv->getElementsByTagName('span')->item(0);
                // 如果找到了目标 span 元素
                if ($spanTag) {
                    // 获取 span 元素内的第一个 a 标签
                    $aTag = $spanTag->getElementsByTagName('a')->item(0);

                    // 如果找到了目标 a 标签
                    if ($aTag) {
                        // 获取 a 标签的文本内容
                        $arr["title"] = $aTag->textContent;
                        $href = $aTag->getAttribute('href');
                        $arr['detail_url'] = $href;
                        $urlParts = parse_url($href);
                        parse_str($urlParts['query'], $query);
                        $arr["law_id"] = $query['LawID'];
                    }

                }
            }
            try{
                // 获取公布时间
                $entryHeadDiv = $xpath->query('.//li[contains(@class, "publish-date")]', $li)->item(0);
                $textContent = trim($entryHeadDiv->textContent);
                $arr['publish-date'] = substr($textContent, 0, -6); // 去除最后两个字符

                // 获取施行时间
                $entryHeadDiv = $xpath->query('.//li[contains(@class, "implemented-date")]', $li)->item(0);
                $textContent = trim($entryHeadDiv->textContent);
                $arr['implemented-date'] = substr($textContent, 0, -6); // 去除最后两个字符
                DB::table("admin_xzfg")->insert($arr);
            }
            catch (\Exception $e){
                continue;
            }

        }
    }

    public function download(){
        set_time_limit(0);
        while (true){
            $this->getWebSecond();
            sleep(60);
        }
    }

    public function downloadWord(){
        set_time_limit(0);
        while (true){
            $this->getWebSecond2();
            sleep(60);
        }
    }

    public function getWebSecond(){
        set_time_limit(0);
        $list=(array)DB::table("admin_xzfg")->where("status",0)->first();
        $pdf_url='http://xzfg.moj.gov.cn/law/download?LawID='.$list['law_id'].'&type=pdf';
        $word_url='http://xzfg.moj.gov.cn/law/download?LawID='.$list['law_id'];
        $this->downloaded_pdf($pdf_url,$list['title']);
        //sleep(60);
        //$this->downloaded_word($word_url,$list['title']);
        DB::table("admin_xzfg")->where("id",$list['id'])->update(['status'=>1]);
    }

    public function getWebSecond2(){
        set_time_limit(0);
        $list=(array)DB::table("admin_xzfg")->where("status",1)->first();
        $word_url='http://xzfg.moj.gov.cn/law/download?LawID='.$list['law_id'];
        $this->downloaded_word($word_url,$list['title']);
        DB::table("admin_xzfg")->where("id",$list['id'])->update(['status'=>3]);
    }
    public function downloaded_pdf($url, $fileName)
    {
        // 原始字符串
        $string = $fileName;

        // 要替换的特殊字符串
        $specialChars = ['-', ':', '.', '/', '\\'];

        // 替换为空格
        $replacement = ' ';

        // 使用 str_replace 替换特殊字符串为空格
        $newString = str_replace($specialChars, $replacement, $string);

        // 指定保存目录和文件名
        $savePath = 'D:\wamp\www\www\beekeeper\public\uploads\xzfg\pdf/';
        $fileName = $newString . '.pdf';

        // 初始化 cURL
        $ch = curl_init($url);

        // 打开文件句柄以保存文件
        $fileHandle = fopen($savePath . $fileName, 'w+');

        // 设置 cURL 选项
        curl_setopt($ch, CURLOPT_FILE, $fileHandle);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // 允许 cURL 自动跟随重定向
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置超时时间为 30 秒

        // 执行 cURL 请求
        curl_exec($ch);

        // 检查是否有错误发生
        if (curl_errno($ch)) {
            fclose($fileHandle); // 关闭文件句柄
            curl_close($ch); // 关闭 cURL
            return 0;
        } else {
            fclose($fileHandle); // 关闭文件句柄
            curl_close($ch); // 关闭 cURL
            return $newString;
        }
    }
    public function downloaded_word($url, $fileName)
    {
        // 原始字符串
        $string = $fileName;

        // 要替换的特殊字符串
        $specialChars = ['-', ':', '.', '/', '\\'];

        // 替换为空格
        $replacement = ' ';

        // 使用 str_replace 替换特殊字符串为空格
        $newString = str_replace($specialChars, $replacement, $string);

        // 指定保存目录和文件名
        $savePath = 'D:\wamp\www\www\beekeeper\public\uploads\xzfg\word/';
        $fileName = $newString . '.docx';

        // 初始化 cURL
        $ch = curl_init($url);

        // 打开文件句柄以保存文件
        $fileHandle = fopen($savePath . $fileName, 'w+');

        // 设置 cURL 选项
        curl_setopt($ch, CURLOPT_FILE, $fileHandle);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // 允许 cURL 自动跟随重定向
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置超时时间为 30 秒

        // 执行 cURL 请求
        curl_exec($ch);

        // 检查是否有错误发生
        if (curl_errno($ch)) {
            fclose($fileHandle); // 关闭文件句柄
            curl_close($ch); // 关闭 cURL
            return 0;
        } else {
            fclose($fileHandle); // 关闭文件句柄
            curl_close($ch); // 关闭 cURL
            return $newString;
        }
    }

}