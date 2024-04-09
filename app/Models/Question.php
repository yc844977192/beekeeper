<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_question');

        parent::__construct($attributes);

    }
    
}