<?php

namespace domain\oauth\model;

use domain\base\model\BaseModel;
use think\model\concern\SoftDelete;

/**
 * notes: 领域层-模型类
 * 说明: 负责基础层的工作,字段过滤(模型黑白名单),用户权限(模型策略),触发器(模型事件),等一系列传统DBA负责的工作.
 */
class OauthRoleModel extends BaseModel
{
    // 设置当前模型的数据库连接
    protected $connection = 'mysql';

    // 模型名（默认为当前不含后缀的模型类名）
    protected $name = 'oauth_roles';

    // 主键名（默认为id）
    protected $pk = 'id';
    //自动写入创建和更新的时间戳字段（默认关闭）
    protected $autoWriteTimestamp = true;          // true/false/datetime
    protected $createTime = 'create_time'; // false/{string}/create_time
    protected $updateTime = 'update_time'; // false/{string}/update_time
    //启用软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time'; // false/{string}/delete_time
    protected $defaultSoftDelete = null;          //默认值: 0-未删除, 1-已删除

    // 设置json类型字段
    protected $json = ['info'];

    //字段设置类型自动转换
    protected $type = [
        //@types
        "id"          => "bigint",
        "role"        => "varchar",
        "role_id"     => "varchar",
        "create_time" => "datetime",
        "update_time" => "datetime",
        "delete_time" => "datetime",
        //@types
    ];

    //更新排除
    protected $readonly = [
        //@guarded
        "id",
        "create_time",
        //@guarded
    ];

    //输出过滤
    public static $hiddenRuler = [
        'delete_time',
    ];

    //定义全局的查询范围
    protected function base(&$query)
    {
        $query->where(['delete_time' => $this->defaultSoftDelete]);
    }

    //模型事件
    //读取后
    public static function onAfterRead($model){ }
    //新增前
    public static function onBeforeInsert($model){  }
    //新增后
    public static function onAfterInsert($model){   }
    //更新前
    public static function onBeforeUpdate($model){  }
    //更新后
    public static function onAfterUpdate($model){   }
    //写入前
    public static function onBeforeWrite($model){   }
    //写入后
    public static function onAfterWrite($model){    }
    //删除前
    public static function onBeforeDelete($model){  }
    //删除后
    public static function onAfterDelete($model){   }
    //恢复前
    public static function onBeforeRestore($model){ }
    //恢复后
    public static function onAfterRestore($model){  }

    /*
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'id', 'id')->field('id,sex');
    }
    */


}