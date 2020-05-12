<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

//密码加密
function set_password($password)
{
    return substr(md5($password), 6, 10);
}

//七牛
function upload_qiniu($filePath)
{
    // 需要填写你的 Access Key 和 Secret Key
    $accessKey = "rArqVcjXbjZ6obdwWuYE35lmabnKviHZ-xzP627c";
    $secretKey = "4OVu0zLsyth6_B9g5-KbuLtyrEy1tyxpe-hYYiZ_";
    $bucket = "200426";


    // 构建鉴权对象
    $auth = new Auth($accessKey, $secretKey);
// 生成上传 Token
    $token = $auth->uploadToken($bucket);
// 上传到七牛后保存的文件名
    $key = basename($filePath);
// 初始化 UploadManager 对象并进行文件的上传。
    $uploadMgr = new UploadManager();
// 调用 UploadManager 的 putFile 方法进行文件的上传。
    $uploadMgr->putFile($token, $key, $filePath);

//    unlink($filePath); //删除本地图片在这里
}

/**
 * 截取, 并加上...
 * @param $string
 * @param $size
 * @param bool $dot 是否加上..., 默认true
 * @return string
 */
function sub($string, $size = 5, $dot = true)
{
    $string = strip_tags(trim($string));
    if (strlen($string) > $size) {
        $string = mb_substr($string, 0, $size);
        $string .= $dot ? '...' : '';
        return $string;
    }

    return $string;
}

/**
 * 微信菜单, 删除空数组
 * @param $buttons
 * break 用来跳出目前执行的循环，并不再继续执行循环了。
 * continue 立即停止目前执行循环，并回到循环的条件判断处，继续下一个循环。
 */
function wechat_menus($request_buttons)
{
    $buttons = [];
    foreach ($request_buttons as $key => $value) {
        if ($value['name'] == "") {
            continue;//终止本次循环而进入到下一次循环中，
        }

        $buttons["$key"] = wechat_key_url($value);

        foreach ($value["sub_button"] as $k => $v) {
            if ($v['name'] == "") {
                continue;
            }
            $buttons["$key"]["sub_button"][] = wechat_key_url($v);
        }
    }
    return $buttons;
}

/**
 * 根据类型,返回url或者key
 * @param $value
 * @return array
 */
function wechat_key_url($value)
{
    $result = [];
    $result['type'] = $value['type'];
    $result['name'] = $value['name'];
    if ($value['type'] == "click") {
        $result['key'] = $value['value'];
    } else {
        $result['url'] = $value['value'];
    }
    return $result;
}
