<?php
declare(strict_types=1);

namespace app\controller\admin;

use app\controller\BaseController;
use think\facade\View;
use think\facade\Db;
use app\model\News;
use app\model\Product;
use app\model\CaseStudy;
use app\model\Message;
use app\model\AdminUser;

/**
 * 后台仪表盘控制器
 */
class DashboardController extends BaseController
{
    protected $middleware = ['admin', 'log'];

    /**
     * 仪表盘首页
     */
    public function index()
    {
        // 统计数据
        $stats = [
            'news_count'    => News::count(),
            'product_count' => Product::count(),
            'case_count'    => CaseStudy::count(),
            'message_count' => Message::count(),
            'unread_count'  => Message::getUnreadCount(),
            'user_count'    => AdminUser::count(),
        ];

        // 最新留言
        $messages = Message::order('id desc')
            ->limit(5)
            ->select();

        // 最新新闻
        $news = News::order('id desc')
            ->limit(5)
            ->select();

        // 获取菜单
        $menus = $this->getMenus();

        View::assign([
            'stats'    => $stats,
            'messages' => $messages,
            'news'     => $news,
            'menus'    => $menus,
        ]);

        return View::fetch('dashboard/index');
    }

    /**
     * 获取菜单
     */
    protected function getMenus(): array
    {
        $permissions = session('admin_permissions', []);
        $adminUser = session('admin_user');

        // 超级管理员获取所有菜单
        if ($adminUser['id'] == 1) {
            $permissions = \app\model\AdminPermission::where('status', 1)
                ->order('sort', 'asc')
                ->select()
                ->toArray();
        }

        return list_to_tree($permissions);
    }
}
