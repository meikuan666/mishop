<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;
use think\Request;
class Product extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    public function categories()  //分类多对多
    {
        return $this->belongsToMany('Category','product_category','id','product_id');
    }

    public function galleries() //相册一对多
    {
        return $this->hasMany('Gallery', 'product_id');
    }

    public function photos()  //缩略图反向一对多
    {
        return $this->belongsTo('Photo', 'photo_id');
    }

    public function brands()  //品牌反向一对多
    {
        return $this->belongsTo('Brand', 'brand_id');
    }

    static function all_products(Request $request)
    {
        $where = function ($query) use ($request) {
            //按名称
            if ($request->keyword and $request->keyword != '') {
                $search = "%" . $request->keyword . "%";
                $query->where('name', 'like', $search);
            }
            // 按分类
            if ($request->has('category_id') and $request->category_id != '') {
                $result = \app\common\model\ProductCategory::where('category_id', $request->category_id)->select()->toArray();
                $product_ids = array_column($result, 'product_id');
                $query->whereIn('id', $product_ids);
            }

            // 按日期
            if ($request->has('created_at') and $request->created_at != '') {
                $time = explode(" ~ ", $request->created_at);

                $start = $time[0] . ' 00:00:00';
                $end = $time[1] . ' 23:59:59';
                $query->whereTime('created_at', 'between', [$start, $end]);
            }
        };

        $products = self::with(['categories', 'photos', 'brands'])->where($where)->paginate(3);

        return $products;
    }
}
