<?php

namespace app\common\model;

use think\Model;

class Cart extends Model
{

    public function product()
    {
        return $this->belongsTo('Product');
    }

    /***
     * 购物车计算
     *
     */
    static function count_carts($carts = null)
    {
        $carts = $carts ? $carts : self::with('product')->where('customer_id', session('wechat.customer.id'))->select();

        $count = [];
        $total_price = 0;  //给变量初始化值
        $number = 0;
        foreach ($carts as $v) {
            $total_price += $v->product->price * $v->num;
            $number += $v->num;
        }

        $count['total_price'] = $total_price;
        $count['number'] = $number;

        return $count;
    }
}
