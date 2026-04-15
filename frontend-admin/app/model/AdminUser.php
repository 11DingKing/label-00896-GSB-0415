<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * 管理员用户模型
 */
class AdminUser extends Model
{
    protected $name = 'admin_user';

    protected $pk = 'id';

    // 自动时间戳
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    // 隐藏字段
    protected $hidden = ['password'];

    /**
     * 密码加密
     */
    public function setPasswordAttr(string $value): string
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    /**
     * 验证密码
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    /**
     * 关联角色
     */
    public function roles()
    {
        return $this->belongsToMany(AdminRole::class, 'admin_role_user', 'role_id', 'user_id');
    }

    /**
     * 获取用户权限
     */
    public function getPermissions(): array
    {
        // 先获取用户的角色ID
        $roleIds = \think\facade\Db::name('admin_role_user')
            ->where('user_id', $this->id)
            ->column('role_id');

        if (empty($roleIds)) {
            return [];
        }

        // 获取角色对应的权限
        return \think\facade\Db::name('admin_permission')
            ->alias('p')
            ->join('admin_role_permission rp', 'p.id = rp.permission_id')
            ->whereIn('rp.role_id', $roleIds)
            ->where('p.status', 1)
            ->order('p.sort', 'asc')
            ->field('p.*')
            ->select()
            ->toArray();
    }

    /**
     * 更新登录信息
     */
    public function updateLoginInfo(): void
    {
        $this->login_count = $this->login_count + 1;
        $this->last_login_time = time();
        $this->last_login_ip = get_client_ip();
        $this->save();
    }
}
