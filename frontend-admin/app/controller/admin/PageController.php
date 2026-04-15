<?php
declare(strict_types=1);

namespace app\controller\admin;

use app\controller\BaseController;
use think\facade\View;
use app\model\Page;

/**
 * 后台单页管理控制器
 */
class PageController extends BaseController
{
    protected $middleware = ['admin', 'log'];

    /**
     * 单页列表
     */
    public function index()
    {
        $list = Page::order('id asc')
            ->paginate(15);

        View::assign([
            'list' => $list,
        ]);

        return View::fetch('page/index');
    }

    /**
     * 创建单页
     */
    public function create()
    {
        return View::fetch('page/create');
    }

    /**
     * 保存单页
     */
    public function save()
    {
        $data = $this->request->post();

        if (empty($data['slug'])) {
            return $this->error('请输入页面标识');
        }

        if (empty($data['title'])) {
            return $this->error('请输入页面标题');
        }

        // 检查标识是否存在
        $exists = Page::where('slug', $data['slug'])->find();
        if ($exists) {
            return $this->error('页面标识已存在');
        }

        $page = new Page();
        $page->slug = $data['slug'];
        $page->title = $data['title'];
        $page->content = $data['content'] ?? '';
        $page->seo_title = $data['seo_title'] ?? '';
        $page->seo_keywords = $data['seo_keywords'] ?? '';
        $page->seo_description = $data['seo_description'] ?? '';
        $page->status = $data['status'] ?? 1;
        $page->save();

        return $this->success('创建成功');
    }

    /**
     * 编辑单页
     */
    public function edit(int $id)
    {
        $page = Page::find($id);
        if (!$page) {
            return $this->error('单页不存在');
        }

        View::assign([
            'page' => $page,
        ]);

        return View::fetch('page/edit');
    }

    /**
     * 更新单页
     */
    public function update(int $id)
    {
        $page = Page::find($id);
        if (!$page) {
            return $this->error('单页不存在');
        }

        $data = $this->request->post();

        if (empty($data['title'])) {
            return $this->error('请输入页面标题');
        }

        $page->title = $data['title'];
        $page->content = $data['content'] ?? '';
        $page->seo_title = $data['seo_title'] ?? '';
        $page->seo_keywords = $data['seo_keywords'] ?? '';
        $page->seo_description = $data['seo_description'] ?? '';
        $page->status = $data['status'] ?? 1;
        $page->save();

        return $this->success('更新成功');
    }

    /**
     * 删除单页
     */
    public function delete(int $id)
    {
        $page = Page::find($id);
        if (!$page) {
            return $this->error('单页不存在');
        }

        $page->delete();

        return $this->success('删除成功');
    }
}
