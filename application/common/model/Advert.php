<?php

namespace app\common\model;

use think\Model;

class Advert extends Model
{
    protected $autoWriteTimestamp = 'datetime';

    public function photo()
    {
        return $this->belongsTo('Photo', 'photo_id');
    }

    public function categories()  //分类1对多
    {
        return $this->belongsTo('AdvertCategory','category_id');
    }

}
