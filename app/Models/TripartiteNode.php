<?php

namespace App\Models;


use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;


class TripartiteNode extends Model
{

    use DefaultDatetimeFormat;
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('tripartite_node');

        parent::__construct($attributes);

    }

}
