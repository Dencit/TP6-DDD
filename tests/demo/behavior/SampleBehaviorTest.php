<?php
namespace tests\demo\behavior;

use PHPUnit\Framework\TestResult;
use extend\thinktest\base\behavior\BaseBehavior;

class SampleBehaviorTest extends BaseBehavior {
    protected $mainTable = 'samples';
    protected $baseHost;
    protected $stack;
    protected $service;

    //基境初始化
    public static function setUpBeforeClass():void{
    }
    protected function setUp():void{
        $this->stack = [];
        $this->baseHost = config('app.app_host');
        $this->service = null;
    }
    public static function tearDownAfterClass():void{
    }
    protected function tearDown():void{
        $this->stack = [];
        $this->baseHost = null;
        $this->service = null;
    }
    //#


    //测试用例
    /**
     * 生产者
     * @return \think\Model|static
     */
    //{@hidden
    public function testSampleCreate(){

        $startTime = strtotime( date('Y-m-d').' -1 day -59 second' ); $endTime = strtotime( date('Y-m-d').' -60 second' );
        $createTime= $this->betweenTime($startTime,$endTime);

        $insertData = [
            //@in_list
            ['name'=>'未知','nick_name'=>'11','mobile'=>'18500000000','photo'=>'','sex'=>0,'type'=>0,'status'=>0,'create_time'=>$createTime],
            ['name'=>'张三','nick_name'=>'33','mobile'=>'18500000001','photo'=>'','sex'=>1,'type'=>1,'status'=>1,'create_time'=>$createTime],
            ['name'=>'李四','nick_name'=>'44','mobile'=>'18500000002','photo'=>'','sex'=>2,'type'=>1,'status'=>1,'create_time'=>$createTime],
            //@in_list
        ];

        $result = $this->tableSaveOrFailAll($this->mainTable,$insertData);
        $ids = array_column($result->toArray(),'id');

        $this->tableMergeTempIds( $this->mainTable, $ids );

        $this->assertTrue( (count($result->toArray())>0) );
        return $result;
    }
    //@hidden}


    /**
     * 结束测试
     */
    public function testEnd(){
        $this->tableCleanByArgv($this->mainTable,'clean','mysql');

        $this->assertTrue(true);
    }

    //抽象类继承 必须实现方法
    public function count(): int{
        return parent::count();
    }
    public function toString(): string{
        return parent::toString();
    }
    public function run(TestResult $result = null): TestResult{
        return parent::run($result);
    }

}