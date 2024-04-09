<?php

namespace App\Models;


use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;



/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Webchat extends Model
{
    use DefaultDatetimeFormat;
    public function __construct(array $attributes = [])
    {
        $connection = "mysql_aliyun";

        $this->setConnection($connection);

        $this->setTable('chat_record');

        parent::__construct($attributes);
    }

}
