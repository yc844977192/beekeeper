<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Phone  extends Model
{
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_phone_show');

        parent::__construct($attributes);

    }
    
}
