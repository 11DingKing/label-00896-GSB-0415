<?php
declare(strict_types=1);

namespace app\validate;

use think\Validate;

/**
 * 新闻验证器
 */
class NewsValidate extends Validate
{
    protected $rule = [
        'title'       => 'require|length:2,200',
        'content'     => 'require',
        'status'      => 'in:0,1',
    ];

    protected $message = [
        'title.require'       => '请输入新闻标题',
        'title.length'        => '标题长度为2-200个字符',
        'content.require'     => '请输入新闻内容',
        'status.in'           => '状态值不正确',
    ];
}
