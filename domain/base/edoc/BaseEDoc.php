<?php
namespace domain\base\edoc;

use extend\elastic\EsOrm;

/**
 * notes: ES文档-模型基础类
 * @author 陈鸿扬 | @date 2022/6/8 11:37
 */
class BaseEDoc
{
    //单例
    private static $instance;

    //ES-table
    protected $esTable = null;

    //索引表版本号
    protected static $version = 0;

    //执行模型是否自动维护时间戳
    protected $timestamps = false;
    const CREATED_AT = null;
    const UPDATED_AT = null;

    //ES查询单例
    protected static $esQuery = null;

    public function __construct($version=0)
    {
        //连接ES查询
        $esOrm = EsOrm::init(); //必须重复初始化 - 防继承子类单例变量污染
        //不再区分 index/type 一律同名.
        $table = $this->esTable;

        //索引版本号
        if (!empty($version)) {
            $table .= '-' . $version;
        }
        self::$esQuery = $esOrm->table($table);
    }

    //获取数据库字段
    public function getFieldKeys()
    {
        $fieldKeys=[];
        if (!empty($this->casts)) {
            //casts
            $fieldKeys = array_keys($this->casts);
        }
        return $fieldKeys;
    }

    //重复初始化
    public static function init($version = 0)
    {
        self::$instance = new static($version);
        return self::$instance;
    }

    //不重复初始化 - 单例
    public static function instance($version = 0)
    {
        if (!self::$instance instanceof static) {
            self::$instance = new static($version);
        }

        return self::$instance;
    }

    //魔术方法过滤器
    public function callFilter($name,&$arguments){
        //提交数据预处理
        if( !empty($arguments[0]) ) {
            switch ($name) {
                case 'add':
                    $this->dataItemFilter('create',$arguments[0]);
                    break;
                case 'addAll':
                    $this->dataListFilter('create',$arguments[0]);
                    break;
                case 'save':
                    $this->dataItemFilter('create',$arguments[0]);
                    break;
                case 'saveAll':
                    $this->dataListFilter('create',$arguments[0]);
                    break;
            }
        }
        //dd($arguments[0]);//
    }

