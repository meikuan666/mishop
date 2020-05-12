<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Session;

class Common extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->check_login();
    }

    private function check_login()
    {
        if (!Session::has('user')) {
            $this->error('您还没有登录，请先登录后再访问', '/admin/User/login');
        }
    }


}
