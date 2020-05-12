<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Category extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->assign([
            '_mishop' => 'am-in',
            '_category' => 'am-active',
            'categories' => \app\common\model\Category::with('children.photo')->where('parent_id', 0)->order('sort')->select(),
        ]);
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
//        return 123;


        return view('index');

    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function add()
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
        Db::table('category')->insert($request->param());
        $this->success('新增成功', '/Admin/Category/index');
    }


    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $category = \app\common\model\Category::with('photo')->find($id);
        $this->assign(compact('category'));
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
//        return $request->param();
        Db::name('category')->where('id', $id)->update($request->param());
        $this->success('编辑成功', '/admin/Brand/index');
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $cate = Db::table('category')->where('parent_id', $id)->select();
        if ($cate) {
            $this->error('此分类下有二级分类，不能删除', '/admin/Brand/index');
        } else {
            Db::name('category')->delete($id);
            $this->success('删除成功', '/admin/Brand/index');

        }
    }
}
