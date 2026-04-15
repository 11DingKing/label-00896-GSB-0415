<?php
/**
 * 验证码配置
 */
return [
    // 验证码字符集
    'codeSet' => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
    // 验证码字符长度
    'length' => 4,
    // 验证码字体大小
    'fontSize' => 25,
    // 是否使用混淆曲线
    'useCurve' => true,
    // 是否添加杂点
    'useNoise' => true,
    // 验证码图片宽度
    'imageW' => 130,
    // 验证码图片高度
    'imageH' => 50,
    // 背景颜色
    'bg' => [243, 251, 254],
    // 验证成功后是否重置
    'reset' => true,
];
