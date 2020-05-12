<?php

namespace app\index\controller;

use app\common\model\Customer;
use think\Controller;
use think\Request;
use app\common\model\Product;
use think\Session;
use think\response\Json;
class Cart extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->assign([
            '_cart' => 'on'
        ]);
    }
    protected $middleware = ['Wechat'];

    public function store(Request $request)
    {
        //        return session('wechat.customer.id');

        //判断是否同一用户同一商品
        $cart = \app\common\model\Cart::where('customer_id', session('wechat.customer.id'))->where('product_id', $request->id)->find();
        if ($cart) {
            \app\common\model\Cart::where('id', $cart->id)->setInc('num');
            return;
        }
        \app\common\model\Cart::create([

            'product_id' => $request->id,
            'customer_id' => session('wechat.customer.id'),
        ]);

    }

    public function index(Request $request)
    {
//        return $request->param();
        $carts = \app\common\model\Cart::with('product.photos')->where('customer_id', session('wechat.customer.id'))->select();
//        return $carts;
        $count = \app\common\model\Cart::count_carts($carts);

        $this->assign(compact('carts', 'count'));


        return $this->fetch();

    }

    //购物车加减 id 和类型
    public function change_num(Request $request)
    {
        if ($request->type == 'add') {
            \app\common\model\Cart::where('id', $request->id)->setInc('num');
        } else {
            \app\common\model\Cart::where('id', $request->id)->setDec('num');
        }

        return \app\common\model\Cart::count_carts();
    }

    //删除
    public function delete(Request $request,$id)
    {
//        return $request->id;
//        \app\common\model\Cart::delete($id);
        \Db::name('cart')->where('id',$request->id)->delete();

        return \app\common\model\Cart::count_carts();

    }


}
