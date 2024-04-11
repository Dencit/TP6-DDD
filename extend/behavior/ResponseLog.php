<?php

namespace extend\behavior;

use extend\log\JsonTool;
use extend\log\xTrace;
use think\facade\Log;

class ResponseLog
{
    public function handle($response)
    {
        if (!request()->isCli()) {
            //链路日志
            $xTrace                = xTrace::instance();
            $downSpan              = $xTrace->pushDown(['span_name' => 'backend.php.tp6']);
            $getResponse           = $xTrace->getResponse($response);
            $logTrace['_RESPONSE'] = $getResponse;
            $logTrace['_TRACE']    = $downSpan;

            //返回给前端request_id,形成链路闭环.
            $data    = $response->getData();
            $request = ['request-id' => $downSpan['trace_id'] ?? ''];
            $data    = array_merge($request, $data);
            $response->data($data);

            Log::alert('[RESPONSE_END][X-TRACE]' . PHP_EOL . JsonTool::fString($logTrace));
        }
    }

}