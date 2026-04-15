<?php
declare(strict_types=1);

namespace app\controller\admin;

use app\controller\BaseController;
use think\facade\View;
use app\model\Message;

/**
 * 后台留言管理控制器
 */
class MessageController extends BaseController
{
    protected $middleware = ['admin', 'log'];

    /**
     * 留言列表
     */
    public function index()
    {
        // 如果有 id 参数，说明是查看请求
        $id = $this->request->get('id', 0);
        if ($id) {
            return $this->view();
        }

        $keyword = $this->request->get('keyword', '');
        $isRead = $this->request->get('is_read', '');
        $limit = $this->request->get('limit', 15, 'intval'); // 获取每页数量，默认15

        $query = Message::order('id desc');

        if ($keyword) {
            $query->whereLike('name|email|phone|content', "%{$keyword}%");
        }

        if ($isRead !== '') {
            $query->where('is_read', $isRead);
        }

        $list = $query->paginate($limit)
            ->appends(['keyword' => $keyword, 'is_read' => $isRead, 'limit' => $limit]);

        View::assign([
            'list'    => $list,
            'keyword' => $keyword,
            'is_read' => $isRead,
        ]);

        return View::fetch('message/index');
    }

    /**
     * 查看留言 (AJAX)
     */
    public function view()
    {
        $id = $this->request->get('id', 0);
        $message = Message::find($id);
        if (!$message) {
            return $this->error('留言不存在');
        }

        // 标记为已读
        $message->markAsRead();

        return $this->success('获取成功', $message->toArray());
    }

    /**
     * 回复留言
     */
    public function reply(int $id)
    {
        $message = Message::find($id);
        if (!$message) {
            return $this->error('留言不存在');
        }

        $reply = $this->request->post('reply', '');
        if (empty($reply)) {
            return $this->error('请输入回复内容');
        }

        $message->replyMessage($reply);

        return $this->success('回复成功');
    }

    /**
     * 删除留言
     */
    public function delete(int $id)
    {
        $message = Message::find($id);
        if (!$message) {
            return $this->error('留言不存在');
        }

        $message->delete();

        return $this->success('删除成功');
    }

    /**
     * 批量删除
     */
    public function batchDelete()
    {
        $ids = $this->request->post('ids', []);
        if (empty($ids)) {
            return $this->error('请选择要删除的留言');
        }

        Message::whereIn('id', $ids)->delete();

        return $this->success('删除成功');
    }

    /**
     * 标记已读
     */
    public function markRead()
    {
        $ids = $this->request->post('ids', []);
        if (empty($ids)) {
            return $this->error('请选择留言');
        }

        Message::whereIn('id', $ids)->update(['is_read' => 1]);

        return $this->success('操作成功');
    }
}
