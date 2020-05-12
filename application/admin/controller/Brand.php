<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Brand extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->assign([
            '_mishop' => 'am-in',
            '_brand' => 'am-active'
        ]);
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
//        return 132;
        $where = function ($query) use ($request) {
            //按名称
            if ($request->q and $request->q != ' ') {
                $search = "%" . $request->q . "%";
                $query->where('name', 'like', $search);
            }

        };
        $brands = Db::table('brand')->where($where)->paginate(3);
//        return $brands;
        return view('index', compact('brands'));
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return view('add');
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
        $validate = new \app\admin\validate\Brand;

        if (!$validate->check($request->param())) {
            $this->error($validate->getError());
        } else {
            Db::table('brand')->insert($request->param());
            $this->success('新增成功', '/admin/Brand/index');
        }
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $brand = Db::table('brand')->get($id);
//        return $brand;
        return view('edit', compact('brand'));

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
//        return $request->param();
        $validate = new \app\admin\validate\Brand;

        if (!$validate->check($request->param())) {
            $this->error($validate->getError());
        } else {
            Db::name('brand')->where('id', $id)->update($request->param());
            $this->success('编辑成功', '/admin/Brand/index');
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        Db::name('brand')->delete($id);
        $this->success('删除成功', '/admin/Brand/index');

    }

    //多选删除
    public function del_all(Request $request)
    {

        $checked_id = $request->checked_id;
//                        dump($checked_id);exit;

        // 数组删除
        foreach ($checked_id as $v) {
//
            Db::name('brand')->where('id',$v)->delete();


        }
        $data = ['status' => 1, 'msg' => '删除成功'];
        return $data;

    }

    //勾叉
    public function change_attr(Request $request)
    {
        $is_show = $request->attr;
        Db::name('brand')->where('id', $request->id)->setField('is_show',!$is_show);
    }
}
