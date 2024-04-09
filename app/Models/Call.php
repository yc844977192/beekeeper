<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Call  extends Model
{
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_call_center');

        parent::__construct($attributes);

    }
    
}
