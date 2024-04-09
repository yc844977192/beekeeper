<?php

namespace App\Models;


use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;

class United extends Model
{
    use DefaultDatetimeFormat;
    public function __construct(array $attributes = [])
    {
        $connection = "mysql_aliyun";

        $this->setConnection($connection);

        $this->setTable('data_united');

        parent::__construct($attributes);

    }
    
}
