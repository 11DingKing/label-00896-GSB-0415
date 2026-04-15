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

        // 超级管理员写操作保护
        if (!$this->checkSuperAdminProtection($adminUser, $request, $path)) {
            if ($isApiRequest) {
                return json(['code' => 403, 'msg' => '没有操作权限：不能修改超级管理员']);
            }
            return response('没有操作权限：不能修改超级管理员', 403);
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
     * 超级管理员写操作保护
     * 防止普通管理员对超级管理员（id=1）进行任何写操作
     */
    protected function checkSuperAdminProtection(array $user, Request $request, string $path): bool
    {
        // 超级管理员本身不受限制
        if ($user['id'] == 1) {
            return true;
        }

        // 定义需要保护的写操作路径模式
        // 格式: [路径关键词, 操作描述]
        $protectedPatterns = [
            // 用户相关写操作
            ['user/update', '修改用户'],
            ['user/delete', '删除用户'],
            ['user/status', '修改用户状态'],
            // 角色相关写操作
            ['role/update', '修改角色'],
            ['role/delete', '删除角色'],
            ['role/permission', '分配角色权限'],
        ];

        // 检查是否是需要保护的路径
        $isProtectedPath = false;
        foreach ($protectedPatterns as $pattern) {
            if (strpos($path, $pattern[0]) !== false) {
                $isProtectedPath = true;
                break;
            }
        }

        if (!$isProtectedPath) {
            return true;
        }

        // 从路由参数中获取目标ID
        // 路由格式: user/update/:id, role/delete/:id 等
        $targetId = null;
        
        // 尝试从路由参数获取
        $routeParam = $request->param('id');
        if ($routeParam !== null) {
            $targetId = (int)$routeParam;
        }

        // 如果路由参数没有，尝试从URL路径提取
        if ($targetId === null) {
            $pathParts = explode('/', $path);
            $lastPart = end($pathParts);
            if (is_numeric($lastPart)) {
                $targetId = (int)$lastPart;
            }
        }

        // 如果没有找到ID，允许通过（由控制器处理）
        if ($targetId === null) {
            return true;
        }

        // 禁止普通管理员操作id=1的超级管理员或超级管理员角色
        if ($targetId === 1) {
            return false;
        }

        return true;
    }
}
