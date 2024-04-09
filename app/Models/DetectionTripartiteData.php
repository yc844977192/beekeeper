<?php

namespace App\Models;


use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;


class DetectionTripartiteData extends Model
{
    use DefaultDatetimeFormat;
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('baidu_words_data_detection');

        parent::__construct($attributes);
    }
    public function get_node(){
        return $this->belongsTo(new DetectionTripartiteNode(), 'business_region',"ip_address");
    }

}
