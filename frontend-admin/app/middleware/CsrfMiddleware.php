<?php
declare(strict_types=1);

namespace app\middleware;

use Closure;
use think\Request;
use think\Response;
use think\facade\Session;
use think\facade\View;

/**
 * CSRF 防护中间件
 * 防止跨站请求伪造攻击
 */
class CsrfMiddleware
{
    /**
     * 不需要验证 CSRF 的路由
     */
    protected array $except = [
        'admin/auth/login',
        'admin/auth/captcha',
        'admin/upload/image',
        'admin/upload/file',
    ];

    /**
     * 处理请求
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 生成 CSRF Token
        $token = $this->getToken();
        
        // 设置到视图变量，供模板使用
        View::assign('csrf_token', $token);
        
        // 只对 POST 请求验证
        if ($request->isPost()) {
            // 检查是否在排除列表中
            $path = strtolower($request->pathinfo());
            foreach ($this->except as $pattern) {
                if (strpos($path, $pattern) === 0) {
                    return $next($request);
                }
            }
            
            // 验证 Token
            $requestToken = $request->header('X-CSRF-TOKEN') ?: $request->post('_token');
            
            if (!$this->validateToken($requestToken)) {
                if ($request->isAjax()) {
                    return json(['code' => 0, 'msg' => 'CSRF Token 验证失败，请刷新页面重试']);
                }
                return response('CSRF Token 验证失败', 403);
            }
        }

        return $next($request);
    }

    /**
     * 获取或生成 Token
     */
    protected function getToken(): string
    {
        $token = Session::get('csrf_token');
        
        if (empty($token)) {
            $token = bin2hex(random_bytes(32));
            Session::set('csrf_token', $token);
        }
        
        return $token;
    }

    /**
     * 验证 Token
     */
    protected function validateToken(?string $token): bool
    {
        if (empty($token)) {
            return false;
        }
        
        $sessionToken = Session::get('csrf_token');
        
        if (empty($sessionToken)) {
            return false;
        }
        
        return hash_equals($sessionToken, $token);
    }
}
