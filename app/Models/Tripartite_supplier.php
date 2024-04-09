<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tripartite_supplier extends Model
{
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_tripartite_supplier');

        parent::__construct($attributes);
    }

    public function get_node(){
        return $this->belongsTo(new TripartiteNode(), 'business_region',"ip_address");
    }

}
