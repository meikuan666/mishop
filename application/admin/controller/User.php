<?php

namespace app\admin\controller;

use think\Controller;
use think\captcha\Captcha;
use think\Request;
use think\facade\Session;


class User extends Controller
{

    public function login(Request $request)
    {
        if ($_POST) {
            $data['name'] = $request->name;
            $data['password'] = set_password($request->password);
            $captcha = $request->captcha;
//            dump($data);exit;
            if (!captcha_check($captcha)) {
                $this->error('验证码填写错误');
            }
            $user = \app\common\model\User::where($data)->find();
            if (!$user) {
                $this->error('用户名或密码填写错误');
            }
            Session::set('user', $user); // 登录成功后保存用户信息到session
            $this->success('登录成功', '/admin');
        } else {
            return view('login');

        }

    }

    //退出
    public function logout()
    {
        Session::clear();
        $this->success('您已成功退出', '/admin/User/login');
    }

    //验证码
    public function verify()
    {
        $config = [
            // 验证码字体大小
            'fontSize' => 30,
            // 验证码位数
            'length' => 3,

            'codeSet' => '12345689',
            // 关闭验证码杂点
            'useNoise' => false,
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }
}
