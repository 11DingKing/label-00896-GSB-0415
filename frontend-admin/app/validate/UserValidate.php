<?php
declare(strict_types=1);

namespace app\validate;

use think\Validate;

/**
 * 用户验证器
 */
class UserValidate extends Validate
{
    protected $rule = [
        'username' => 'require|length:2,50|unique:admin_user',
        'password' => 'require|length:6,32',
        'password_confirm' => 'requireWith:password|confirm:password',
        'nickname' => 'length:2,50',
        'email'    => 'email',
        'status'   => 'in:0,1',
    ];

    protected $message = [
        'username.require' => '请输入用户名',
        'username.length'  => '用户名长度为2-50个字符',
        'username.unique'  => '用户名已存在',
        'password.require' => '请输入密码',
        'password.length'  => '密码长度为6-32个字符',
        'password_confirm.requireWith' => '请确认密码',
        'password_confirm.confirm' => '两次密码输入不一致',
        'nickname.length'  => '昵称长度为2-50个字符',
        'email.email'      => '邮箱格式不正确',
        'status.in'        => '状态值不正确',
    ];

    protected $scene = [
        'create' => ['username', 'password', 'password_confirm', 'nickname', 'email', 'status'],
        'update' => ['nickname', 'email', 'status'],
        'password' => ['password', 'password_confirm'],
    ];
}
