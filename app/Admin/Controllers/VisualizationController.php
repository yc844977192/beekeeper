<?php

namespace App\Admin\Controllers;


use Encore\Admin\Controllers\AdminController;

class VisualizationController extends AdminController
{
    protected function title()
    {
        return trans('数据可视化');
    }

    public function grid()
    {
        return view("Admin/Visualization/chat-web");

    }



}
