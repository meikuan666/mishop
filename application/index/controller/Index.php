<?php

namespace app\index\controller;

use app\common\model\Category;
use app\common\model\Photo;
use app\common\model\ProductCategory;
use think\Controller;
use think\Request;
use app\common\model\Product;

class Index extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->assign([
            '_home' => 'on'
        ]);
    }
    public function index(Request $request)
    {
//        return $request->text;
        //广告图
        $adverts = \app\common\model\AdvertCategory::with('photo')->order('sort')->select();

        //分类图
        $banners = \app\common\model\Category::with('photo')->where('parent_id', '>', 0)->order('sort')->select();
//        return $adverts;

        // 按名称搜索
        $where = function ($query) use ($request) {
            //按名称
            if ($request->text and $request->text != ' ') {
                $search = "%" . $request->text . "%";
                $query->where('name', 'like', $search);
            }

        };

        $products = Product::with('photos')->where($where)->where('is_onsale', true)->order('sort', 'desc')->limit(3)->select();
//        return $adverts;
        $total = Product::count();
//        return $total;
        $this->assign(compact('adverts', 'banners', 'products', 'total'));


        return $this->fetch();

    }

    public function show($id)
    {
//        return $id;
        $product = Product::with('galleries', 'photos')->find($id);
//        return $product;
        $this->assign(compact('product'));
        return $this->fetch();
    }

    /***
     * 加载更多数据
     */
    public function get_products(Request $request)
    {
        $page = $request->page;
        $num = 3;
        $products = Product::with('photos')->where('is_onsale', true)->limit($page, $num)->order('sort', 'desc')->select();
        return $products;
    }

    //分类
    public function category()
    {
//        return 13;

        $categories = \app\common\model\Category::with('children.photo')->where('parent_id', 0)->order('sort')->select();


//        return $categories;
        $this->assign(compact('categories'));


        return $this->fetch();
    }

//分类列表
    public function cl(Request $request,$id)
    {
//        return $id;                         多重关联写法 不必用foreach
        $products = ProductCategory::with('product.photos')->where('category_id',$id)->select();
//                return $products;


//        $products = Product::with('photos')->find($id);
//        return $products;

        $this->assign(compact('products'));
        return $this->fetch('list');

    }

    /***
     * 搜索页面
     */
    public function search(Request $request)
    {
        $products = Product::order('sort', 'desc')->select();

        $this->assign(compact('products'));

        return $this->fetch();
    }

}
