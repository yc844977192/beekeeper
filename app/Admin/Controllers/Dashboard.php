<?php

namespace App\Admin\Controllers;

use Illuminate\Support\Facades\DB;

class Dashboard
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function title()
    {
        return view('Admin/dashboard.title');
    }

    /**
     * 供应商
     */
    public static function environment()
    {
        $supplierCount['sum'] = DB::table('admin_supplier')->count();

        $supplierCount['today_sum'] = DB::table('admin_supplier')->whereDate("created_at", date("Y-m-d"))->count();
        //报价人
        $offererCount['sum'] = DB::table('admin_offerer')->count();

        $offererCount['today_sum'] = DB::table('admin_offerer')->whereDate("created_at", date("Y-m-d"))->count();
        return view('Admin/dashboard/environment')->with('supplierCount', $supplierCount)->with('offererCount', $offererCount)->with("password_is_modify", 0);
    }

    /**
     * 商品
     */
    public static function extensions()
    {

        $commodityCount['sum'] = DB::table('admin_commodity')->count();

        $commodityCount['today_sum'] = DB::table('admin_commodity')->whereDate("created_at", date("Y-m-d"))->count();
        //报价单
        $quotationCount['sum'] = DB::table('admin_quotation')->count();

        $quotationCount['today_sum'] = DB::table('admin_quotation')->whereDate("created_at", date("Y-m-d"))->count();

        return view('Admin/dashboard/extensions')->with('commodityCount', $commodityCount)->with('quotationCount', $quotationCount);
    }

    /**
     * 商机
     */
    public static function dependencies()
    {
        $businessCount['sum'] = DB::table('admin_business')->count();

        $businessCount['today_sum'] = DB::table('admin_business')->whereDate("created_at", date("Y-m-d"))->count();
        //0-待处理，1-已分配待跟进，3-已跟进待确定,4-失败，5-暂缓，6-成交
        $businessCount['d_sum'] = DB::table('admin_business')->where("business_states", 0)->count();
        $businessCount['g_sum'] = DB::table('admin_business')->where("business_states", 1)->count();
        $businessCount['q_sum'] = DB::table('admin_business')->where("business_states", 3)->count();
        $businessCount['s_sum'] = DB::table('admin_business')->where("business_states", 4)->count();
        $businessCount['z_sum'] = DB::table('admin_business')->where("business_states", 5)->count();
        $businessCount['c_sum'] = DB::table('admin_business')->where("business_states", 6)->count();
        //客户
        $customerCount['sum'] = DB::table('admin_customer')->count();

        $customerCount['today_sum'] = DB::table('admin_customer')->whereDate("created_at", date("Y-m-d"))->count();

        return view('Admin/dashboard/dependencies')->with('businessCount', $businessCount)->with('customerCount', $customerCount);
    }
}
