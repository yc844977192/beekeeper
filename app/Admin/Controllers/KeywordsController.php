<?php

namespace App\Admin\Controllers;

use App\Models\Keywords_new;
use App\Models\Warehouse;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Encore\ECharts\ECharts;
use Encore\Admin\Grid;
use Encore\Admin\Form;
use App\Models\Keywords;
use Illuminate\Support\Facades\DB;


class KeywordsController extends AdminController
{
    protected function title()
    {
        return trans('关键字');
    }
    public function index(Content $content){
        return $content
            ->title('keywords')
            ->description('')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append($this->grid());
                });
            });
    }
    public  function  showData(Content $content){
        return $content->header('字段追踪')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $list=(array)DB::table("arithmetic_data")->where("warehouse_id",$_GET['keyword_id'])->first();
                    $keyword_list=(array)DB::table("warehouse_by_detection")->where("id",$_GET['keyword_id'])->first();
                    $chartData = [
                        'title' => '示例折线图',
                        'legend' => [
                            'data' => [$keyword_list['query_name']],
                            'selected' => [$keyword_list['query_name'] => true]
                        ],
                        'yAxisName' => '数量',
                        'xAxisData' =>json_decode($list["time"]) ,
                        'seriesData' => [
                            [
                                'name' => $keyword_list['query_name'],
                                'type' => 'line',
                                'stack' => '数量',
                                'data' => json_decode($list["data"])
                            ],
                        ]
                    ];
                    $options = [
                        'chartId' => 1,
                        'height' => '400px',
                        'chartJson' => json_encode($chartData)
                    ];
                    //显示处理
                    $column->row(new Box('averageMonthPv', ECharts::line($options)));
                });



