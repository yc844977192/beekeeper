<?php
namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Supplier extends Model
{
    use DefaultDatetimeFormat;

    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_supplier');

        parent::__construct($attributes);
    }

    /**
     * @return BelongsToMany
     */
    public function get_commodity_commodity_id(){
        return $this->belongsToMany(Commodity::class,'admin_commodity_supplier','supplier_id','commodity_id');
    }
    public function get_g_id(){
        return $this->belongsToMany(Commodity::class,'admin_commodity_supplier','supplier_id','commodity_id');
    }
    public function get_c_id(){
        return $this->belongsToMany(Commodity::class,'admin_commodity_supplier','supplier_id','commodity_id');
    }
    public function get_offerer_id(){
        return $this->belongsToMany(Offerer::class,'admin_supplier_offerer','supplier_id','offerer_id');

    }
    // 在 Supplier 模型中定义与 detection_snapshot 表的一对多关系
    public function detectionSnapshots()
    {
        return $this->hasMany(Detection_snapshot::class, 'supplier_id', 'id');
    }
}
