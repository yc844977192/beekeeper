<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Offerer extends Model
{
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_offerer');

        parent::__construct($attributes);
    }

    public function get_supplier_offerer_id(){
        return $this->belongsToMany(Supplier::class,'admin_supplier_offerer','offerer_id','supplier_id');
    }
    public function get_t_id(){
        return $this->belongsToMany(Commodity::class,'admin_commodity_offerer','offerer_id','commodity_id');
    }
    public function get_g_id(){
        return $this->belongsToMany(Commodity::class,'admin_commodity_offerer','offerer_id','commodity_id');
    }
    public function get_c_id(){
        return $this->belongsToMany(Commodity::class,'admin_commodity_offerer','offerer_id','commodity_id');
    }
}
