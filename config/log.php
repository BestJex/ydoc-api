<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 日志设置
// +----------------------------------------------------------------------
return [
    // 日志记录方式，内置 file socket 支持扩展
    'type'        => 'File',
    // 日志保存目录
    'path'        => '',
    // 日志记录级别
    'level'       => ['error', 'warning', 'request'],
    // 单文件日志写入
    'single'      => false,
    // 独立日志级别
    'apart_level' => ['error', 'warning'],
    // 最大日志文件数量
    'max_files'   => 0,
    // 是否关闭日志写入
    'close'       => false,
    // 日志日期格式
    'time_format' => 'Y-m-d H:i:s',
    // 日志文件最大大小
    'file_size'   => 1024 * 1024 * 3,
];
