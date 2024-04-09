<?php
namespace Modules\Api\Controllers;
use App\Jobs\TripartitePlan;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2023/12/8
 * Time: 17:04
 */
class TripartiteController
{
    public function index(){
        echo 1;exit;
        TripartitePlan::dispatch();
    }


}