    //静态初始化 - 每个新继承子类 调用自己不存在的方法, 都会重置ES单例.
    public static function __callStatic($name, $arguments)
    {
        self::init();
        $argCount  = count($arguments);
        switch ($argCount){
            default:
                $query = self::$esQuery->{$name}();
                break;
            case 1:
                $query = self::$esQuery->{$name}($arguments[0]);
                break;
            case 2:
                $query = self::$esQuery->{$name}($arguments[0],$arguments[1]);
                break;
            case 3:
                $query = self::$esQuery->{$name}($arguments[0], $arguments[1], $arguments[2]);
                break;
            case 4:
                $query = self::$esQuery->{$name}($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
                break;
        }
        return $query;
    }

    //动态初始化 - 每个新继承子类,连贯操作,调用自己不存在的方法,会转到顶层方法.
    public function __call($name, $arguments)
    {
        //初始化
        self::instance();
        //魔术方法过滤器
        self::callFilter($name,$arguments);
        //
        $argCount  = count($arguments);
        switch ($argCount){
            default:
                $query = self::$esQuery->{$name}();
                break;
            case 1:
                $query = self::$esQuery->{$name}($arguments[0]);
                break;
            case 2:
                $query = self::$esQuery->{$name}($arguments[0],$arguments[1]);
                break;
            case 3:
                $query = self::$esQuery->{$name}($arguments[0], $arguments[1], $arguments[2]);
                break;
            case 4:
                $query = self::$esQuery->{$name}($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
                break;
        }
        return $query;
    }

    //单行数据处理
    public function dataItemFilter( string $action, &$item)
    {
        if (!empty($item) && !empty($this->casts)) {
            //模型字段过滤
            $casts = $this->casts;
            $diffCasts = array_intersect_key($casts, $item);
            //当前时间
            $dateTime = date("Y-m-d H:i:s", time());

            //自动插入操作时间
            if ($action == 'create' && $this->timestamps &&
                !empty(static::CREATED_AT) && !empty(static::UPDATED_AT)) {
                $item[static::CREATED_AT] = $dateTime;
                $item[static::UPDATED_AT] = $dateTime;
            }
            if ($action == 'update' && $this->timestamps && !empty(static::UPDATED_AT)) {
                $item[static::UPDATED_AT] = $dateTime;
            }

            //模型字段过滤
            foreach ($item as $key => &$value) {

                //清除NULL值
                if ($value === null) {
                    unset($item[$key]);
                }

                //空值过滤
                if (isset($diffCasts[$key])) {
                    $type = $diffCasts[$key];
                    if (empty($value)) {
                        $this->emptyValueFilter($value, $type);
                    } else {
                        //处理文本浮点数0
                        preg_match('/^0\.\d+$/', $value, $m);
                        if (isset($m[0])) {
                            $this->emptyValueFilter($value, $type);
                        }
                        //处理文本日期 0000-00-00 00:00:00
                        preg_match('/^(\d{4}-\d{2}-\d{2}).*$/', $value, $m);
                        if (isset($m[0])) {
                            $value = date('Y-m-d H:i:s',strtotime($value));
                        }
                    }
                }

            }
        }
    }

    //多行数据处理
    public function dataListFilter(string $action, &$data)
    {
        if( !empty($data) && !empty($this->casts) ){
            //模型字段过滤
            $casts = $this->casts;
            $diffCasts = array_intersect_key($casts,$data[0]);
            //当前时间
            $dateTime = date("Y-m-d H:i:s",time());
            //
            array_walk($data, function (&$item) use ($action,$diffCasts,$dateTime) {

                //自动插入操作时间
                if ($action == 'create' && $this->timestamps &&
                    !empty(static::CREATED_AT) && !empty(static::UPDATED_AT)) {
                    $item[static::CREATED_AT] = $dateTime;
                    $item[static::UPDATED_AT] = $dateTime;
                }
                if ($action == 'update' && $this->timestamps && !empty(static::UPDATED_AT)) {
                    $item[static::UPDATED_AT] = $dateTime;
                }

                //模型字段过滤
                foreach ($item as $key => &$value) {

                    //清除NULL值
                    if ($value === null) {
                        unset($item[$key]);
                    }

                    //空值过滤
                    if (isset($diffCasts[$key])) {
                        $type = $diffCasts[$key];
                        if (empty($value)) {
                            $this->emptyValueFilter($value, $type);
                        } else {
                            //处理文本浮点数0
                            preg_match('/^0\.\d+$/', $value, $m);
                            if (isset($m[0])) {
                                $this->emptyValueFilter($value, $type);
                            }
                            //处理文本日期 0000-00-00 00:00:00
                            preg_match('/^(\d{4}-\d{2}-\d{2}).*$/', $value, $m);
                            if (isset($m[0])) {
                                $value = date('Y-m-d H:i:s', strtotime($value));
                            }
                        }
                    }

                }

            });
        }
    }

    //多行数据查询
    public function getDataFilter(string $action, &$data)
    {
        if( !empty($data) && !empty($this->casts) ){
            //模型字段过滤
            $casts = $this->casts;
            $diffCasts = array_intersect_key($casts,$data[0]);
            //
            array_walk($data, function (&$item) use ($action,$diffCasts) {

                //模型字段过滤
                foreach ($item as $key => &$value) {

                    //空值过滤
                    if (isset($diffCasts[$key])) {
                        $type = $diffCasts[$key];
                        dd($type);
                        if (empty($value)) {
                            $this->emptyValueFilter($value, $type);
                        } else {
                            //处理文本浮点数0
                            preg_match('/^0\.\d+$/', $value, $m);
                            if (isset($m[0])) {
                                $this->emptyValueFilter($value, $type);
                            }
                            //处理文本日期 0000-00-00 00:00:00
                            preg_match('/^(\d{4}-\d{2}-\d{2}).*$/', $value, $m);
                            if (isset($m[0])) {
                                $value = date('Y-m-d H:i:s', strtotime($value));
                            }
                        }
                    }

                }

            });
        }
    }

    //空值过滤
    protected function emptyValueFilter(&$value,$type){
        switch ($type){
            case 'date' :
                $value = NULL; break;
            case 'text' :
                $value = (string) $value ; break;
            case 'integer' :
                $value = (int) $value ; break;
            case 'float' :
                $value = (float) $value ; break;
        }
    }

}