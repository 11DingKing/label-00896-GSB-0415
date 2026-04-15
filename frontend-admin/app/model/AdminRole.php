<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * 角色模型
 */
class AdminRole extends Model
{
    protected $name = 'admin_role';

    protected $pk = 'id';

    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    /**
     * 关联用户
     */
    public function users()
    {
        return $this->belongsToMany(AdminUser::class, 'admin_role_user', 'user_id', 'role_id');
    }

    /**
     * 关联权限
     */
    public function permissions()
    {
        return $this->belongsToMany(AdminPermission::class, 'admin_role_permission', 'permission_id', 'role_id');
    }

    /**
     * 获取权限ID列表
     */
    public function getPermissionIds(): array
    {
        return $this->permissions()->column('id');
    }

    /**
     * 设置权限
     */
    public function setPermissions(array $permissionIds): void
    {
        // 先删除原有权限
        $this->permissions()->detach();
        // 添加新权限
        if (!empty($permissionIds)) {
            $this->permissions()->attach($permissionIds);
        }
    }
}
