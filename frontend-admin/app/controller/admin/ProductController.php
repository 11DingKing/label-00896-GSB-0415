<?php
declare(strict_types=1);

namespace app\controller\admin;

use app\controller\BaseController;
use think\facade\View;
use app\model\Product;
use app\model\Category;
use app\validate\ProductValidate;

/**
 * 后台产品管理控制器
 */
class ProductController extends BaseController
{
    protected $middleware = ['admin', 'log'];

    /**
     * 产品列表
     */
    public function index()
    {
        // 如果有 id 参数，说明是编辑请求
        $id = $this->request->get('id', 0);
        if ($id) {
            return $this->edit();
        }

        $keyword = $this->request->get('keyword', '');
        $categoryId = $this->request->get('category_id', '');
        $status = $this->request->get('status', '');
        $limit = $this->request->get('limit', 15, 'intval'); // 获取每页数量，默认15

        $query = Product::with(['category']);

        if ($keyword) {
            $query->whereLike('name', "%{$keyword}%");
        }

        if ($categoryId !== '') {
            $query->where('category_id', $categoryId);
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        $list = $query->order('sort asc, id desc')
            ->paginate($limit)
            ->appends(['keyword' => $keyword, 'status' => $status, 'limit' => $limit]);

        $categories = Category::getOptions('product');

        View::assign([
            'list'       => $list,
            'categories' => $categories,
            'keyword'    => $keyword,
            'category_id' => $categoryId,
            'status'     => $status,
        ]);

        return View::fetch('product/index');
    }

    /**
     * 创建产品
     */
    public function create()
    {
        $categories = Category::getOptions('product');

        View::assign([
            'categories' => $categories,
        ]);

        return View::fetch('product/create');
    }

    /**
     * 保存产品
     */
    public function save()
    {
        $data = $this->request->post();

        try {
            $this->validate($data, ProductValidate::class);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        $product = new Product();
        $product->category_id = $data['category_id'] ?? 0;
        $product->name = $data['name'];
        $product->cover = $data['cover'] ?? '';
        $product->images = $data['images'] ?? [];
        $product->description = $data['description'] ?? '';
        $product->content = $data['content'] ?? '';
        $product->price = $data['price'] ?? 0;
        $product->sort = $data['sort'] ?? 0;
        $product->is_recommend = $data['is_recommend'] ?? 0;
        $product->status = $data['status'] ?? 1;
        $product->save();

        return $this->success('创建成功');
    }

    /**
     * 编辑产品 (AJAX)
     */
    public function edit()
    {
        $id = $this->request->get('id', 0);
        $product = Product::find($id);
        if (!$product) {
            return $this->error('产品不存在');
        }

        return $this->success('获取成功', $product->toArray());
    }

    /**
     * 更新产品 (AJAX)
     */
    public function update()
    {
        $id = $this->request->post('id', 0);
        $product = Product::find($id);
        if (!$product) {
            return $this->error('产品不存在');
        }

        $data = $this->request->post();

        try {
            $this->validate($data, ProductValidate::class);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        $product->category_id = $data['category_id'] ?? $product->category_id;
        $product->name = $data['name'];
        $product->cover = $data['cover'] ?? $product->cover;
        $product->images = $data['images'] ?? $product->images;
        $product->description = $data['description'] ?? '';
        $product->content = $data['content'] ?? '';
        $product->price = $data['price'] ?? 0;
        $product->sort = $data['sort'] ?? $product->sort;
        $product->is_recommend = $data['is_recommend'] ?? $product->is_recommend;
        $product->status = $data['status'] ?? 1;
        $product->save();

        return $this->success('更新成功');
    }

    /**
     * 删除产品
     */
    public function delete(int $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->error('产品不存在');
        }

        $product->delete();

        return $this->success('删除成功');
    }

    /**
     * 获取产品数据 (纯JSON API)
     */
    public function get(int $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->error('产品不存在');
        }
        return $this->success('获取成功', $product->toArray());
    }

    /**
     * 更改状态
     */
    public function status(int $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->error('产品不存在');
        }

        $product->status = $product->status == 1 ? 0 : 1;
        $product->save();

        return $this->success('状态更新成功');
    }
}
