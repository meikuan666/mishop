<?php

namespace app\common\model;

use think\Model;

class Category extends Model
{
    public function children()
    {
        return $this->hasMany('Category', 'parent_id')->order('sort');
    }

    public function photo()
    {
        return $this->belongsTo('Photo', 'photo_id');
    }

    public function product()
    {
        return $this->belongsToMany('Product','product_category','id','category_id');
//        ('关联模型','中间表','外键','关联键');
    }
}
