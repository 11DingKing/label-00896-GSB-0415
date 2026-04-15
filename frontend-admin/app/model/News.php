<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * 新闻模型
 */
class News extends Model
{
    protected $name = 'news';

    protected $pk = 'id';

    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    /**
     * 关联分类
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * 获取摘要
     */
    public function getSummaryTextAttr($value, $data): string
    {
        if (!empty($data['summary'])) {
            return $data['summary'];
        }
        return str_cut($data['content'] ?? '', 200);
    }

    /**
     * 增加浏览量
     */
    public function incrementViews(): void
    {
        $this->inc('views')->update();
    }

    /**
     * 获取上一篇
     */
    public function getPrev(): ?array
    {
        return self::where('id', '<', $this->id)
            ->where('status', 1)
            ->order('id', 'desc')
            ->field('id,title')
            ->find()?->toArray();
    }

    /**
     * 获取下一篇
     */
    public function getNext(): ?array
    {
        return self::where('id', '>', $this->id)
            ->where('status', 1)
            ->order('id', 'asc')
            ->field('id,title')
            ->find()?->toArray();
    }
}
