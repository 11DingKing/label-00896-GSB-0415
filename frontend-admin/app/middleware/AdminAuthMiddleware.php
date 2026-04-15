<?php
declare(strict_types=1);

namespace app\middleware;

use Closure;
use think\Request;
use think\Response;
use think\facade\Session;
use think\facade\View;

/**
 * 后台认证中间件
 */
class AdminAuthMiddleware
{
    /**
     * 无需登录的路由
     */
    protected array $except = [
        'admin/auth/login',
        'admin/auth/captcha',
    ];

    /**
     * 处理请求
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = strtolower($request->pathinfo());

        // 检查是否在白名单中
        foreach ($this->except as $pattern) {
            if (strpos($path, $pattern) === 0) {
                return $next($request);
            }
        }

        // 检查登录状态
        $adminUser = Session::get('admin_user');

        // 判断是否为API请求 (isAjax 或 路径包含 /get/)
        $isApiRequest = $request->isAjax() || strpos($path, '/get/') !== false;

        if (empty($adminUser)) {
            if ($isApiRequest) {
                return json(['code' => 401, 'msg' => '请先登录']);
            }
            return redirect((string)url('/admin/auth/login'));
        }

        // 检查用户状态
        if ($adminUser['status'] != 1) {
            Session::delete('admin_user');
            if ($isApiRequest) {
                return json(['code' => 403, 'msg' => '账号已被禁用']);
            }
            return redirect((string)url('/admin/auth/login'));
        }

        // 设置视图变量
        View::assign('admin_user', $adminUser);

        // 权限检查
        if (!$this->checkPermission($adminUser, $path)) {
            if ($isApiRequest) {
                return json(['code' => 403, 'msg' => '没有操作权限']);
            }
            return response('没有操作权限', 403);
        }

        // 超级管理员保护：普通管理员无法对超级管理员执行写操作
        if (!$this->checkSuperAdminProtection($adminUser, $request, $path)) {
            if ($isApiRequest) {
                return json(['code' => 403, 'msg' => '没有操作权限：不能操作超级管理员']);
            }
            return response('没有操作权限：不能操作超级管理员', 403);
        }

        return $next($request);
    }

    /**
     * 检查权限
     */
    protected function checkPermission(array $user, string $path): bool
    {
        // 超级管理员拥有所有权限
        if ($user['id'] == 1) {
            return true;
        }

        // 获取用户权限
        $permissions = Session::get('admin_permissions', []);
        if (empty($permissions)) {
            return false;
        }

        // 检查路径权限
        foreach ($permissions as $permission) {
            if (!empty($permission['path']) && strpos('/' . $path, $permission['path']) === 0) {
                return true;
            }
        }

        // 默认允许访问仪表盘
        if (strpos($path, 'admin/dashboard') === 0) {
            return true;
        }

        return false;
    }

    /**
     * 超级管理员保护：普通管理员无法对超级管理员执行写操作
     */
    protected function checkSuperAdminProtection(array $currentUser, Request $request, string $path): bool
    {
        // 当前用户是超级管理员，允许所有操作
        if ($currentUser['id'] == 1) {
            return true;
        }

        // 检查用户管理相关的写操作
        if (strpos($path, 'admin/user/') !== false) {
            // 获取目标用户ID (路径参数或POST参数)
            $targetUserId = $this->getTargetUserId($request, $path);
            
            // 是写操作且目标用户是超级管理员
            if ($targetUserId == 1 && $this->isUserWriteOperation($path, $request)) {
                return false;
            }

            // 检查是否在分配超级管理员角色
            if ($this->isAssigningSuperAdminRole($request)) {
                return false;
            }
        }

        // 检查角色管理相关的写操作
        if (strpos($path, 'admin/role/') !== false) {
            // 获取目标角色ID
            $targetRoleId = $this->getTargetRoleId($request, $path);
            
            // 是写操作且目标角色是超级管理员角色 (ID=1)
            if ($targetRoleId == 1 && $this->isRoleWriteOperation($path, $request)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 获取目标用户ID
     */
    protected function getTargetUserId(Request $request, string $path): ?int
    {
        // 从路径参数获取 (如 /admin/user/delete/1)
        $pathParts = explode('/', $path);
        foreach ($pathParts as $key => $part) {
            if (in_array($part, ['delete', 'edit', 'update', 'status']) && isset($pathParts[$key + 1])) {
                $userId = intval($pathParts[$key + 1]);
                if ($userId > 0) {
                    return $userId;
                }
            }
        }

        // 从POST参数获取 id
        $postId = $request->post('id');
        if ($postId) {
            return intval($postId);
        }

        // 从GET参数获取 id
        $getId = $request->get('id');
        if ($getId) {
            return intval($getId);
        }

        return null;
    }

    /**
     * 获取目标角色ID
     */
    protected function getTargetRoleId(Request $request, string $path): ?int
    {
        // 从路径参数获取
        $pathParts = explode('/', $path);
        foreach ($pathParts as $key => $part) {
            if (in_array($part, ['delete', 'edit', 'update', 'permission']) && isset($pathParts[$key + 1])) {
                $roleId = intval($pathParts[$key + 1]);
                if ($roleId > 0) {
                    return $roleId;
                }
            }
        }

        // 从POST参数获取
        $postId = $request->post('id');
        if ($postId) {
            return intval($postId);
        }

        // 从GET参数获取
        $getId = $request->get('id');
        if ($getId) {
            return intval($getId);
        }

        return null;
    }

    /**
     * 判断是否为用户写操作
     */
    protected function isUserWriteOperation(string $path, Request $request): bool
    {
        $writeActions = ['delete', 'update', 'status', 'save'];
        foreach ($writeActions as $action) {
            if (strpos($path, 'admin/user/' . $action) === 0 || strpos($path, 'admin/user/' . $action . '/') !== false) {
                return true;
            }
        }
        // POST请求到edit也视为写操作
        if ((strpos($path, 'admin/user/edit') === 0 || strpos($path, 'admin/user/edit/') !== false) && $request->isPost()) {
            return true;
        }
        return false;
    }

    /**
     * 判断是否为角色写操作
     */
    protected function isRoleWriteOperation(string $path, Request $request): bool
    {
        $writeActions = ['delete', 'update', 'permission', 'save'];
        foreach ($writeActions as $action) {
            if (strpos($path, 'admin/role/' . $action) === 0 || strpos($path, 'admin/role/' . $action . '/') !== false) {
                return true;
            }
        }
        // POST请求到permission也视为写操作
        if ((strpos($path, 'admin/role/permission') === 0 || strpos($path, 'admin/role/permission/') !== false) && $request->isPost()) {
            return true;
        }
        return false;
    }

    /**
     * 检查是否在分配超级管理员角色
     */
    protected function isAssigningSuperAdminRole(Request $request): bool
    {
        $roleIds = $request->post('role_ids/a', []);
        if (empty($roleIds)) {
            $roleIds = $request->post('role_ids', '');
            if (is_string($roleIds)) {
                $roleIds = explode(',', $roleIds);
            }
        }
        
        return in_array(1, array_map('intval', (array)$roleIds));
    }
}
