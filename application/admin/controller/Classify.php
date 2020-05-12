<?php

namespace app\admin\controller;

use app\common\model\AdvertCategory;
use think\Controller;
use think\Request;
use think\Db;

class Classify extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->assign([
            '_mishop' => 'am-in',
            '_classify' => 'am-active',
            'categories' => \app\common\model\AdvertCategory::with('photo')->order('sort')->select(),

        ]);
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
//        $categories = AdvertCategory::with('photo')->order('sort')->select();
//        return $categories;
//        $this->assign(compact('categories'));

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
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
//        $validate = new \app\admin\validate\Callsify();
//
//        if (!$validate->check($request->param())) {
//            $this->error($validate->getError());
//        } else {
            Db::table('advert_category')->insert($request->param());
            $data = ['status' => 1, 'msg' => '删除成功'];
            return $data;

//        }


    }



    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {

//        $brand = Db::table('advert_category')->with('photo')->get($id);
////        return $brand;
//        return view('edit', compact('brand'));

    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
//        return $request->param();
//        $validate = new \app\admin\validate\Callsify();
//
//        if (!$validate->check($request->param())) {
//            $this->error($validate->getError());
//        } else {
            Db::name('advert_category')->where('id', $id)->update($request->param());
            $data = array('status' => 1, 'msg' => '编辑成功');
            return $data;
//        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete_cate(Request $request,$id)
    {
        Db::name('advert_category')->where('id',$request->id)->delete();
        $data = ['status' => 1, 'msg' => '删除成功'];
        return $data;

    }
}
