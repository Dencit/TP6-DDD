<?php

// +----------------------------------------------------------------------
// | 日志设置
// +----------------------------------------------------------------------
return [
    'default'      => 'log', // 默认日志记录通道
    //日志记录级别
    'level'        => [
        'info',
        'sql',
        'critical',
        'warning',
        'emergency',
        'error',
        'notice',
        'debug',
        'alert'
    ],
    'type_channel' => [], // 日志类型记录的通道 ['error'=>'email',...]
    'close'        => false, // 关闭全局日志写入
    'processor'    => null, // 全局日志处理 支持闭包

    //日志通道列表
    'channels'     => [
        'log'            => [
            //'type'           => 'File', // 日志记录方式
            'type'           => extend\think\log\driver\File::class, // 日志记录方式 - 改驱动
            'path'           => app()->getRuntimePath() . 'log', // 日志保存目录
            'single'         => false, // 单文件日志写入
            'apart_level'    => [], // 独立日志级别
            'max_files'      => 30, // 最大日志文件数量
            'file_size'      => 1024 * 1024 * 10,
            'json'           => false, // 使用JSON格式记录
            'processor'      => null, // 日志处理
            'close'          => false, // 关闭通道日志写入
            'time_format'    => 'Y-m-d H:i:s - ' . (int)(microtime(true) * 1000 * 1000 * 1000) . '|' . getmypid(),
            'format'         => '[%s][%s] %s',// 日志输出格式化
            'realtime_write' => true, // 是否实时写入
        ],
        //http异常日志
        'http_exception' => [
            //'type'           => 'File', // 日志记录方式
            'type'           => extend\think\log\driver\File::class, // 日志记录方式 - 改驱动
            'path'           => app()->getRuntimePath() . 'http_exception',
            'single'         => false,
            'apart_level'    => [],
            'max_files'      => 30,
            'file_size'      => 1024 * 1024 * 10,
            'json'           => false,
            'processor'      => null,
            'close'          => false,
            'time_format'    => 'Y-m-d H:i:s - ' . (int)(microtime(true) * 1000 * 1000 * 1000) . '|' . getmypid(),
            'format'         => '[%s][%s] %s',
            'realtime_write' => true,
        ],
        //测试日志
        'test'           => [
            //'type'           => 'File', // 日志记录方式
            'type'           => extend\think\log\driver\File::class, // 日志记录方式 - 改驱动
            'path'           => app()->getRuntimePath() . 'test',
            'single'         => false,
            'apart_level'    => [],
            'max_files'      => 30,
            'file_size'      => 1024 * 1024 * 10,
            'json'           => false,
            'processor'      => null,
            'close'          => false,
            'time_format'    => 'Y-m-d H:i:s - ' . (int)(microtime(true) * 1000 * 1000 * 1000) . '|' . getmypid(),
            'format'         => '[%s][%s] %s',
            'realtime_write' => true,
        ],
        //队列总日志
        'queue'          => [
            //'type'           => 'File', // 日志记录方式
            'type'           => extend\think\log\driver\File::class, // 日志记录方式 - 改驱动
            'path'           => app()->getRuntimePath() . 'queue',
            'single'         => false,
            'apart_level'    => [],
            'max_files'      => 30,
            'file_size'      => 1024 * 1024 * 10,
            'json'           => false,
            'processor'      => null,
            'close'          => false,
            'time_format'    => 'Y-m-d H:i:s - ' . (int)(microtime(true) * 1000 * 1000 * 1000) . '|' . getmypid(),
            'format'         => '[%s][%s] %s',
            'realtime_write' => true,
        ],
        //任务总日志
        'task'           => [
            //'type'           => 'File', // 日志记录方式
            'type'           => extend\think\log\driver\File::class, // 日志记录方式 - 改驱动
            'path'           => root_path() . 'runtime/task',
            'single'         => false,
            'apart_level'    => [],
            'max_files'      => 30,
            'file_size'      => 1024 * 1024 * 10,
            'json'           => false,
            'processor'      => null,
            'close'          => false,
            'time_format'    => 'Y-m-d H:i:s - ' . (int)(microtime(true) * 1000 * 1000 * 1000) . '|' . getmypid(),
            'format'         => '[%s][%s] %s',
            'realtime_write' => true,
        ],
        //phpunit日志
        'unit'           => [
            //'type'           => 'File', // 日志记录方式
            'type'           => extend\think\log\driver\File::class, // 日志记录方式 - 改驱动
            'path'           => app()->getRuntimePath() . 'unit',
            'single'         => false,
            'apart_level'    => [],
            'max_files'      => 30,
            'file_size'      => 1024 * 1024 * 10,
            'json'           => false,
            'processor'      => null,
            'close'          => false,
            'time_format'    => 'Y-m-d H:i:s - ' . (int)(microtime(true) * 1000 * 1000 * 1000) . '|' . getmypid(),
            'format'         => '[%s][%s] %s',
            'realtime_write' => true,
        ],

    ],

];
