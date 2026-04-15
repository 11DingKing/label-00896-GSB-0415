<?php
// 中间件配置
return [
    // 别名或分组
    'alias'    => [
        'auth'  => app\middleware\AuthMiddleware::class,
        'admin' => app\middleware\AdminAuthMiddleware::class,
        'log'   => app\middleware\OperationLogMiddleware::class,
        'csrf'  => app\middleware\CsrfMiddleware::class,
    ],
    // 优先级设置，此数组中的中间件会按照数组中的顺序优先执行
    'priority' => [],
];
