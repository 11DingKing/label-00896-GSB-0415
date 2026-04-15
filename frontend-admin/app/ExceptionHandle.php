<?php
declare(strict_types=1);

namespace app;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\db\exception\DbException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use think\facade\Log;
use Throwable;

/**
 * 应用异常处理类
 * 提供统一的异常处理和友好的错误响应
 */
class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     */
    public function report(Throwable $exception): void
    {
        // 记录详细的异常信息
        $this->logException($exception);

        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * 记录异常详细信息到日志
     */
    protected function logException(Throwable $exception): void
    {
        // 忽略列表中的异常不记录详细日志
        foreach ($this->ignoreReport as $class) {
            if ($exception instanceof $class) {
                return;
            }
        }

        $request = request();

        $logData = [
            'message'    => $exception->getMessage(),
            'code'       => $exception->getCode(),
            'file'       => $exception->getFile(),
            'line'       => $exception->getLine(),
            'url'        => $request->url(true),
            'method'     => $request->method(),
            'ip'         => $request->ip(),
            'user_agent' => $request->header('user-agent', ''),
            'params'     => $request->param(),
            'trace'      => $exception->getTraceAsString(),
        ];

        Log::error('Exception: ' . json_encode($logData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e): Response
    {
        // 参数验证错误
        if ($e instanceof ValidateException) {
            if ($request->isAjax()) {
                return json(['code' => 0, 'msg' => $e->getError()]);
            }
            return $this->renderErrorPage('参数验证错误', $e->getError(), 422);
        }

        // 数据未找到
        if ($e instanceof ModelNotFoundException || $e instanceof DataNotFoundException) {
            if ($request->isAjax()) {
                return json(['code' => 0, 'msg' => '数据不存在']);
            }
            return $this->renderErrorPage('404 Not Found', '您访问的内容不存在', 404);
        }

        // 数据库异常
        if ($e instanceof DbException) {
            if ($request->isAjax()) {
                return json(['code' => 0, 'msg' => '数据操作失败']);
            }
            return $this->renderErrorPage('服务器错误', '数据操作失败，请稍后重试', 500);
        }

        // HTTP 异常
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            $message = $e->getMessage() ?: $this->getHttpMessage($statusCode);

            if ($request->isAjax()) {
                return json(['code' => 0, 'msg' => $message]);
            }

            return $this->renderErrorPage($statusCode . ' Error', $message, $statusCode);
        }

        // 其他异常（生产环境不暴露详细错误）
        if (!env('APP_DEBUG', false)) {
            if ($request->isAjax()) {
                return json(['code' => 0, 'msg' => '服务器内部错误，请稍后重试']);
            }
            return $this->renderErrorPage('500 Server Error', '服务器内部错误，请稍后重试', 500);
        }

        // 调试模式下显示详细错误
        return parent::render($request, $e);
    }

    /**
     * 渲染错误页面
     */
    protected function renderErrorPage(string $title, string $message, int $code): Response
    {
        $html = <<<HTML
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title} - 企业网站管理系统</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .error-container {
            text-align: center;
            padding: 60px 40px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            max-width: 500px;
        }
        .error-code {
            font-size: 120px;
            font-weight: 700;
            color: #1890ff;
            line-height: 1;
            margin-bottom: 20px;
        }
        .error-title {
            font-size: 24px;
            color: #262626;
            margin-bottom: 16px;
        }
        .error-message {
            color: #8c8c8c;
            margin-bottom: 32px;
        }
        .btn-back {
            padding: 12px 32px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">{$code}</div>
        <h1 class="error-title">{$title}</h1>
        <p class="error-message">{$message}</p>
        <a href="javascript:history.back()" class="btn btn-outline-primary btn-back me-2">
            <i class="bi bi-arrow-left me-2"></i>返回上页
        </a>
        <a href="/" class="btn btn-primary btn-back">
            <i class="bi bi-house me-2"></i>返回首页
        </a>
    </div>
</body>
</html>
HTML;

        return response($html, $code);
    }

    /**
     * 获取 HTTP 状态码对应的默认消息
     */
    protected function getHttpMessage(int $code): string
    {
        $messages = [
            400 => '请求参数错误',
            401 => '请先登录',
            403 => '没有访问权限',
            404 => '页面不存在',
            405 => '请求方法不允许',
            500 => '服务器内部错误',
            502 => '网关错误',
            503 => '服务暂时不可用',
        ];

        return $messages[$code] ?? '请求错误';
    }
}
