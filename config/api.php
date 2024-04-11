<?php

// +----------------------------------------------------------------------
// | api 接口的一些配置
// +----------------------------------------------------------------------

return [
    
    //growingio 第三方 埋点统计
    'gio' => [
        //统计项目id
        'account_id' => env('GIO.ACCOUNT_ID', null),
        //公匙
        'product_key' => env('GIO.PRODUCT_KEY', null),
        //私匙
        'private_key' => env('GIO.PRIVATE_KEY', null),
        //api token
        'token' => env('GIO.TOKEN', null),
    ]

];