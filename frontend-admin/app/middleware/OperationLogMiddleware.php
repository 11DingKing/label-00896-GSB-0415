<?php
declare(strict_types=1);

namespace app\middleware;

use Closure;
use think\Request;
use think\Response;
use think\facade\Session;
use think\facade\Db;

/**
 * 操作日志中间件
 */
class OperationLogMiddleware
{
    /**
     * 需要记录日志的操作
     */
    protected array $logActions = [
        'save'   => '新增',
        'update' => '更新',
        'delete' => '删除',
        'status' => '状态变更',
        'reply'  => '回复',
        'clear'  => '清空',
    ];

    /**
     * 模块名称映射
     */
    protected array $moduleNames = [
        'user'     => '用户管理',
        'role'     => '角色管理',
        'news'     => '新闻管理',
        'product'  => '产品管理',
        'case'     => '案例管理',
        'category' => '分类管理',
        'banner'   => 'Banner管理',
        'page'     => '单页管理',
        'message'  => '留言管理',
        'config'   => '系统配置',
        'log'      => '操作日志',
    ];

    /**
     * 处理请求
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 只记录POST请求
        if ($request->method() !== 'POST') {
            return $response;
        }

        // 获取当前操作
        $pathInfo = $request->pathinfo();
        $parts = explode('/', $pathInfo);

        if (count($parts) < 3) {
            return $response;
        }

        $module = $parts[1] ?? '';
        $action = $parts[2] ?? '';

        // 检查是否需要记录
        if (!isset($this->logActions[$action])) {
            return $response;
        }

        // 获取当前用户
        $adminUser = Session::get('admin_user');
        if (empty($adminUser)) {
            return $response;
        }

        // 记录日志
        $this->writeLog([
            'user_id'        => $adminUser['id'],
            'username'       => $adminUser['username'],
            'module'         => $this->moduleNames[$module] ?? $module,
            'action'         => $this->logActions[$action],
            'description'    => $this->getDescription($module, $action, $request),
            'ip'             => get_client_ip(),
            'user_agent'     => $request->header('user-agent', ''),
            'request_url'    => $request->url(),
            'request_method' => $request->method(),
            'request_data'   => json_encode($this->filterSensitiveData($request->post()), JSON_UNESCAPED_UNICODE),
            'created_at'     => time(),
        ]);

        return $response;
    }

    /**
     * 写入日志
     */
    protected function writeLog(array $data): void
    {
        try {
            Db::name('operation_log')->insert($data);
        } catch (\Exception $e) {
            // 日志写入失败不影响业务
            trace($e->getMessage(), 'error');
        }
    }

    /**
     * 获取操作描述
     */
    protected function getDescription(string $module, string $action, Request $request): string
    {
        $moduleName = $this->moduleNames[$module] ?? $module;
        $actionName = $this->logActions[$action] ?? $action;

        $id = $request->post('id') ?: ($request->route('id') ?: '');
        $name = $request->post('name') ?: ($request->post('title') ?: '');

        $desc = "{$actionName}{$moduleName}";
        if ($id) {
            $desc .= " [ID:{$id}]";
        }
        if ($name) {
            $desc .= " [{$name}]";
        }

        return $desc;
    }

    /**
     * 过滤敏感数据
     */
    protected function filterSensitiveData(array $data): array
    {
        $sensitiveKeys = ['password', 'password_confirm', 'token', 'captcha'];

        foreach ($sensitiveKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = '******';
            }
        }

        return $data;
    }
}
