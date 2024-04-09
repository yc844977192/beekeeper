<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Rubbings extends Model
{
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('baidu_query_count');

        parent::__construct($attributes);

    }




}
