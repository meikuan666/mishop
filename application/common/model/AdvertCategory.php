<?php

namespace app\common\model;

use think\Model;

class AdvertCategory extends Model
{

    public function photo()
    {
        return $this->belongsTo('Photo', 'photo_id');
    }
}
