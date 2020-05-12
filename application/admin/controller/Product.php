<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Product extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->assign([
            '_mishop' => 'am-in',
            '_product' => 'am-active',
            '_recycle' => 'am-active',
            'brands' => Db::table('brand')->where('is_show', true)->select(),
            'categories' => \app\common\model\Category::with('children.photo')->where('parent_id', 0)->order('sort')->select(),
        ]);
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
//        $products = \app\common\model\Product::with(['categories', 'photos', 'brands'])->paginate(3);
//        return $time = explode(" ~ ", $request->created_at);

//        $products = \app\common\model\Product::with(['categories', 'photos', 'brands'])->where($where)->paginate(3);

        $products = \app\common\model\Product::all_products($request);
        $this->assign(compact('products'));
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return $this->fetch('add');

    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
//        return $request->param();
        //1. 往商品表插值
        $product = \app\common\model\Product::create($request->param());

        //2. 往商品相册表插值
        if ($request->imgs) {
            foreach ($request->imgs as $v) {
                $product->galleries()->save(['img' => $v]);
            }
        }

        //3. 往中间表插值
        $product->categories()->saveAll([$request->category_id]);
        $this->success('新增商品成功', '/admin/Product/index');
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $product = \app\common\model\Product::with(['categories', 'photos', 'brands'])->get($id);
//        return $products;
        $this->assign(compact('product'));
        return $this->fetch();

    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @param  int $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //1. 找到当前要编辑的数据
        $product = \app\common\model\Product::find($id);
        $product->update($request->param());  // 编辑商品

        //2. 往中间表插值
        $product->categories()->sync($request->category_id);  // 同步更新数据

        //3. 往商品相册表插值
        if ($request->imgs) {
            foreach ($request->imgs as $v) {
                $product->galleries()->save(['img' => $v]);
            }
        }

        $this->success('编辑商品成功', '/admin/Product/index');
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        // 软删除
        \app\common\model\Product::destroy($id);
        $this->success('回收成功', '/admin/Product/index');

    }


    //回收站首页
    public function recycle()
    {
        $products = \app\common\model\Product::onlyTrashed()->paginate(3);
//        return $product;
        $this->assign(compact('products'));

        return $this->fetch();
    }

    // 真实删除
    public function del($id)
    {
        \app\common\model\Product::destroy($id,true);
        $this->success('删除成功', '/admin/Product/recycle');

    }

    //回收站还原
    public function reduction($id)
    {
        $user = \app\common\model\Product::onlyTrashed()->find($id);
        $user->restore();
        $this->success('恢复成功', '/admin/Product/recycle');

    }

}
