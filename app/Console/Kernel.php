<?php

namespace App\Console;

use App\Jobs\TripartitePlan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 从数据库获取任务列表
        $tasks = DB::table('detection_tripartite_node')->get();

        foreach ($tasks as $task) {
            // 将任务添加到调度中
            $schedule->job(new TripartitePlan($task->del_url))->everyMinute($task->frequency);
        }
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
