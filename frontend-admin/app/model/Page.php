<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * 单页模型
 */
class Page extends Model
{
    protected $name = 'page';

    protected $pk = 'id';

    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    /**
     * 根据标识获取单页
     */
    public static function getBySlug(string $slug): ?array
    {
        $page = self::where('slug', $slug)
            ->where('status', 1)
            ->find();

        return $page ? $page->toArray() : null;
    }
}
