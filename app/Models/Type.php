<?php

namespace App\Models;


use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Type extends Model
{
    use DefaultDatetimeFormat;
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_type');

        parent::__construct($attributes);
    }
    
}
