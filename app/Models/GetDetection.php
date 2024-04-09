<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class GetDetection extends Model
{
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('detection_list_where');

        parent::__construct($attributes);

    }
    
}
