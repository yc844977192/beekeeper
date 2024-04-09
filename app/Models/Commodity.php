<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
    class Commodity extends Model
{
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_commodity');

        parent::__construct($attributes);
    }
        public function get_commodity_supplier_id(){
            return $this->belongsToMany(Supplier::class,'admin_commodity_supplier','commodity_id','supplier_id');
        }
}
