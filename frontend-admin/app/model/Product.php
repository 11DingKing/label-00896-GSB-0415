<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * 产品模型
 */
class Product extends Model
{
    protected $name = 'product';

    protected $pk = 'id';

    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    /**
     * 图片列表获取器
     */
    public function getImagesAttr($value): array
    {
        return $value ? json_decode($value, true) : [];
    }

    /**
     * 图片列表修改器
     */
    public function setImagesAttr($value): string
    {
        return is_array($value) ? json_encode($value) : $value;
    }

    /**
     * 关联分类
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * 获取上一个
     */
    public function getPrev(): ?array
    {
        return self::where('id', '<', $this->id)
            ->where('status', 1)
            ->order('id', 'desc')
            ->field('id,name')
            ->find()?->toArray();
    }

    /**
     * 获取下一个
     */
    public function getNext(): ?array
    {
        return self::where('id', '>', $this->id)
            ->where('status', 1)
            ->order('id', 'asc')
            ->field('id,name')
            ->find()?->toArray();
    }
}
