<?php
declare(strict_types=1);

namespace app\validate;

use think\Validate;

/**
 * 产品验证器
 */
class ProductValidate extends Validate
{
    protected $rule = [
        'name'        => 'require|length:2,200',
        'category_id' => 'integer',
        'status'      => 'in:0,1',
    ];

    protected $message = [
        'name.require'        => '请输入产品名称',
        'name.length'         => '名称长度为2-200个字符',
        'category_id.integer' => '分类ID格式不正确',
        'status.in'           => '状态值不正确',
    ];
}
