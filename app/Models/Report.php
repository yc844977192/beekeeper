<?php

namespace App\Models;


use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use DefaultDatetimeFormat;
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_report');

        parent::__construct($attributes);

    }
    
}
