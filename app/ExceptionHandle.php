<?php
namespace app;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\facade\Config;
use think\facade\Log;
use think\facade\Request;
use think\Response;
use Throwable;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request   $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        // 添加自定义异常处理机制

        $msg=[
            "error"=>$e->getMessage(),
            "message"=>$e->getMessage(),
            "code"=>$e->getCode()
        ];

        // 参数验证错误
        if ($e instanceof ValidateException) {
            $msg['error']=$e->getError();
            $msg=$this->isDebugMsg($e,$msg);

            return json( $msg ,422 );
        }

        // http 请求异常
        if ($e instanceof HttpException ) {
            $msg['code']=$e->getStatusCode();
            $msg=$this->isDebugMsg($e,$msg);

            return json( $msg ,400 );
        }

        // http ajax请求异常
        if ( $e instanceof HttpException && request()->isAjax() ) {
            $msg['code']=$e->getStatusCode();
            $msg=$this->isDebugMsg($e,$msg);

            return json( $msg ,400 );
        }

        //详细的异常日志
        $this->setErrorLog($e);

        //其他错误 默认数据结构
        $msg=$this->isDebugMsg($e,$msg);
        return json( $msg ,400 );

        // 其他错误交给系统处理
        //return parent::render($request, $e);
    }


    public function isDebugMsg($e,$msg){
        $debug = Config::get('app.app_debug');
        if($debug){
            $msg['file']=$e->getFile();
            $msg['line']=$e->getLine();
            $msg['trace']=$e->getTrace();
        }
        return $msg;
    }

    //详细的异常日志
    public function setErrorLog($e){
        $error = [
            'request'=>[
                'method' => Request::method(),
                'header' => json_encode(Request::header()),
                'url' => Request::url(),
                'param' => json_encode(Request::param()),
                'controller' => Request::controller(),
                'action' => Request::action()
            ],
            'error'=>[
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]
        ];
        Log::write($error,'error');
    }

}
