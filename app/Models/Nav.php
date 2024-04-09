<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Nav extends Model
{
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_nav');

        parent::__construct($attributes);
    }
    
}
