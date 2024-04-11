<?php

namespace domain\base\request;

use think\exception\ValidateException;
use think\Validate;

/**
 * notes: 输入验证基类
 * 内置规则: https://www.kancloud.cn/manual/thinkphp6_0/1037629
 * 场景验证: https://www.kancloud.cn/manual/thinkphp6_0/1037627
 */
class BaseRequest extends Validate
{
    /*
     * 追加数据验证的场景
     */
    protected function makeScene($scene = '')
    {
        $this->{'scene' . ucwords($scene)}();
        foreach ($this->rule as $k => &$v) {
            if (isset($this->append[$k])) {
                $str = implode('|', $this->append[$k]);
                if ($v == '') {
                    $v .= $str;
                } else {
                    $v .= '|' . $str;
                }
            }
        }
    }

    //json 验证规则
    protected function jsonCheck($value, $rule = '', $data = '', $field = '')
    {
        $data = json_decode($value, true);
        if (empty($data)) {
            return $field . ' 必须是非空的Json格式';
        }
        return true;
    }

    //验证默认
    public function checkValidate($requestInput)
    {
        if (!$this->check($requestInput)) {
            throw new ValidateException($this->getError());
        }
    }

    //验证场景
    public function checkSceneValidate($scene, $requestInput)
    {
        if (!$this->scene($scene)->check($requestInput)) {
            throw new ValidateException($this->getError());
        }
    }


}