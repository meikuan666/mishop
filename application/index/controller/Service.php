<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\common\model\Product;
use app\common\model\Address;
class Service extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->assign([
            '_service' => 'on'
        ]);
    }

    protected $middleware = ['Wechat'];

    public function index(Request $request)
    {
        $head = \app\common\model\Customer::where('id', session('wechat.customer.id'))->find();
//        return $head;
        $this->assign(compact('head'));
//        {$Think.session.user_id}
//        return
        return $this->fetch();

    }


    public function address()
    {
//        return 234;
        $addresses = \app\common\model\Address::where('customer_id', session('wechat.customer.id'))->select();
//        return $addresses;
        $this->assign(compact('addresses'));


        return $this->fetch();


    }
}
