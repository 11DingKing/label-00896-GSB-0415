<?php
declare(strict_types=1);

namespace app\validate;

use think\Validate;

/**
 * 留言验证器
 */
class MessageValidate extends Validate
{
    protected $rule = [
        'name'    => 'require|length:2,50',
        'content' => 'require|length:10,1000',
    ];

    protected $message = [
        'name.require'    => '请输入您的姓名',
        'name.length'     => '姓名长度为2-50个字符',
        'content.require' => '请输入留言内容',
        'content.length'  => '留言内容长度为10-1000个字符',
    ];

    /**
     * 验证场景
     */
    protected $scene = [
        'submit' => ['name', 'content'],
    ];
}
