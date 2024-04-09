<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;



/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Messages extends Model
{

    public function __construct(array $attributes = [])
    {
        //$connection = "mysql_aliyun";
        $connection = config('admin.database.connection') ?: config('database.default');
        $this->setConnection($connection);

        $this->setTable('admin_message');

        parent::__construct($attributes);
    }

}
