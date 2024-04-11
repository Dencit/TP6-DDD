<?php

namespace extend\think\log\driver;

use think\log\driver\File as BaseFile;

class File extends BaseFile
{
    /**
     * 日志写入接口
     * @access public
     * @param array $log 日志信息
     * @return bool
     */
    public function save(array $log): bool
    {
        $destination = $this->getMasterLogFile();

        $path = dirname($destination);
        !is_dir($path) && mkdir($path, 0755, true);

        $info = [];

        // 日志信息封装
        $time = \DateTime::createFromFormat('0.u00 U', microtime())->setTimezone(new \DateTimeZone(date_default_timezone_get()))->format($this->config['time_format']);

        foreach ($log as $type => $val) {
            $message = [];
            foreach ($val as $msg) {
                if (!is_string($msg)) {
                    $msg = var_export($msg, true);
                }

                $message[] = $this->config['json'] ?
                    json_encode(['time' => $time, 'type' => $type, 'msg' => $msg], $this->config['json_options']) :
                    sprintf($this->config['format'], $time, $type, $msg);
            }

            if (true === $this->config['apart_level'] || in_array($type, $this->config['apart_level'])) {
                // 独立记录的日志级别
                $filename = $this->getApartLevelFile($path, $type);
                $this->write($message, $filename);
                continue;
            }

            $info[$type] = $message;
        }

        if ($info) {
            return $this->write($info, $destination);
        }

        return true;
    }
}
