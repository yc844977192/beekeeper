<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Arithmetic extends Model
{

    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('arithmetic_data');

        parent::__construct($attributes);

    }

}
