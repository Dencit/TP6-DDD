<?php

namespace domain\base\repository;

use extend\utils\QueryMatch;
use think\facade\Db;

class BaseRepository
{
    //继承类覆盖的模型class
    protected $model = null;
    //当前模型引用
    protected static $query = null;
    //当前单例
    protected static $instance = null;
    //当前筛选字段集合
    protected static $fields = null;
    //当前单例类型
    protected static $instanceType; //0-纯动态,1-静态注入动态,2-纯静态

    public function __construct(array $input = null, $callStatic = 0)
    {
        //重新设置
        self::$fields = null;
        self::$instanceType = $callStatic;
        //
        switch ($callStatic) {
            default: //引用 动态实例 - 包含继承函数
                if (!empty($input)) {
                    self::$query = (new $this->model($input)); // new modelClass($input)
                } else {
                    self::$query = (new $this->model()); // new modelClass()
                }
                break;
            case 1: //引用 源模型 动态实例 - 不包含继承函数
                self::$query = $this->model::withoutGlobalScope();
                if(!empty($input)){
                    $fields = $input;
                    self::$query->field($fields); // modelClass::select(['*'])
                }
                break;
            case 2: //引用 源模型 静态类
                self::$query = $this->model; // modelClass::class
                break;
        }
    }

    //仓储单例 - 仓储模型 和 源模型 实例化, 不能调用间接实例化的函数.
    public static function newInstance(array $fields = null)
    {
        self::$instance = new static($fields, 0);
        return self::$instance;
    }

    //融合单例 - 仓储模型 和 源模型 融合, 共享函数(实例,静态,间接实例).
    public static function searchInstance(array $fields = null)
    {
        if (!(self::$instance instanceof static)) {
            self::$instance = new static($fields, 1);
        }
        return self::$instance;
    }

    //源模型静态引用 - 仓储模型 和 源模型, 只能调用静态函数.
    public static function sourceInstance(array $fields = null)
    {
        self::$instance = new static($fields, 2);
        return self::$instance;
    }


    //转接不存在的动态函数 到 源模型上
    public function __call($name, $arguments)
    {
        if (!empty($arguments[0]) && !empty($arguments[1]) && !empty($arguments[2])) {
            return self::$query->{$name}($arguments[0], $arguments[1], $arguments[2]);

        } else if (!empty($arguments[0]) && !empty($arguments[1])) {
            return self::$query->{$name}($arguments[0], $arguments[1]);

        } else if (!empty($arguments[0])) {
            return self::$query->{$name}($arguments[0]);

        } else {
            return self::$query->{$name}();

        }
    }

    //转接不存在的静态函数 到 源模型上
    public static function __callStatic($name, $arguments)
    {
        //源模型静态引用
        self::sourceInstance();
        if (!empty($arguments[0]) && !empty($arguments[1])) {
            return self::$query::{$name}($arguments[0], $arguments[1]);
        } else if (!empty($arguments[0])) {
            return self::$query::{$name}($arguments[0]);
        } else {
            return self::$query::{$name}();
        }
    }

    //控制全局查询范围
    public function withoutGlobalScope( $scope = true ){
        if($scope){
            self::$query = $this->model::withoutGlobalScope();
        }else{
            self::$query = $this->model;
        }
        if (self::$instanceType == 1) {
            self::$query->field(self::$fields);
        }
        return self::$instance;
    }

    //分页获取
    public function pageGet(QueryMatch $QM)
    {
        $query = self::$query;
        $collect = [];

        $QM->pagination($per_page, $page, $pagination, $row);
        //dd($page, $per_page, $pagination, $row);//

        //不能克隆未翻页对象,只能提前拿sql.
        $selectSQuery = $query->fetchSql()->select();

        //执行步进翻页-获取翻页结果
        $collectArr = $query->limit($row, $per_page)->select();
        array_walk($collectArr, function (&$item) {
            $item = collect($item);
        });
        $collect['data'] = $collectArr;

        //不能克隆未翻页对象,只能提前拿sql,再执行.
        $totalQuery = self::subTotalQuery($selectSQuery);
        //dd($selectSQuery,$totalQuery??0,count($collectArr));//

        //打开翻页时,才有meta数据 且 计算总行数
        if ($pagination != 'false') {
            $meta['pagination'] = true;
            $meta['per_page'] = $per_page;
            $meta['current_page'] = $page;

            //最小化查表总计
            $tableCount = Db::query($totalQuery);
            $tableCount = $tableCount[0]['total'] ?? 0;
            //dd($collectArr[0],$tableCount);//

            $meta['last_page'] = (int)ceil($tableCount / $per_page);
            $meta['total'] = $tableCount;

            $collect['meta'] = $meta;
        }

        //附加补充数据
        if (!empty($query->addMeta)) {
            if (!empty($collect['meta'])) {
                $collect['meta'] = array_merge($collect['meta'], $query->addMeta);
            } else {
                $collect['meta'] = $query->addMeta;
            }
        }

        return $collect;
    }

    //目标数据不为空
    public static function NoEmpty($data, $key)
    {
        if (isset($data[$key]) && $data[$key] != '') {
            return true;
        }
        return false;
    }

    //查询Sql改成合计sql
    public static function subTotalQuery($selectSQuery)
    {
        $database = self::$query->getConfig()['database'];
        $totalQuery = preg_replace("/(^.*)(\s+FROM\s+){1}(.*)(\s+LIMIT\s+\d+,\d+)(.*$)/is", '$1$2`' . $database . '`.$3 $5', $selectSQuery);
        $totalQuery = "SELECT count(*) AS total FROM (".$totalQuery.") AS SUB";
        return $totalQuery;
    }

}