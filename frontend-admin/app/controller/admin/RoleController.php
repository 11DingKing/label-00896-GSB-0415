<?php
declare(strict_types=1);

namespace app\controller\admin;

use app\controller\BaseController;
use think\facade\View;
use app\model\AdminRole;
use app\model\AdminPermission;
use think\facade\Db;

/**
 * 后台角色管理控制器
 */
class RoleController extends BaseController
{
    protected $middleware = ['admin', 'log'];

    /**
     * 角色列表
     */
    public function index()
    {
        $list = AdminRole::order('id', 'asc')
            ->paginate(15);

        View::assign([
            'list' => $list,
        ]);

        return View::fetch('role/index');
    }

    /**
     * 创建角色
     */
    public function create()
    {
        return View::fetch('role/create');
    }

    /**
     * 保存角色
     */
    public function save()
    {
        $data = $this->request->post();

        if (empty($data['name'])) {
            return $this->error('请输入角色名称');
        }

        $role = new AdminRole();
        $role->name = $data['name'];
        $role->description = $data['description'] ?? '';
        $role->status = $data['status'] ?? 1;
        $role->save();

        return $this->success('创建成功');
    }

    /**
     * 编辑角色
     */
    public function edit(int $id)
    {
        $role = AdminRole::find($id);
        if (!$role) {
            return $this->error('角色不存在');
        }

        View::assign([
            'role' => $role,
        ]);

        return View::fetch('role/edit');
    }

    /**
     * 更新角色
     */
    public function update(int $id)
    {
        $role = AdminRole::find($id);
        if (!$role) {
            return $this->error('角色不存在');
        }

        $data = $this->request->post();

        if (empty($data['name'])) {
            return $this->error('请输入角色名称');
        }

        $role->name = $data['name'];
        $role->description = $data['description'] ?? '';
        $role->status = $data['status'] ?? 1;
        $role->save();

        return $this->success('更新成功');
    }

    /**
     * 删除角色
     */
    public function delete(int $id)
    {
        if ($id == 1) {
            return $this->error('不能删除超级管理员角色');
        }

        $role = AdminRole::find($id);
        if (!$role) {
            return $this->error('角色不存在');
        }

        // 检查是否有用户使用此角色
        $userCount = $role->users()->count();
        if ($userCount > 0) {
            return $this->error('该角色下还有用户，无法删除');
        }

        Db::startTrans();
        try {
            // 删除权限关联
            $role->permissions()->detach();
            // 删除角色
            $role->delete();

            Db::commit();
            return $this->success('删除成功');
        } catch (\Exception $e) {
            Db::rollback();
            return $this->error('删除失败：' . $e->getMessage());
        }
    }

    /**
     * 分配权限页面
     */
    public function permission(int $id)
    {
        $role = AdminRole::find($id);
        if (!$role) {
            return $this->error('角色不存在');
        }

        if ($this->request->isPost()) {
            $permissionIds = $this->request->post('permission_ids', []);

            $role->setPermissions($permissionIds);

            return $this->success('权限分配成功');
        }

        // 获取所有权限
        $permissions = AdminPermission::getTree();
        $rolePermissionIds = $role->getPermissionIds();

        View::assign([
            'role'              => $role,
            'permissions'       => $permissions,
            'rolePermissionIds' => $rolePermissionIds,
        ]);

        return View::fetch('role/permission');
    }
}
