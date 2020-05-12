<?php

namespace app\admin\validate;

use think\Validate;

class Callsify extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule =   [
        'name' => 'require|max:25',
        'sort' => 'require|number|between:1,99',
    ];

    protected $message  =   [
        'name.require' => '名称必须',
        'name.max' => '名称最多不能超过25个字符',


        'sort.require' => '排序必须',
        'sort.number' => '排序必须是数字',
        'sort.between' => '排序只能在1-99之间',


    ];
}
