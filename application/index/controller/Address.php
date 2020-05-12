<?php

namespace app\index\controller;

use app\common\model\Customer;
use think\Controller;
use think\Request;
use app\common\model\Product;
use think\facade\Session;

class Address extends Controller
{
    protected $middleware = ['Wechat'];


    public function index(Request $request)
    {
        $addresses = \app\common\model\Address::where('customer_id', session('wechat.customer.id'))->select();

        $this->assign(compact( 'addresses'));

        return $this->fetch();

    }

    public function create()
    {
        return $this->fetch();



    }

    public function store(Request $request)
    {
        //新增地址 省份字符串转数组
//        return $request->param();
        $pca = explode(' ', $request->pca);

        \app\common\model\Address::create([
            'customer_id' => session('wechat.customer.id'),
            'province' => $pca[0],
            'city' => $pca[1],
            'area' => $pca[2],
            'detail' => $request->detail,
            'tel' => $request->tel,
            'name' => $request->name,
        ]);

    }

    public function edit($id)
    {
        //调用模型查这个id的相关地址信息
        $address = \app\common\model\Address::find($id);
//        return $address;
        $this->assign(compact( 'address'));

        return $this->fetch();

    }

    public function update(Request $request, $id)
    {
//        return 123;
        //接收id和数据执行更新
//        return $request->param();
        $address = \app\common\model\Address::find($id);
//        return $address;

        $pca = explode(' ', $request->pca);


        $address->save([
            'customer_id' => session('wechat.customer.id'),
            'province' => $pca[0],
            'city' => $pca[1],
            'area' => $pca[2],
            'detail' => $request->detail,
            'tel' => $request->tel,
            'name' => $request->name,
        ]);



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

//        return $id;
        // 调用模型删除
        \app\common\model\Address::destroy($id);


    }

    /***
     * 修改默认地址
     *
     */
    public function change_address(Request $request)
    {
        Customer::where('id', session('wechat.customer.id'))->update(['address_id' => $request->address_id]);

        //重新设置session
        $customer = Session::get('wechat.customer');//取出当前用户的session
        $customer['address_id'] = $request->address_id;     //给session设置新的address_id
           //再把新的session存起来
        Session::set('wechat.customer', $customer);


    }


}
