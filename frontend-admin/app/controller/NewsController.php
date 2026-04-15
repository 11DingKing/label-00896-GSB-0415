<?php
declare(strict_types=1);

namespace app\controller;

use think\facade\View;
use app\model\News;
use app\model\Category;

/**
 * 前台新闻控制器
 */
class NewsController extends BaseController
{
    /**
     * 新闻列表
     */
    public function index()
    {
        $categoryId = $this->request->get('category_id', 0, 'intval');

        $query = News::where('status', 1);

        if ($categoryId > 0) {
            $query->where('category_id', $categoryId);
        }

        $list = $query->order('is_top desc, id desc')
            ->paginate(12);

        // 获取分类
        $categories = get_categories('news');

        View::assign([
            'list'       => $list,
            'categories' => $categories,
            'category_id' => $categoryId,
        ]);

        return View::fetch('news/index');
    }

    /**
     * 新闻详情
     */
    public function detail(int $id)
    {
        $news = News::where('id', $id)
            ->where('status', 1)
            ->find();

        if (!$news) {
            abort(404, '新闻不存在');
        }

        // 增加浏览量
        $news->incrementViews();

        // 获取上一篇/下一篇
        $prev = $news->getPrev();
        $next = $news->getNext();

        // 获取相关新闻
        $related = News::where('status', 1)
            ->where('id', '<>', $id)
            ->where('category_id', $news->category_id)
            ->order('id desc')
            ->limit(5)
            ->select();

        View::assign([
            'news'    => $news,
            'prev'    => $prev,
            'next'    => $next,
            'related' => $related,
        ]);

        return View::fetch('news/detail');
    }
}
