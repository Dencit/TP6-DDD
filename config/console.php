<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        //基础指令
        'cron_timer'    => schedule\CronTimer::class,     //cron-expression 基本定时任务
        'cron_test'     => schedule\CronTest::class,      //cron-expression 基本定时任务调试
        'task_test'     => schedule\TaskTest::class,      //easyTask 并发任务调试
        'queue_monitor' => schedule\QueueMonitor::class,  //队列监控
        //业务指令
        'sample_cmd'    => domain\demo\console\SampleCmd::class, //CMD模板
    ],
];
