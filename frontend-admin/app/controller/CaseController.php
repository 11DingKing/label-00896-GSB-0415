<?php
declare(strict_types=1);

namespace app\controller;

use think\facade\View;
use app\model\CaseStudy;
use app\model\Category;

/**
 * 前台案例控制器
 */
class CaseController extends BaseController
{
    /**
     * 案例列表
     */
    public function index()
    {
        $categoryId = $this->request->get('category_id', 0, 'intval');

        $query = CaseStudy::where('status', 1);

        if ($categoryId > 0) {
            $query->where('category_id', $categoryId);
        }

        $list = $query->order('sort asc, id desc')
            ->paginate(12);

        // 获取分类
        $categories = get_categories('case');

        View::assign([
            'list'       => $list,
            'categories' => $categories,
            'category_id' => $categoryId,
        ]);

        return View::fetch('case/index');
    }

    /**
     * 案例详情
     */
    public function detail(int $id)
    {
        $case = CaseStudy::where('id', $id)
            ->where('status', 1)
            ->find();

        if (!$case) {
            abort(404, '案例不存在');
        }

        // 获取上一个/下一个
        $prev = $case->getPrev();
        $next = $case->getNext();

        // 获取相关案例
        $related = CaseStudy::where('status', 1)
            ->where('id', '<>', $id)
            ->where('category_id', $case->category_id)
            ->order('sort asc, id desc')
            ->limit(3)
            ->select();

        View::assign([
            'case'    => $case,
            'prev'    => $prev,
            'next'    => $next,
            'related' => $related,
        ]);

        return View::fetch('case/detail');
    }
}
