<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BData extends Model
{
    public function __construct(array $attributes = [])
    {
        $connection = "mysql_ztb";

        $this->setConnection($connection);

        $this->setTable('data_gov_buy');

        parent::__construct($attributes);

    }
    
}
