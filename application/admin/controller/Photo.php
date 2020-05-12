<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;

class Photo extends Controller
{
    public function upload()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        $info = $file->move('./uploads');
        if ($info) {
//             成功上传后 获取上传信息,上传到本地
            $result['image_url'] = '/uploads/'.$info->getSaveName();
            $photo = \app\common\model\Photo::create(['image'=>$result['image_url']]);
            $result['photo_id'] = $photo['id'];
            return $result;
            //上传七牛在这里
//            $filePath = getcwd() . '/uploads/' . $info->getSaveName();
////            return $filePath;
//            upload_qiniu($filePath);  //调用七牛函数在这里
//            $result['image_url'] = 'http://q9dj3kiki.bkt.clouddn.com/' . $info->getFilename();
////            return $result;
//            $photo = \app\common\model\Photo::create(['image' => $result['image_url']]);
////            return $photo;
//
//            $result['photo_id'] = $photo->id;
//
//            return $result;

        } else {
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }

}
