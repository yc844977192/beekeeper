<?php
namespace App\Modules\Api\Controllers;
use App\Jobs\TripartitePlan;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2023/12/9
 * Time: 23:37
 */
class TripartiteController
{
    public function index(){
        $tasks = DB::table('detection_tripartite_node')->get();


        foreach ($tasks as $task) {
            // 将任务添加到调度中
            TripartitePlan::dispatch($task->del_url);
            //$schedule->job(new TripartitePlan($task->ip_address))->everyMinutes($task->frequency);
        }

    }

}