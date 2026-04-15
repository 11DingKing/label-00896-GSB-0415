<?php
/**
 * 公共函数库
 */

use think\facade\Cache;
use think\facade\Db;

/**
 * 获取系统配置
 * @param string $key 配置键名 (格式: group.key)
 * @param mixed $default 默认值
 * @return mixed
 */
function get_config(string $key, $default = '')
{
    static $configs = null;

    if ($configs === null) {
        try {
            $configs = Cache::remember('system_configs', function () {
                $list = Db::name('system_config')->select()->toArray();
                $result = [];
                foreach ($list as $item) {
                    $result[$item['group_name'] . '.' . $item['key_name']] = $item['value'];
                }
                return $result;
            }, 3600);
        } catch (\Exception $e) {
            $configs = [];
        }
    }

    return $configs[$key] ?? $default;
}

/**
 * 清除配置缓存
 */
function clear_config_cache(): void
{
    Cache::delete('system_configs');
}

/**
 * 获取分类列表
 * @param string $type 分类类型
 * @return array
 */
function get_categories(string $type): array
{
    $cacheKey = 'categories_' . $type;
    return Cache::remember($cacheKey, function () use ($type) {
        return Db::name('category')
            ->where('type', $type)
            ->where('status', 1)
            ->order('sort', 'asc')
            ->select()
            ->toArray();
    }, 3600);
}

/**
 * 清除分类缓存
 * @param string $type 分类类型
 */
function clear_category_cache(string $type = ''): void
{
    if ($type) {
        Cache::delete('categories_' . $type);
    } else {
        Cache::delete('categories_news');
        Cache::delete('categories_product');
        Cache::delete('categories_case');
    }
}

/**
 * 生成树形结构
 * @param array $list 数据列表
 * @param int $parentId 父级ID
 * @param string $pk 主键字段
 * @param string $pid 父级字段
 * @param string $child 子级字段名
 * @return array
 */
function list_to_tree(array $list, int $parentId = 0, string $pk = 'id', string $pid = 'parent_id', string $child = 'children'): array
{
    $tree = [];
    foreach ($list as $item) {
        if ($item[$pid] == $parentId) {
            $children = list_to_tree($list, $item[$pk], $pk, $pid, $child);
            if (!empty($children)) {
                $item[$child] = $children;
            }
            $tree[] = $item;
        }
    }
    return $tree;
}

/**
 * 格式化文件大小
 * @param int $size 字节数
 * @return string
 */
function format_size(int $size): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;
    while ($size >= 1024 && $i < count($units) - 1) {
        $size /= 1024;
        $i++;
    }
    return round($size, 2) . ' ' . $units[$i];
}

/**
 * 生成随机字符串
 * @param int $length 长度
 * @return string
 */
function random_string(int $length = 16): string
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $str = '';
    for ($i = 0; $i < $length; $i++) {
        $str .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $str;
}

/**
 * 截取字符串
 * @param string $str 字符串
 * @param int $length 长度
 * @param string $suffix 后缀
 * @return string
 */
function str_cut(string $str, int $length, string $suffix = '...'): string
{
    $str = strip_tags($str);
    if (mb_strlen($str) <= $length) {
        return $str;
    }
    return mb_substr($str, 0, $length) . $suffix;
}

/**
 * 获取客户端IP
 * @return string
 */
function get_client_ip(): string
{
    $ip = request()->ip();
    return $ip ?: '0.0.0.0';
}

/**
 * XSS过滤
 * @param string $str 字符串
 * @return string
 */
function xss_clean(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * 生成上传路径
 * @param string $type 类型
 * @return string
 */
function upload_path(string $type = 'image'): string
{
    return 'uploads/' . $type . '/' . date('Ymd') . '/';
}

/**
 * 时间格式化
 * @param int $timestamp 时间戳
 * @param string $format 格式
 * @return string
 */
function format_time(int $timestamp, string $format = 'Y-m-d H:i:s'): string
{
    return $timestamp > 0 ? date($format, $timestamp) : '-';
}

/**
 * 友好时间显示
 * @param int $timestamp 时间戳
 * @return string
 */
function friendly_time(int $timestamp): string
{
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return '刚刚';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . '分钟前';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . '小时前';
    } elseif ($diff < 2592000) {
        return floor($diff / 86400) . '天前';
    } else {
        return date('Y-m-d', $timestamp);
    }
}

/**
 * 检查是否为手机访问
 * @return bool
 */
function is_mobile(): bool
{
    $userAgent = request()->header('user-agent', '');
    $mobileAgents = ['iPhone', 'iPad', 'Android', 'Mobile', 'iPod'];

    foreach ($mobileAgents as $agent) {
        if (stripos($userAgent, $agent) !== false) {
            return true;
        }
    }
    return false;
}
