<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class StaticWeb extends Model
{
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('static_web');

        parent::__construct($attributes);

    }
    
}
