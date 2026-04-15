<?php
declare(strict_types=1);

namespace app\controller;

use think\facade\View;
use think\facade\Db;
use think\facade\Cache;
use app\model\Banner;
use app\model\News;
use app\model\Product;
use app\model\CaseStudy;
use app\model\Page;
use app\model\Message;
use app\validate\MessageValidate;

/**
 * 前台首页控制器
 */
class Index extends BaseController
{
    /**
     * 初始化
     */
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 首页
     */
    public function index()
    {
        // 简洁首页，不加载数据列表
        return View::fetch();
    }

    /**
     * 关于我们
     */
    public function about()
    {
        $page = Page::getBySlug('about');

        View::assign([
            'page' => $page,
        ]);

        return View::fetch();
    }

    /**
     * 联系我们
     */
    public function contact()
    {
        $page = Page::getBySlug('contact');

        // 生成 CSRF Token 并存入缓存（30分钟有效）
        $csrfToken = bin2hex(random_bytes(32));
        Cache::set('csrf_' . $csrfToken, true, 1800);

        View::assign([
            'page'          => $page,
            'company_name'  => get_config('contact.company_name'),
            'company_address' => get_config('contact.company_address'),
            'company_phone' => get_config('contact.company_phone'),
            'company_email' => get_config('contact.company_email'),
            'csrf_token'    => $csrfToken,
        ]);

        return View::fetch();
    }

    /**
     * 提交留言
     */
    public function message()
    {
        if (!$this->request->isPost()) {
            return $this->error('请求方式错误');
        }

        // CSRF Token 验证
        $token = $this->request->header('X-CSRF-TOKEN') ?: $this->request->post('_token');

        if (empty($token)) {
            return $this->error('页面已过期，请刷新后重试');
        }

        // 从缓存中验证 Token
        $isValid = Cache::get('csrf_' . $token);

        if (!$isValid) {
            return $this->error('页面已过期，请刷新后重试');
        }

        // 验证成功后删除 Token（防止重复提交）
        Cache::delete('csrf_' . $token);

        $data = $this->request->post();

        // 验证数据
        try {
            $this->validate($data, MessageValidate::class);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        // 手动验证 email 格式（非必填）
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->error('邮箱格式不正确');
        }

        // 手动验证 phone 格式（非必填）
        if (!empty($data['phone']) && !preg_match('/^1[3-9]\d{9}$/', $data['phone'])) {
            return $this->error('手机号格式不正确，请输入11位手机号');
        }

        // 保存留言
        $message = new Message();
        $message->name = xss_clean($data['name']);
        $message->email = $data['email'] ?? '';
        $message->phone = $data['phone'] ?? '';
        $message->company = xss_clean($data['company'] ?? '');
        $message->subject = xss_clean($data['subject'] ?? '');
        $message->content = xss_clean($data['content']);
        $message->ip = get_client_ip();
        $message->created_at = time();
        $message->save();

        return $this->success('留言提交成功，我们会尽快与您联系');
    }
}
