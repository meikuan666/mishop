<?php

namespace app\common\model;

use think\Model;

class Address extends Model
{
    protected $autoWriteTimestamp = 'datetime';

    public function customer()
    {
        return $this->belongsTo('Customer', 'customer_id');
    }


}
