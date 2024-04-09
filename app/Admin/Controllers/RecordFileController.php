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

class RecordFileController  extends AdminController {


    protected function title()
    {
        return trans('400EXCELL上传:');
    }
    public function index(Content $content){
        return $content
            ->title('录音文件:')
            ->description('录音文件')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append($this->showRecord());
                });
            });
    }
    public function showRecord(){
        return view("Admin/Call/record");
    }

    public function uploadFiles(Request $request){
        $input = $request->all();
        $filesNames=$input['name'];
        if(sizeof($filesNames)){
            foreach ($filesNames as $k=>$v){
                $handler = opendir($v);
                $lastPart = basename($v);
                while( ($filename = readdir($handler)) !== false )
                {

                    //略过linux目录的名字为'.'和‘..'的文件
                    if($filename != '.' && $filename != '..')
                    {
                        $intoFile="4000949315/".$lastPart."/".$filename;
                        $list=explode("_",$filename);
                        $where['call_number'] = $list[2];
                        $where['answer_number'] = substr($list[3], 1);
                        $where['record_file'] = null;
                        $today = now()->toDateString();
                        try {
                            DB::table("admin_call_center")->where($where)->whereDate('created_at', $today)->update(['record_file'=>$intoFile]);
                        } catch (\Exception $e) {
                            // 打印异常信息
                            echo $e->getMessage();
                        }
                    }
                }
            }
        }

        return response()->json(['message' => '上传成功']);

    }






}