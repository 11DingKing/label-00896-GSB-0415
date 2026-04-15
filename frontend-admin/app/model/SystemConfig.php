<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * 系统配置模型
 */
class SystemConfig extends Model
{
    protected $name = 'system_config';

    protected $pk = 'id';

    protected $autoWriteTimestamp = false;

    /**
     * 获取分组配置
     */
    public static function getGroupConfig(string $group): array
    {
        return self::where('group_name', $group)
            ->order('sort', 'asc')
            ->select()
            ->toArray();
    }

    /**
     * 保存配置
     */
    public static function saveConfig(string $group, array $data): void
    {
        foreach ($data as $key => $value) {
            self::where('group_name', $group)
                ->where('key_name', $key)
                ->update(['value' => $value]);
        }

        // 清除缓存
        clear_config_cache();
    }
}
