<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Plugging_supplier extends Model
{
    use DefaultDatetimeFormat;
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_plugging_supplier');

        parent::__construct($attributes);
    }

    public function get_node(){
        return $this->belongsTo(new TripartiteNode(), 'business_region',"ip_address");
    }

}
