<?php

namespace app\admin\controller;
use think\facade\Session;
use think\Controller;

class Index extends Common
{
    public function index()
    {
//        echo Session::get('user');
//        EXIT;
        return view('index');
    }

}
