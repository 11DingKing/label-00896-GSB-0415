<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * 操作日志模型
 */
class OperationLog extends Model
{
    protected $name = 'operation_log';

    protected $pk = 'id';

    protected $autoWriteTimestamp = false;

    /**
     * 清空日志
     */
    public static function clearAll(): int
    {
        return self::where('id', '>', 0)->delete();
    }

    /**
     * 清理过期日志
     */
    public static function clearExpired(int $days = 30): int
    {
        $time = time() - ($days * 86400);
        return self::where('created_at', '<', $time)->delete();
    }
}
