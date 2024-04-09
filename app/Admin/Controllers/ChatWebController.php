<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2022/7/12
 * Time: 11:53
 */
namespace App\Admin\Controllers;
use App\Models\Bid;
use App\Models\Tripartite;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class ChatWebController extends AdminController
{


    protected function title()
    {
        return trans('检测行业三方竞品公司数据:');
    }

    public function index(Content $content)
    {
        return $content
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append($this->showWeb());
                });
            });
    }
    public  function showWeb(){
        return view("Admin/Ai/chat-web");
    }
    public function my_iframe(){
        $client = new Client();
        $response = $client->request('GET', 'http://www.baidu.com/');

        $contentType = $response->getHeaderLine('Content-Type');
        if (empty($contentType)) {
            $contentType = 'text/html'; // 设置默认内容类型为 text/html
        }
        return response($response->getBody(), $response->getStatusCode())
            ->header('Content-Type', $contentType);
    }
}