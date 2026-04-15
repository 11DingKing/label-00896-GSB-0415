<?php
declare(strict_types=1);

namespace app\controller;

use think\App;
use think\exception\ValidateException;
use think\Validate;
use think\facade\View;

/**
 * 控制器基类
 */
abstract class BaseController
{
    /**
     * Request实例
     */
    protected $request;

    /**
     * 应用实例
     */
    protected $app;

    /**
     * 是否批量验证
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     */
    protected $middleware = [];

    /**
     * 构造方法
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    /**
     * 当前页面标识（用于菜单激活）
     */
    protected string $currentPage = '';

    /**
     * 初始化
     */
    protected function initialize()
    {
        // 根据请求路径自动识别当前页面
        $pathInfo = $this->request->pathinfo();
        $this->currentPage = $this->detectCurrentPage($pathInfo);

        // 设置公共视图变量
        View::assign([
            'site_name' => get_config('basic.site_name', '企业网站'),
            'site_keywords' => get_config('basic.site_keywords', ''),
            'site_description' => get_config('basic.site_description', ''),
            'current_page' => $this->currentPage,
        ]);
    }

    /**
     * 根据路径检测当前页面
     */
    protected function detectCurrentPage(string $pathInfo): string
    {
        $pathInfo = trim($pathInfo, '/');

        if (empty($pathInfo) || $pathInfo === 'index' || $pathInfo === 'index/index') {
            return 'home';
        }

        // 匹配路径前缀
        $patterns = [
            'news' => 'news',
            'product' => 'product',
            'case' => 'case',
            'about' => 'about',
            'contact' => 'contact',
        ];

        foreach ($patterns as $prefix => $page) {
            if (strpos($pathInfo, $prefix) === 0) {
                return $page;
            }
        }

        return '';
    }

    /**
     * 验证数据
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }

    /**
     * 成功响应
     */
    protected function success($msg = '操作成功', $data = null, int $code = 1)
    {
        return json(['code' => $code, 'msg' => $msg, 'data' => $data])->header(['Content-Type' => 'application/json']);
    }

    /**
     * 失败响应
     */
    protected function error($msg = '操作失败', $data = null, int $code = 0)
    {
        return json(['code' => $code, 'msg' => $msg, 'data' => $data])->header(['Content-Type' => 'application/json']);
    }
}
