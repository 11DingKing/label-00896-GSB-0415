<?php
declare(strict_types=1);

namespace app\controller\admin;

use app\controller\BaseController;
use think\facade\View;
use app\model\News;
use app\model\Category;
use app\validate\NewsValidate;

/**
 * 后台新闻管理控制器
 */
class NewsController extends BaseController
{
    protected $middleware = ['admin', 'log'];

    /**
     * 新闻列表
     */
    public function index()
    {
        // 如果有 id 参数，说明是编辑请求被错误路由到这里
        $id = $this->request->get('id', 0);
        if ($id) {
            return $this->edit();
        }

        $keyword = $this->request->get('keyword', '');
        $categoryId = $this->request->get('category_id', '');
        $status = $this->request->get('status', '');
        $limit = $this->request->get('limit', 15, 'intval'); // 获取每页数量，默认15

        $query = News::with(['category']);

        if ($keyword) {
            $query->whereLike('title', "%{$keyword}%");
        }

        if ($categoryId !== '') {
            $query->where('category_id', $categoryId);
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        $list = $query->order('id', 'desc')
            ->paginate($limit)
            ->appends(['keyword' => $keyword, 'status' => $status, 'limit' => $limit]);

        $categories = Category::getOptions('news');

        View::assign([
            'list'       => $list,
            'categories' => $categories,
            'keyword'    => $keyword,
            'category_id' => $categoryId,
            'status'     => $status,
        ]);

        return View::fetch('news/index');
    }

    /**
     * 创建新闻
     */
    public function create()
    {
        $categories = Category::getOptions('news');

        View::assign([
            'categories' => $categories,
        ]);

        return View::fetch('news/create');
    }

    /**
     * 保存新闻
     */
    public function save()
    {
        $data = $this->request->post();

        try {
            $this->validate($data, NewsValidate::class);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        $news = new News();
        $news->category_id = $data['category_id'] ?? 0;
        $news->title = $data['title'];
        $news->cover = $data['cover'] ?? '';
        $news->summary = $data['summary'] ?? '';
        $news->content = $data['content'];
        $news->author = $data['author'] ?? '';
        $news->source = $data['source'] ?? '';
        $news->is_top = $data['is_top'] ?? 0;
        $news->is_recommend = $data['is_recommend'] ?? 0;
        $news->status = $data['status'] ?? 1;
        $news->published_at = !empty($data['published_at']) ? strtotime($data['published_at']) : time();
        $news->save();

        return $this->success('创建成功');
    }

    /**
     * 编辑新闻 - 返回JSON数据
     */
    public function edit()
    {
        $id = $this->request->get('id', 0);
        if (empty($id)) {
            return $this->error('参数错误');
        }

        $news = News::find($id);
        if (!$news) {
            return $this->error('新闻不存在');
        }

        return $this->success('获取成功', $news->toArray());
    }

    /**
     * 更新新闻
     */
    public function update()
    {
        $data = $this->request->post();
        $id = $data['id'] ?? 0;

        $news = News::find($id);
        if (!$news) {
            return $this->error('新闻不存在');
        }

        try {
            $this->validate($data, NewsValidate::class);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        $news->title = $data['title'];
        $news->summary = $data['summary'] ?? '';
        $news->content = $data['content'];
        $news->author = $data['author'] ?? '';
        $news->status = $data['status'] ?? 1;
        $news->save();

        return $this->success('更新成功');
    }

    /**
     * 删除新闻
     */
    public function delete(int $id)
    {
        $news = News::find($id);
        if (!$news) {
            return $this->error('新闻不存在');
        }

        $news->delete();

        return $this->success('删除成功');
    }

    /**
     * 获取新闻数据 (纯JSON API)
     */
    public function get(int $id)
    {
        $news = News::find($id);
        if (!$news) {
            return $this->error('新闻不存在');
        }
        return $this->success('获取成功', $news->toArray());
    }

    /**
     * 更改状态
     */
    public function status(int $id)
    {
        $news = News::find($id);
        if (!$news) {
            return $this->error('新闻不存在');
        }

        $news->status = $news->status == 1 ? 0 : 1;
        $news->save();

        return $this->success('状态更新成功');
    }
}
