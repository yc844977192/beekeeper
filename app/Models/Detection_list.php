<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Detection_list extends Model
{
    public function __construct(array $attributes = [])
    {
        $connection = "mysql_detection";

        $this->setConnection($connection);

        $this->setTable('warehouse');

        parent::__construct($attributes);

    }
    
}
