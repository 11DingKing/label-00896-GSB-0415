<?php
// 数据库配置
return [
    // 默认使用的数据库连接配置
    'default'         => env('DATABASE_TYPE', 'sqlite'),
    // 自定义时间查询规则
    'time_query_rule' => [],
    // 自动写入时间戳字段
    'auto_timestamp'  => true,
    // 时间字段取出后的默认时间格式
    'datetime_format' => false,
    // 时间字段配置
    'datetime_field'  => '',
    // 数据库连接配置信息
    'connections'     => [
        'sqlite' => [
            // 数据库类型
            'type'            => 'sqlite',
            // 数据库文件路径
            'database'        => env('DATABASE_DATABASE', root_path() . 'database' . DIRECTORY_SEPARATOR . 'data.db'),
            // 数据库表前缀
            'prefix'          => env('DATABASE_PREFIX', 'cms_'),
            // 数据库部署方式
            'deploy'          => 0,
            // 数据库调试模式
            'debug'           => env('DATABASE_DEBUG', false),
            // 数据库编码默认采用utf8
            'charset'         => 'utf8',
            // 是否需要断线重连
            'break_reconnect' => false,
            // 字段缓存路径
            'schema_cache_path' => root_path() . 'runtime' . DIRECTORY_SEPARATOR . 'schema' . DIRECTORY_SEPARATOR,
        ],
    ],
];
