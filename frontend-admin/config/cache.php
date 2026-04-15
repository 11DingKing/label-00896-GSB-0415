<?php
// 缓存配置
return [
    // 默认缓存驱动
    'default' => env('CACHE_DRIVER', 'file'),
    // 缓存连接方式配置
    'stores'  => [
        'file' => [
            // 驱动方式
            'type'       => 'File',
            // 缓存保存目录
            'path'       => runtime_path() . 'cache' . DIRECTORY_SEPARATOR,
            // 缓存前缀
            'prefix'     => 'cms_',
            // 缓存有效期 0表示永久缓存
            'expire'     => 3600,
            // 缓存标签前缀
            'tag_prefix' => 'tag:',
            // 序列化机制
            'serialize'  => [],
        ],
    ],
];
