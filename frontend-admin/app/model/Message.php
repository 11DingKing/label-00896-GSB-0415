<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * 留言模型
 */
class Message extends Model
{
    protected $name = 'message';

    protected $pk = 'id';

    protected $autoWriteTimestamp = false;

    /**
     * 标记为已读
     */
    public function markAsRead(): void
    {
        if ($this->is_read == 0) {
            $this->is_read = 1;
            $this->save();
        }
    }

    /**
     * 回复留言
     */
    public function replyMessage(string $reply): void
    {
        $this->reply = $reply;
        $this->replied_at = time();
        $this->is_read = 1;
        $this->save();
    }

    /**
     * 获取未读数量
     */
    public static function getUnreadCount(): int
    {
        return self::where('is_read', 0)->count();
    }
}
