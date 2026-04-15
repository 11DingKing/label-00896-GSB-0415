<?php
declare(strict_types=1);

namespace app\middleware;

use Closure;
use think\Request;
use think\Response;

/**
 * 前台认证中间件
 */
class AuthMiddleware
{
    /**
     * 处理请求
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
