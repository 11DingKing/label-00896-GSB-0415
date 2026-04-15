<?php
declare(strict_types=1);

namespace app\controller\admin;

use app\controller\BaseController;
use think\facade\View;
use app\model\OperationLog;

/**
 * 后台操作日志控制器
 */
class LogController extends BaseController
{
    protected $middleware = ['admin'];

    /**
     * 日志列表
     */
    public function index()
    {
        $keyword = $this->request->get('keyword', '');
        $module = $this->request->get('module', '');
        $startDate = $this->request->get('start_date', '');
        $endDate = $this->request->get('end_date', '');
        $limit = $this->request->get('limit', 15, 'intval'); // 获取每页数量，默认15

        $query = OperationLog::order('id desc');

        if ($keyword) {
            $query->whereLike('username|description|ip', "%{$keyword}%");
        }

        if ($module) {
            $query->where('module', $module);
        }

        if ($startDate) {
            $query->where('created_at', '>=', strtotime($startDate));
        }

        if ($endDate) {
            $query->where('created_at', '<=', strtotime($endDate . ' 23:59:59'));
        }

        $list = $query->paginate($limit)
            ->appends(['keyword' => $keyword, 'module' => $module, 'limit' => $limit]);

        // 获取模块列表
        $modules = OperationLog::distinct(true)->column('module');

        View::assign([
            'list'       => $list,
            'modules'    => $modules,
            'keyword'    => $keyword,
            'module'     => $module,
            'start_date' => $startDate,
            'end_date'   => $endDate,
        ]);

        return View::fetch('log/index');
    }

    /**
     * 清空日志
     */
    public function clear()
    {
        $adminUser = session('admin_user');
        if ($adminUser['id'] != 1) {
            return $this->error('只有超级管理员可以清空日志');
        }

        OperationLog::clearAll();

        return $this->success('日志已清空');
    }

    /**
     * 清理过期日志
     */
    public function clearExpired()
    {
        $days = $this->request->post('days', 30, 'intval');

        $count = OperationLog::clearExpired($days);

        return $this->success("已清理 {$count} 条过期日志");
    }
}
