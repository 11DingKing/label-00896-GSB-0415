<?php
// Session配置
return [
    // session name
    'name'           => 'CMSSESSID',
    // SESSION_ID的提交变量,解决flash上传跨域
    'var_session_id' => '',
    // 驱动方式 支持file cache
    'type'           => 'file',
    // 存储连接标识 当type使用cache的时候有效
    'store'          => null,
    // 过期时间
    'expire'         => 7200,
    // 前缀
    'prefix'         => 'cms_',
    // session保存路径
    'path'           => '',
    // 是否自动开启session
    'auto_start'     => true,
];
