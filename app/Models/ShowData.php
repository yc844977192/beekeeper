<?php

namespace App\Models;


use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;

class ShowData extends Model
{
    use DefaultDatetimeFormat;
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_show_data');

        parent::__construct($attributes);

    }
    public  function  getGetTodaySumAttribute(){

//        $count=DB::table("admin_trend")
//            ->join("warehouse_link_data","admin_trend.warehouse_id","=","warehouse_link_data.warehouse_id")
//            ->where("admin_trend.time",date("Y-m-d"))
//            ->where("warehouse_link_data.data_id",$id)
//            ->count();
//        return $count;
        return 1;
    }
    
}
