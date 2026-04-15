<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * 权限模型
 */
class AdminPermission extends Model
{
    protected $name = 'admin_permission';

    protected $pk = 'id';

    protected $autoWriteTimestamp = false;

    /**
     * 获取树形权限列表
     */
    public static function getTree(): array
    {
        $list = self::where('status', 1)
            ->order('sort', 'asc')
            ->select()
            ->toArray();

        return list_to_tree($list);
    }

    /**
     * 获取菜单列表
     */
    public static function getMenus(array $permissionIds = []): array
    {
        $query = self::where('status', 1)
            ->where('type', 1);

        if (!empty($permissionIds)) {
            $query->whereIn('id', $permissionIds);
        }

        $list = $query->order('sort', 'asc')
            ->select()
            ->toArray();

        return list_to_tree($list);
    }
}
