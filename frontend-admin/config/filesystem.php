<?php
// 文件系统配置
return [
    // 默认磁盘
    'default' => env('FILESYSTEM_DRIVER', 'local'),
    // 磁盘列表
    'disks'   => [
        'local'  => [
            'type' => 'local',
            'root' => root_path() . 'runtime' . DIRECTORY_SEPARATOR . 'storage',
        ],
        'public' => [
            'type'       => 'local',
            'root'       => root_path() . 'public' . DIRECTORY_SEPARATOR . 'uploads',
            'url'        => '/uploads',
            'visibility' => 'public',
        ],
    ],
];
