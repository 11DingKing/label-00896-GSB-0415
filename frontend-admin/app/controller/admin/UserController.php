<?php
declare(strict_types=1);

namespace app\controller\admin;

use app\controller\BaseController;
use think\facade\View;
use app\model\AdminUser;
use app\model\AdminRole;
use app\validate\UserValidate;
use think\facade\Db;

/**
 * 后台用户管理控制器
 */
class UserController extends BaseController
{
    protected $middleware = ['admin', 'log'];

    /**
     * 用户列表
     */
    public function index()
    {
        $keyword = $this->request->get('keyword', '');
        $status = $this->request->get('status', '');

        $query = AdminUser::alias('u');

        if ($keyword) {
            $query->whereLike('u.username|u.nickname|u.email', "%{$keyword}%");
        }

        if ($status !== '') {
            $query->where('u.status', $status);
        }

        $list = $query->order('u.id', 'desc')
            ->paginate(15)
            ->each(function ($item) {
                $item->roles = $item->roles()->select();
            });

        View::assign([
            'list'    => $list,
            'keyword' => $keyword,
            'status'  => $status,
        ]);

        return View::fetch('user/index');
    }

    /**
     * 创建用户
     */
    public function create()
    {
        $roles = AdminRole::where('status', 1)->select();

        View::assign([
            'roles' => $roles,
        ]);

        return View::fetch('user/create');
    }

    /**
     * 保存用户
     */
    public function save()
    {
        $data = $this->request->post();

        try {
            $this->validate($data, UserValidate::class . '.create');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        Db::startTrans();
        try {
            $user = new AdminUser();
            $user->username = $data['username'];
            $user->password = $data['password'];
            $user->nickname = $data['nickname'] ?? '';
            $user->email = $data['email'] ?? '';
            $user->status = $data['status'] ?? 1;
            $user->save();

            // 分配角色
            if (!empty($data['role_ids'])) {
                $user->roles()->attach($data['role_ids']);
            }

            Db::commit();
            return $this->success('创建成功');
        } catch (\Exception $e) {
            Db::rollback();
            return $this->error('创建失败：' . $e->getMessage());
        }
    }

    /**
     * 编辑用户
     */
    public function edit(int $id)
    {
        $user = AdminUser::find($id);
        if (!$user) {
            return $this->error('用户不存在');
        }

        $roles = AdminRole::where('status', 1)->select();
        $userRoleIds = $user->roles()->column('id');

        View::assign([
            'user'        => $user,
            'roles'       => $roles,
            'userRoleIds' => $userRoleIds,
        ]);

        return View::fetch('user/edit');
    }

    /**
     * 更新用户
     */
    public function update(int $id)
    {
        $user = AdminUser::find($id);
        if (!$user) {
            return $this->error('用户不存在');
        }

        $data = $this->request->post();

        try {
            $this->validate($data, UserValidate::class . '.update');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        Db::startTrans();
        try {
            $user->nickname = $data['nickname'] ?? '';
            $user->email = $data['email'] ?? '';
            $user->status = $data['status'] ?? 1;

            // 修改密码
            if (!empty($data['password'])) {
                $user->password = $data['password'];
            }

            $user->save();

            // 更新角色
            $user->roles()->detach();
            if (!empty($data['role_ids'])) {
                $user->roles()->attach($data['role_ids']);
            }

            Db::commit();
            return $this->success('更新成功');
        } catch (\Exception $e) {
            Db::rollback();
            return $this->error('更新失败：' . $e->getMessage());
        }
    }

    /**
     * 删除用户
     */
    public function delete(int $id)
    {
        if ($id == 1) {
            return $this->error('不能删除超级管理员');
        }

        $user = AdminUser::find($id);
        if (!$user) {
            return $this->error('用户不存在');
        }

        Db::startTrans();
        try {
            // 删除角色关联
            $user->roles()->detach();
            // 删除用户
            $user->delete();

            Db::commit();
            return $this->success('删除成功');
        } catch (\Exception $e) {
            Db::rollback();
            return $this->error('删除失败：' . $e->getMessage());
        }
    }

    /**
     * 更改状态
     */
    public function status(int $id)
    {
        if ($id == 1) {
            return $this->error('不能禁用超级管理员');
        }

        $user = AdminUser::find($id);
        if (!$user) {
            return $this->error('用户不存在');
        }

        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();

        return $this->success('状态更新成功');
    }
}
