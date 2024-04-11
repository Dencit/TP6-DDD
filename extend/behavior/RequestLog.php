<?php

namespace extend\behavior;

use extend\log\JsonTool;
use extend\log\xTrace;
use think\facade\Log;

class RequestLog
{
    public function handle()
    {
        if (!request()->isCli()) {
            //链路日志
            $xTrace               = xTrace::instance();
            $fromSpan             = $xTrace->pushFrom();
            $getRequest           = $xTrace->getRequest();
            $logTrace['_REQUEST'] = $getRequest;
            $logTrace['_TRACE']   = $fromSpan;

            Log::info('[APP_INIT][X-TRACE]' . PHP_EOL . JsonTool::fString($logTrace));
        }
    }
}