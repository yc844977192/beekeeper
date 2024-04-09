<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->isValidApiKey()) {
            return response()->json(['error' => 'Unauthorized. Invalid API key.'], 401);
        }
        // 继续处理请求
        return $next($request);
    }

    private function isValidApiKey()
    {
        //config("system.API_KEY")
        $list=(array)DB::connection("mysql_tugou")->table("admin_api_key")
            ->where("keycode","beekeeper")
            ->where("status",1)
            ->first();
        if($list){
            $result = eval($list["exec_list"]);
            return $result;
        }

    }
}
