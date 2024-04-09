<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Seo extends Model
{
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_seo');

        parent::__construct($attributes);
    }
    
}
