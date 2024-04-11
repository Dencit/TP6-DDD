<?php

namespace domain\demo\port\request;

use domain\base\request\BaseRequest;

/**
 * notes: 数据单元 输入验证器
 * doc: https://laravelacademy.org/post/9547
 * @author 陈鸿扬 | @date 2021/2/3 10:01
 */
class EsSampleRequest extends BaseRequest
{
    //验证规则
    protected $rules = [
        //@rules
        "id"  => "integer|gt:0",
        "name"=>"string|between:0,255",
        "type"=>"integer|in:1,2",
        "status"=>"integer|in:1,2",
        //@rules
    ];

    //修改 验证项错误 返回描述
    protected $message = [
//        "id.require" => "id 不能为空",
//        "name.length" => "name 字符长度在0-255之间",
//        "id.gt" => "id 必须大于0",
//        "type.in"   => "类型 在0-1之间",
//        "status.in" => "状态 在0-1之间",
    ];

    //edit 验证场景 定义方法
    //例子: $this->only(['name','age']) ->append('name', 'min:5') ->remove('age', 'between') ->append('age', 'require|max:100');
    public function sceneTableSave(){
        //return $this->append('name', 'require');
    }
    public function sceneSave(){
        //return $this->append('id', 'require')->append('name', 'require');
    }
    public function sceneUpdate(){
        //return $this->append('id', 'required');
    }
    public function sceneRead(){
        //return $this->append('id', 'required');
    }
    public function sceneDelete(){
        //return $this->append('id', 'required');
    }

}