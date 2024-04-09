<?php
namespace App\Models;


use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Customer extends Model
{
    use DefaultDatetimeFormat;
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_customer');

        parent::__construct($attributes);
    }
    public function province_name()
    {
        return $this->belongsTo(new Area(), 'province_id');
    }
    public function city_name()
    {
        return $this->belongsTo(new Area(), 'city_id');
    }
    public function district_name()
    {
        return $this->belongsTo(new Area(), 'district_id');
    }





}
