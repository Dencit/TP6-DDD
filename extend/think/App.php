<?php

namespace extend\think;

use think\App as AppBase;

class App extends AppBase
{

    public function __construct(string $rootPath = '')
    {
        parent::__construct($rootPath);
    }

    /**
     * 容器绑定标识
     * @var array
     */
    protected $bind = [
        'app'                     => \extend\think\App::class, //改绑定
        'console'                 => \extend\think\Console::class, //改绑定
        'cache'                   => \think\Cache::class,
        'config'                  => \think\Config::class,
        'cookie'                  => \think\Cookie::class,
        'db'                      => \think\Db::class,
        'env'                     => \think\Env::class,
        'event'                   => \think\Event::class,
        'http'                    => \think\Http::class,
        'lang'                    => \think\Lang::class,
        'log'                     => \think\Log::class,
        'middleware'              => \think\Middleware::class,
        'request'                 => \think\Request::class,
        'response'                => \think\Response::class,
        'route'                   => \think\Route::class,
        'session'                 => \think\Session::class,
        'validate'                => \think\Validate::class,
        'view'                    => \think\View::class,
        'filesystem'              => \think\Filesystem::class,
        'think\DbManager'         => \think\Db::class,
        'think\LogManager'        => \think\Log::class,
        'think\CacheManager'      => \think\Cache::class,

        // 接口依赖注入
        'Psr\Log\LoggerInterface' => \think\Log::class,
    ];

}