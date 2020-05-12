<?php

namespace app\common\model;

use think\Model;

class Order extends Model
{
    protected $autoWriteTimestamp = 'datetime';

    // 每个订单只有一个订单地址
    public function address()
    {
        return $this->hasOne('OrderAddress', 'order_id');
    }

    // 每个订单有多个商品
    public function orderProducts()
    {
        return $this->hasMany('OrderProduct', 'order_id');
    }

    /***
     * 生成订单号
     * @return bool|string
     * @throws \Exception
     */
    public static function make_orderNo()
    {
        // 订单流水号前缀
        $prefix = date('YmdHis');
        for ($i = 0; $i < 10; $i++) {
            // 随机生成 6 位的数字
            $no = $prefix . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            return $no;
        }
        return false;
    }
}
