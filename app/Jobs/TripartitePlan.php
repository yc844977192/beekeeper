<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;

class TripartitePlan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $ip_address;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ip_address)
    {
        $this->ip_address = $ip_address;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $browsershot = new Browsershot();
            $browsershot->setUrl($this->ip_address);

            // 使用 --no-sandbox 选项
            $browsershot->setOption('args', ['--no-sandbox']);
            $browsershot->setOption('timeout',0); // 设置超时时间为 60 秒，单位为毫秒


            // 获取保存路径，保存到 public/screenshot 目录下
            $savePath = public_path('screenshot/' . date("Y-m-d") . $this->ip_address . '.png');

            // 检查目录是否存在，如果不存在则创建
            $directory = pathinfo($savePath, PATHINFO_DIRNAME);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // 保存截图
            $browsershot->save($savePath);

            // 如果代码执行到这里，表示 Browsershot 运行成功
            echo 'Browsershot ran successfully for ' . $this->ip_address . PHP_EOL;

           // file_put_contents(public_path('ccc.text'), json_encode($list));
        } catch (\Exception $e) {
            Log::error('处理 ' . $this->ip_address . ' 截图时出错: ' . $e->getMessage());
            // 如果捕获到异常，表示 Browsershot 发生了错误
            echo '处理 ' . $this->ip_address . ' 截图时发生错误: ' . $e->getMessage() . PHP_EOL;
            echo '堆栈跟踪: ' . $e->getTraceAsString() . PHP_EOL;
        }
    }
}
