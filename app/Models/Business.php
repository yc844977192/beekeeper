<?php

namespace App\Models;


use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Business extends Model
{
    use DefaultDatetimeFormat;
    protected $fillable = [
        'customer_id',
        // 其他允许通过批量赋值填充的字段...
    ];
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_business');

        parent::__construct($attributes);
    }
    public function getCustomer()
    {
        return $this->belongsTo(Customer::class, 'customer_id','id');
    }
    //三级分类
    public function typeC_name()
    {
        return $this->belongsTo(new Type(), 'c_id');
    }
    //二级分类
    public function typeG_name()
    {
        return $this->belongsTo(new Type(), 'g_id');
    }
    //三级分类
    public function typeP_name()
    {
        return $this->belongsTo(new Type(), 'p_id');
    }
    //省
    public function province_name()
    {
        return $this->belongsTo(new Area(), 'province_id');
    }
    //市
    public function city_name()
    {
        return $this->belongsTo(new Area(), 'city_id');
    }
    //区
    public function district_name()
    {
        return $this->belongsTo(new Area(), 'district_id');
    }
    //私域
    public function get_private_sphere(){
        return $this->belongsTo(new Private_sphere(), 'business_private_sphere_id');
    }
    //商机来源
    public function get_business_source(){
        return $this->belongsTo(new Source(), 'source_id');
    }
    //承接方
    public function get_business_construction(){
        return $this->belongsTo(new Construction(), 'business_construction_id');
    }



}
