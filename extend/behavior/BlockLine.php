<?php

namespace extend\behavior;

use think\facade\Log;

class BlockLine
{
    private static $done = 0; //当前会话周期 执行次数

    //起始线-便于块日志抓取
    public function handle()
    {
        if (!request()->isCli()) {
            $tag = "[APP_START]";
        } else {
            $tag = "[CONSOLE_START]";
        }

        $line = PHP_EOL . '---------------------------------------------------------------';
        $name = "[" . date("Y-m-d H:i:s", time()) . ' - ' . (int)(microtime(true) * 1000 * 1000 * 1000) . "|" . getmypid() . "]" . '[info] ' . $tag;
        $log  = $line . PHP_EOL . $name;
        Log::info($log);

        //AppInit 首次初始化 才记录
//        if (self::$done == 0) {
//            $line = PHP_EOL . '---------------------------------------------------------------';
//            $name = "[" . date("Y-m-d H:i:s", time()) . ' - ' . (int)(microtime(true) * 1000 * 1000 * 1000) . "|" . getmypid() . "]" . '[info] ' . $tag;
//            $log  = $line . PHP_EOL . $name;
//            Log::info($log);
//            self::$done += 1;
//        }

    }
}