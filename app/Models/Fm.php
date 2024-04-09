<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Fm extends Model
{
    public function __construct(array $attributes = [])
    {
        $connection = "mysql_aliyun";

        $this->setConnection($connection);

        $this->setTable('data_project_dense');

        parent::__construct($attributes);

    }
    
}
