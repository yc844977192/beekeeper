<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class HomeController extends AdminController
{
    public function index(Content $content)
    {
        return $content
            ->title('华安检测')
            ->description('后台管理系统')
            ->row(Dashboard::title())
            ->row(function (Row $row) {
                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });
                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });
                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });
            });
    }
}
