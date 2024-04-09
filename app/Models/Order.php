<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('admin_order');

        parent::__construct($attributes);
    }

    public function getBusiness()
    {
        return $this->belongsTo(Business::class, 'order_business_id','id');
    }
}
