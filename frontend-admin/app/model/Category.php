<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * 分类模型
 */
class Category extends Model
{
    protected $name = 'category';

    protected $pk = 'id';

    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    /**
     * 获取分类树
     */
    public static function getTree(string $type = ''): array
    {
        $query = self::where('status', 1);

        if ($type) {
            $query->where('type', $type);
        }

        $list = $query->order('sort', 'asc')
            ->select()
            ->toArray();

        return list_to_tree($list);
    }

    /**
     * 获取分类选项
     */
    public static function getOptions(string $type): array
    {
        return self::where('type', $type)
            ->where('status', 1)
            ->order('sort', 'asc')
            ->column('name', 'id');
    }
}
