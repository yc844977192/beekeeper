<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trend extends Model
{
    use HasFactory;
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_trend');

        parent::__construct($attributes);
    }
    public function getArithmetic(){
        return $this->belongsTo(new Arithmetic(), 'warehouse_id',"warehouse_id");
    }
}
