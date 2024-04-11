<?php
// 事件定义文件
return [
    'bind' => [
        //...更多事件绑定
    ],
    'listen' => [
        //应用初始化标签位
        'AppInit' => [
            'BlockLine' => 'extend\behavior\BlockLine',
        ],
        //应用开始标签位
        'HttpRun' => [
            'RequestLimit' => 'extend\behavior\RequestLimit',
            'RequestLog' => 'extend\behavior\RequestLog',
        ],
        //应用结束标签位 - 参数: 当前响应对象实例
        'HttpEnd' => [
            'ResponseLog' => 'extend\behavior\ResponseLog',
        ],
        //日志write方法标签位 - 参数: 当前写入的日志信息
        'LogWrite' => [],
        //路由加载完成
        'RouteLoaded' => [],
        //日志记录
        'LogRecord' => []
        //...更多事件监听
    ],
    'subscribe' => [
        //...更多事件订阅
    ],
];
