<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Quote  extends Model
{
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('data_quote');

        parent::__construct($attributes);
    }
    
}
