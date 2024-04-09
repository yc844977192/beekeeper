<?php

namespace App\Models;


use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;


class TripartiteKeywords extends Model
{
    use DefaultDatetimeFormat;
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('baidu_key');

        parent::__construct($attributes);

    }

}
