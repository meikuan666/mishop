<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\facade\Cache;
class Vx extends Controller
{
    private $menu;

    function __construct()
    {
        parent::__construct();
        $this->assign([
            '_wechat' => 'am-in',
            '_vx' => 'am-active',

        ]);

        $app = app('wechat.official_account');
        $this->menu = $app->menu;
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
//        $menus = $this->menu->list();
//        return $menus;
        try {
            $buttons = Cache::remember('wechat.config_menus',function(){
                $menus = $this->menu->list();
                return $menus['menu']['button'];
            });
        } catch (HttpException $e) {

            $buttons = [];
        }

        $this->assign(compact('buttons'));
        return $this->fetch();
    }





    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request)
    {
        $button = wechat_menus($request->buttons);
        $this->menu->create($button);
        Cache::rm('wechat.config_menus'); //清除缓存
        $this->success('编辑成功','/admin/Vx/index');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete()
    {
        $this->menu->delete();
        Cache::rm('wechat.config_menus'); //清除缓存
        $this->success('删除成功','/admin/Vx/index');
    }
}
