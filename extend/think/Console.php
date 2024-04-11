<?php

namespace extend\think;

use think\App;
use think\Console as BaseConsole;
use think\console\Input;
use think\console\Output;
use think\facade\Log;

class Console extends BaseConsole
{

    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    /**
     * 执行当前的指令
     * @access public
     * @return int
     * @throws \Exception
     * @api
     */
    public function run()
    {
        $input  = new Input();
        $output = new Output();

        $this->configureIO($input, $output);

        try {
            $exitCode = $this->doRun($input, $output);
        } catch (\Exception $e) {
            if (!$this->catchExceptions) {
                throw $e;
            }

            $output->renderException($e);

            $exitCode = $e->getCode();
            if (is_numeric($exitCode)) {
                $exitCode = (int)$exitCode;
                if (0 === $exitCode) {
                    $exitCode = 1;
                }
            } else {
                $exitCode = 1;
            }
        }

        //因为是异步执行,直接标记结束分割线. 另外依赖在"队列or指令"类内(COMMAND_START,QUEUE_START),添加"入点出点"分割日志来区分.
        //块日志-结束分割线
        $log  = '[CONSOLE_END]';
        $line = $log . PHP_EOL . '---------------------------------------------------------------';
        Log::alert($line);

        if ($this->autoExit) {
            if ($exitCode > 255) {
                $exitCode = 255;
            }
            exit($exitCode);
        }

        return $exitCode;
    }


}
