<?php
declare(strict_types=1);

namespace app\controller;

use think\facade\View;
use app\model\Product;
use app\model\Category;

/**
 * 前台产品控制器
 */
class ProductController extends BaseController
{
    /**
     * 产品列表
     */
    public function index()
    {
        $categoryId = $this->request->get('category_id', 0, 'intval');

        $query = Product::where('status', 1);

        if ($categoryId > 0) {
            $query->where('category_id', $categoryId);
        }

        $list = $query->order('sort asc, id desc')
            ->paginate(12);

        // 获取分类
        $categories = get_categories('product');

        View::assign([
            'list'       => $list,
            'categories' => $categories,
            'category_id' => $categoryId,
        ]);

        return View::fetch('product/index');
    }

    /**
     * 产品详情
     */
    public function detail(int $id)
    {
        $product = Product::where('id', $id)
            ->where('status', 1)
            ->find();

        if (!$product) {
            abort(404, '产品不存在');
        }

        // 获取上一个/下一个
        $prev = $product->getPrev();
        $next = $product->getNext();

        // 获取相关产品
        $related = Product::where('status', 1)
            ->where('id', '<>', $id)
            ->where('category_id', $product->category_id)
            ->order('sort asc, id desc')
            ->limit(4)
            ->select();

        View::assign([
            'product' => $product,
            'prev'    => $prev,
            'next'    => $next,
            'related' => $related,
        ]);

        return View::fetch('product/detail');
    }
}
