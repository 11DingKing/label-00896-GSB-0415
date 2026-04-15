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

        // 防止普通管理员操作超级管理员
        if ($adminUser['id'] != 1) {
            // 检查用户相关操作
            if (preg_match('/admin\/user\/(delete|update)/i', $path)) {
                $userId = $request->param('id/d', 0);
                if ($userId == 1) {
                    if ($isApiRequest) {
                        return json(['code' => 403, 'msg' => '无权操作超级管理员账户']);
                    }
                    return response('无权操作超级管理员账户', 403);
                }
            }

            // 检查角色相关操作
            if (preg_match('/admin\/role\/(update|delete|permission)/i', $path)) {
                $roleId = $request->param('id/d', 0);
                if ($roleId == 1) {
                    if ($isApiRequest) {
                        return json(['code' => 403, 'msg' => '无权操作超级管理员角色']);
                    }
                    return response('无权操作超级管理员角色', 403);
                }
            }
        }

        // 权限检查
        if (!$this->checkPermission($adminUser, $path)) {
            if ($isApiRequest) {
                return json(['code' => 403, 'msg' => '没有操作权限']);
            }
            return response('没有操作权限', 403);
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
}
