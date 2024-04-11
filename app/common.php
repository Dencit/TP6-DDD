<?php

//应用公共文件
use extend\log\backTrace;

if (!function_exists('bt')) {
    function bt($filter = '', $debug = false)
    {
        $finalArr = backTrace::run($filter, $debug);
        header('content-type:application/json');
        exit(json_encode($finalArr));
    }
}