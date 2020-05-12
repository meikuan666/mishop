<?php

namespace app\http\middleware;

use app\common\model\Customer;
use think\facade\Session;

class Wechat
{
    public function handle($request, \Closure $next, $name)
    {
        if (!Session::get('wechat.customer')) {
            $customer = Customer::find(63);
            Session::set('wechat.customer', $customer);
        }

        return $next($request);
    }
}
