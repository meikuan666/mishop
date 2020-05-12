<?php

namespace app\index\controller;

use app\common\model\Product;
use think\Controller;
use think\Request;

class Category extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->assign([
            '_category' => 'on'
        ]);
    }

    /***
     * 分类
     */
    public function index()
    {
        // 查出所有分类
        $categories = \app\common\model\Category::with('children.photo')->where('parent_id', 0)->order('sort')->select();
        $this->assign(compact('categories'));
        return $this->fetch();
    }

    /***
     * 分类对应的商品列表
     */
    public function product(Request $request)
    {
        $where = function ($query) use ($request) {
            // 按分类
            if ($request->has('category_id') and $request->category_id != '') {
                $result = \app\common\model\ProductCategory::where('category_id', $request->category_id)->select()->toArray();
                $product_ids = array_column($result, 'product_id');
                $query->whereIn('id', $product_ids);
            }
        };

        $products = Product::with('photo')->where($where)->select();
        $this->assign(compact('products'));
        return $this->fetch();
    }
}