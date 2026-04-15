<?php
declare(strict_types=1);

namespace app\controller\admin;

use app\controller\BaseController;
use think\facade\View;
use app\model\CaseStudy;
use app\model\Category;

/**
 * 后台案例管理控制器
 */
class CaseController extends BaseController
{
    protected $middleware = ['admin', 'log'];

    /**
     * 案例列表
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

        $query = CaseStudy::with(['category']);

        if ($keyword) {
            $query->whereLike('title', "%{$keyword}%");
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

        $categories = Category::getOptions('case');

        View::assign([
            'list'       => $list,
            'categories' => $categories,
            'keyword'    => $keyword,
            'category_id' => $categoryId,
            'status'     => $status,
        ]);

        return View::fetch('case/index');
    }

    /**
     * 创建案例
     */
    public function create()
    {
        $categories = Category::getOptions('case');

        View::assign([
            'categories' => $categories,
        ]);

        return View::fetch('case/create');
    }

    /**
     * 保存案例
     */
    public function save()
    {
        $data = $this->request->post();

        if (empty($data['title'])) {
            return $this->error('请输入案例标题');
        }

        $case = new CaseStudy();
        $case->category_id = $data['category_id'] ?? 0;
        $case->title = $data['title'];
        $case->cover = $data['cover'] ?? '';
        $case->images = $data['images'] ?? [];
        $case->client = $data['client'] ?? '';
        $case->description = $data['description'] ?? '';
        $case->content = $data['content'] ?? '';
        $case->sort = $data['sort'] ?? 0;
        $case->is_recommend = $data['is_recommend'] ?? 0;
        $case->status = $data['status'] ?? 1;
        $case->save();

        return $this->success('创建成功');
    }

    /**
     * 编辑案例 (AJAX)
     */
    public function edit()
    {
        $id = $this->request->get('id', 0);
        $case = CaseStudy::find($id);
        if (!$case) {
            return $this->error('案例不存在');
        }

        return $this->success('获取成功', $case->toArray());
    }

    /**
     * 更新案例 (AJAX)
     */
    public function update()
    {
        $id = $this->request->post('id', 0);
        $case = CaseStudy::find($id);
        if (!$case) {
            return $this->error('案例不存在');
        }

        $data = $this->request->post();

        if (empty($data['title'])) {
            return $this->error('请输入案例标题');
        }

        $case->category_id = $data['category_id'] ?? 0;
        $case->title = $data['title'];
        $case->cover = $data['cover'] ?? '';
        $case->images = $data['images'] ?? [];
        $case->client = $data['client'] ?? '';
        $case->description = $data['description'] ?? '';
        $case->content = $data['content'] ?? '';
        $case->sort = $data['sort'] ?? 0;
        $case->is_recommend = $data['is_recommend'] ?? 0;
        $case->status = $data['status'] ?? 1;
        $case->save();

        return $this->success('更新成功');
    }

    /**
     * 删除案例
     */
    public function delete(int $id)
    {
        $case = CaseStudy::find($id);
        if (!$case) {
            return $this->error('案例不存在');
        }

        $case->delete();

        return $this->success('删除成功');
    }

    /**
     * 获取案例数据 (纯JSON API)
     */
    public function get(int $id)
    {
        $case = CaseStudy::find($id);
        if (!$case) {
            return $this->error('案例不存在');
        }
        return $this->success('获取成功', $case->toArray());
    }

    /**
     * 更改状态
     */
    public function status(int $id)
    {
        $case = CaseStudy::find($id);
        if (!$case) {
            return $this->error('案例不存在');
        }

        $case->status = $case->status == 1 ? 0 : 1;
        $case->save();

        return $this->success('状态更新成功');
    }
}
