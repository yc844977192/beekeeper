<?php
/**
 * 一些通用方法类
 */
namespace App\Admin\Controllers;


class CommonController
{
    //商机状态数字转文字
    public function getBusinessStatus($status)
    {
        switch ($status){
            case 0:
                return "待分配";
                break;
            case 1:
                return "已分配";
                break;
            case 3:
                return "跟进中";
                break;
            case 4:
                return "失败";
                break;
            case 5:
                return "暂缓";
                break;
            case 6:
                return "成交";
                break;
            case 7:
                return "放弃";
                break;
        }
    }
}
