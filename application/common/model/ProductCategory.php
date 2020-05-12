<?php

namespace app\common\model;

use think\Model;

class ProductCategory extends Model
{
    public function product()
    {
        return $this->belongsTo('Product');
    }
    public function photo()
    {
        return $this->belongsTo('Photo', 'product_id');
    }

}
