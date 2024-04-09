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

class CallDealController  extends AdminController {


    protected function title()
    {
        return trans('400EXCELL上传:');
    }
    public function index(Content $content){
        return $content
            ->title('文件上传:')
            ->description('客服电话文件上传')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append($this->showCall());
                });
            });
    }

    public function  showCall(){
        set_time_limit(0);
        return view("Admin/Call/callDeal");
    }

    public function upload_excel(Request $oRequest){
        //获取文件对象
        $oFile = $oRequest->file('excel_file');
        if ($oFile->isValid()) {
            //判断文件扩展是否符合要求
            $aAllowExtension = ['xls', 'csv', 'xlsx'];
            $sFileExtension = $oFile->getClientOriginalExtension();
            if (!in_array($sFileExtension, $aAllowExtension)) {
                return $this->response()->error('您上传的文件后缀不支持，支持' . implode(',', $aAllowExtension));
            }
            //判断大小是否符合20M
            if ($oFile->getClientSize() >= 20480000) {
                return $this->response()->error('您上传的的文件过大，最大20M');
            }         //移动到文件夹
            $sFilePath = public_path('uploads/excell');
            $sFileName = date('YmdHis') . '.' . $sFileExtension;
            Storage::disk('admin')->put('uploads/excell/' . $sFileName, file_get_contents($oFile->getRealPath()));
            Excel::import(new ExcelImportClass(1), $sFilePath . '/' . $sFileName);
            return response()->json(['message' => '上传成功']);
        }

    }




}