//                $row->column(12, function (Column $column) {
//                    $column->append($this->showList());
//                });
            })
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $list=(array)DB::table("arithmetic_regression_data")->where("warehouse_id",$_GET['keyword_id'])->first();
                    $keyword_list=(array)DB::table("warehouse_by_detection")->where("id",$_GET['keyword_id'])->first();
                    //总量
                    $chartData = [
                        'title' => '示例折线图',
                        'legend' => [
                            'data' => [$keyword_list['query_name']],
                            'selected' => [$keyword_list['query_name'] => true]
                        ],
                        'yAxisName' => '数量',
                        'xAxisData' => json_decode($list["time"]) ,
                        'seriesData' => [
                            [
                                'name' => $keyword_list['query_name'],
                                'type' => 'line',
                                'stack' => '数量',
                                'data' => json_decode($list["data"])
                            ],
                        ]
                    ];
                    $options = [
                        'chartId' => 2,
                        'height' => '400px',
                        'chartJson' => json_encode($chartData)
                    ];
                    //显示处理
                    $column->row(new Box('回归值', ECharts::line($options)));
                });
            })
            ;
    }
    public function showList(){
        $grid = new Grid(new Keywords_new());
        $grid->model()->where('keywords_id',$_GET['keywords']);
        $grid->column('id', 'ID');
        $grid->column('title', 'title')->display(function($code) {
            $id = $this->id;
            $a="/admin/keywords-news/".$id."/edit";
            $url='<a target="_blank" href="'.$a.'">'.$code.'</a>';
            return $url;
        });
        $grid->column('keywords_id', 'keywords')->display(function($code) {
            $list=(array)DB::table("admin_keywords")->where("id",$code)->first();
            return $list["keywords"];
        });
        $grid->column('created_at', 'create_time');
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableFilter();
        $grid->disableActions();
        $grid->disableColumnSelector();
        $grid->actions(function ($actions) {
        // 去掉删除
        $actions->disableDelete();
        // 去掉编辑
        $actions->disableEdit();
        // 去掉查看
        $actions->disableView();
        });
        return $grid;
    }
    public function grid()
    {
        $grid = new Grid(new Warehouse());
        $grid->model()->orderBy('id','desc');
        $grid->model()->join('warehouse_link_data', 'warehouse_link_data.warehouse_id', '=', 'admin_keywords_warehouse.id')
            ->where('warehouse_link_data.data_id', 0)
            ->select('admin_keywords_warehouse.*');
        //$grid->model()->orderBy('id','desc');
        $grid->column('id', 'ID');
        $grid->column('keywords', '关键字')->display(function($code) {
            $id = $this->id;
            return  '<a target="_blank" href="/admin/keywords/showData?keyword_id='.$id.'">'.$code.'</a>';
        });
//        $grid->column('status', 'status')
//            ->display(function($code) {
//                if($code==0){
//                    return '<span style="color: red">Off</span>';
//                }
//                if($code==1){
//                    return 'On';
//                }
//            });
//        $grid->column('Statistics', 'Statistics')->display(function($code) {
//            $id = $this->id;
//            $list=(array)DB::table("admin_keywords")->where("id",$id)->first();
//            $a="/admin/keywords_ranking?keyword_id=".$list['keyword_id'];
//            $url='<a target="_blank" href="'.$a.'">'.'统计'.'</a>';
//            return $url;
//        });
//        $grid->column('created_at', 'create_time');
//        $grid->column('', 'days')
//            ->display(function() {
//                $id = $this->id;
//                $list=(array)DB::table('admin_keywords')->where("id",$id)->first();
//                $count = DB::table('admin_stalk_data')->where("keyword_id",$list['keyword_id'])->distinct()->count();
//            return $count;
//        });
//        $grid->disableCreateButton();
//        $grid->disableExport();
//        $grid->disableRowSelector();
        $grid->actions(function ($actions) {

            // 去掉删除
            $actions->disableDelete();

            // 去掉编辑
            $actions->disableEdit();

            // 去掉查看
            $actions->disableView();
        });
        return $grid;
    }
    public function form()
    {
        $form = new Form(new Warehouse());
        $form->html(view("keywords.inputIn"),"keywords");
//        $form->select('status', 'status')
//            ->options(function (){
//                return ['1'=>'On','0'=>'Off'];
//            });
        $form->ignore("status");
        $kid = 0;
        $form->saving(function (Form $form) {
            $model = $form->model();
            $keywords = $form->input('keywords1');
            // 检查关键字是否已经存在
            $exists = DB::table('admin_keywords_warehouse')->where('keywords', $keywords)->exists();
            if ($exists) {
                // 如果关键字已经存在，显示警告消息并退回到列表页面
                admin_toastr('keywords已存在！', 'warning');
                return redirect('admin/keywords');
            }
            // 如果关键字不存在，继续保存表单数据
            $model->keywords = $form->input('keywords1');
            $model->save();
        });
        $form->saved(function (Form $form)use (&$kid)  {
            $id=$form->model()->id;
            $data['warehouse_id']=$id;
            $data['data_id']=0;
            //warehouse_link_data
            DB::table("warehouse_link_data")->insert($data);
        });
        return $form;
    }
    public function checkKeywords(){
        $keywords=$_POST["keywords"];
        $keywordList=[];
        $keywordList[] = [
            'keywordName' => $keywords,
            'matchType' => '1',
            'phraseType' => '1'
        ];
        $json = '{"header":{"userName":"sh华夏安信","accessToken":"eyJhbGciOiJIUzM4NCJ9.eyJzdWIiOiJhY2MiLCJhdWQiOiJCRUVLRUVQRVIiLCJ1aWQiOjM5MzU2NDgzLCJhcHBJZCI6IjNiYTNiODhhYWI0MjEzMDc3YzI4ZTliZDRlYmViZDk5IiwiaXNzIjoi5ZWG5Lia5byA5Y-R6ICF5Lit5b-DIiwicGxhdGZvcm1JZCI6IjQ5NjAzNDU5NjU5NTg1NjE3OTQiLCJleHAiOjQxMDI0MTYwMDAsImp0aSI6Ijc3NzMwMDUwODA0MzIyMzg4NTkifQ.7nB5xZhfEVIgxWMfQFAuw_Qenps6Uwzi5c8cGDD6QadyeVKG6WP3TthaGoQkUy8-"},"body":{"bidWordSource":"wordList","device":0,"orderBy":"price","order":"desc","keywordList":' . json_encode($keywordList) . '}}';
        $url = 'https://api.baidu.com/json/sms/service/PvSearchFunction/getPvSearch';
        $res = $this->post_curl($url, $json);
        $msg=[];
        if($res['0']['data']){
            $msg['status']=1;
            $msg['adv']=$res['0']['data'][0]['averageMonthPv'];
            $msg['msg']="该字段可以被查询！";
        }
        else{
            $msg['status']=0;
            $msg['msg']="该字段不可以被查询！";
        }
        return json_encode($msg);
    }

    public function post_curl($url, $json)
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
        return $data['body']['data'];
    }


}
