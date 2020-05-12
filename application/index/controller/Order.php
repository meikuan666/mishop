<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\common\model\Cart;
use think\Db;

class Order extends Controller
{
    protected $middleware = ['Wechat'];


    public function checkout(Request $request)
    {
        $carts = Cart::with('product.photos')->where('customer_id', session('wechat.customer.id'))->select();
        $count = Cart::count_carts($carts);

        $address = \app\common\model\Address::find(session('wechat.customer.address_id'));
        if ($address) {

            $xing = substr($address->tel, 3, 4);  //从第三位开始 获取后面四位
            $address['tel'] = str_replace($xing, '****', $address->tel); //用*替换
        }

        $this->assign(compact('carts', 'count', 'address'));
        return $this->fetch();

    }

    /***
     * 去付款
     */
    public function store()
    {
        // 计算总价格
        $count = \app\common\model\Cart::count_carts();
        $total_price = $count['total_price'];

        Db::startTrans();

        try {
            // 1. 往订单表
            $order = \app\common\model\Order::create([
                'customer_id' => session('wechat.customer.id'),
                'status' => 1,
                'total_price' => $total_price,
                'out_trade_no' => \app\common\model\Order::make_orderNo(),
            ]);

            // 2. 订单地址表
            $address = \app\common\model\Address::find(session('wechat.customer.address_id'));
            $order->address()->save([
                'province' => $address['province'],
                'city' => $address['city'],
                'area' => $address['area'],
                'detail' => $address['detail'],
                'name' => $address['name'],
                'tel' => $address['tel'],
            ]);

            // 3. 订单商品表
            $carts = \app\common\model\Cart::with('product.photos')->where('customer_id', session('wechat.customer.id'))->select();
            foreach ($carts as $cart) {

                // 判断商品库存是否足够
                if ($cart->product->stock != '-1' and $cart->product->stock - $cart->num < 0) {
                    throw new \Exception('商品' . $cart->product->name . ',目前仅剩下' . $cart->product->stock . "件.请慎重下单！");
                }

                // 削减商品库存
                if ($cart->product->stock != '-1') {
                    $cart->product->setDec('stock', $cart->num);
                }

                $order->orderProducts()->save([
                    'product_id' => $cart->product_id,
                    'num' => $cart->num
                ]);
            }
            // 提交事务
            Db::commit();
            return json(['status' => 1, 'order_id' => $order->id]);
        } catch (\Exception $e) {  //抛出异常 Exception $e
            // 事务回滚
            Db::rollback();
            return json(['status' => 0, 'info' => $e->getMessage()]);
        }
    }

    //订单成功 支付页面
    public function pay($id)
    {
//        return $id;
        $order = \app\common\model\Order::with('address')->find($id);
//                return $order;
        $this->assign(compact('order'));
        return $this->fetch();
    }

}
