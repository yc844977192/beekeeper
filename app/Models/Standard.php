<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Standard  extends Model
{
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('webfree_standard');

        parent::__construct($attributes);

    }
    
}
