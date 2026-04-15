<?php
declare(strict_types=1);

namespace app\controller\admin;

use app\controller\BaseController;
use think\facade\View;
use app\model\Category;

/**
 * 后台分类管理控制器
 */
class CategoryController extends BaseController
{
    protected $middleware = ['admin', 'log'];

    /**
     * 分类列表
     */
    public function index()
    {
        $type = $this->request->get('type', 'news');

        $list = Category::where('type', $type)
            ->order('sort asc, id asc')
            ->select();

        View::assign([
            'list' => $list,
            'type' => $type,
        ]);

        return View::fetch('category/index');
    }

    /**
     * 创建分类
     */
    public function create()
    {
        $type = $this->request->get('type', 'news');

        View::assign([
            'type' => $type,
        ]);

        return View::fetch('category/create');
    }

    /**
     * 保存分类
     */
    public function save()
    {
        $data = $this->request->post();

        if (empty($data['name'])) {
            return $this->error('请输入分类名称');
        }

        if (empty($data['type'])) {
            return $this->error('请选择分类类型');
        }

        $category = new Category();
        $category->name = $data['name'];
        $category->type = $data['type'];
        $category->parent_id = $data['parent_id'] ?? 0;
        $category->description = $data['description'] ?? '';
        $category->sort = $data['sort'] ?? 0;
        $category->status = $data['status'] ?? 1;
        $category->save();

        // 清除缓存
        clear_category_cache($data['type']);

        return $this->success('创建成功');
    }

    /**
     * 编辑分类
     */
    public function edit(int $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return $this->error('分类不存在');
        }

        View::assign([
            'category' => $category,
        ]);

        return View::fetch('category/edit');
    }

    /**
     * 更新分类
     */
    public function update(int $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return $this->error('分类不存在');
        }

        $data = $this->request->post();

        if (empty($data['name'])) {
            return $this->error('请输入分类名称');
        }

        $category->name = $data['name'];
        $category->description = $data['description'] ?? '';
        $category->sort = $data['sort'] ?? 0;
        $category->status = $data['status'] ?? 1;
        $category->save();

        // 清除缓存
        clear_category_cache($category->type);

        return $this->success('更新成功');
    }

    /**
     * 删除分类
     */
    public function delete(int $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return $this->error('分类不存在');
        }

        $type = $category->type;
        $category->delete();

        // 清除缓存
        clear_category_cache($type);

        return $this->success('删除成功');
    }
}
