<?php
declare(strict_types=1);

namespace app\validate;

use think\Validate;

/**
 * 登录验证器
 */
class LoginValidate extends Validate
{
    protected $rule = [
        'username' => 'require|length:2,50',
        'password' => 'require|length:6,32',
        'captcha'  => 'require|captcha',
    ];

    protected $message = [
        'username.require' => '请输入用户名',
        'username.length'  => '用户名长度为2-50个字符',
        'password.require' => '请输入密码',
        'password.length'  => '密码长度为6-32个字符',
        'captcha.require'  => '请输入验证码',
        'captcha.captcha'  => '验证码错误',
    ];
}
