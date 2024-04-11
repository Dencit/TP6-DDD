<?php

namespace extend\GrowingIO;


class GioApi{

    private static $_instance = null;

    public static function instance( $options = []){

        $accountID = config('api.gio.account_id');

        if(self::$_instance == null) {
            self::$_instance = new self($accountID, $options);
        }
        return self::$_instance;
    }


    /*
     * 获取看板列表
     */
    public static function dashboards($projectUid){

        $url="https://www.growingio.com/projects/${projectUid}/dashboards.json";
        $header=[
            'content-type'=>'application/json',
            'Authorization'=>  self::getAuthorization($projectUid)
        ];
        $result = GioHttp::get($url,$header);
        return $result;

    }

    /*
     * 获取看板中的图表信息
     */
    public static function charts($projectUid,$dashboardId){

        $url="https://www.growingio.com/projects/".$projectUid."/dashboards/".$dashboardId.".json?tm=".self::currentMillisecond();
        $header=[
            'content-type'=>'application/json',
            'Authorization'=>  self::getAuthorization($projectUid)
        ];
        $result = GioHttp::get($url,$header);
        return $result;

    }


    /*
     * 获取事件分析数据
     */
    public static function event($projectUid,$chartId,$interval=null,$startTime=null,$endTime=null){

        $url="https://www.growingio.com/v2/projects/${projectUid}/charts/${chartId}.json";
        $query='';
        if( $interval ){ $query.='&interval='.$interval; }
        if( $startTime && $endTime ){
            $query.='&startTime='.self::getDateToMesc($startTime.'.000');
            $query.='&endTime='.self::getDateToMesc($endTime.'.999');
        }
        if(!empty($query)){ $url.='?'.trim($query,'&'); }

        $header=[
            'content-type'=>'application/json',
            'Authorization'=>  self::getAuthorization($projectUid)
        ];
        $result = GioHttp::get($url,$header);
        return $result;

    }




    /*
     * 申请认证码
     */
    public static function getAuthorization($projectUid){

        $accountID = config('api.gio.account_id');
        $productKey = config('api.gio.product_key');
        $privateKey = config('api.gio.private_key');
        $tm = self::currentMillisecond();
        //$tm = time();
        $authToken = self::authToken($privateKey,$projectUid,$accountID,$tm);

        $url = 'https://www.growingio.com/auth/token';
        $header=[ 'X-Client-Id'=>$productKey, 'content-type'=>'text/plain'];

        $data = [
            'project'=>$projectUid,
            'ai'=>$accountID,
            'tm'=>$tm,
            'auth'=>$authToken
        ];
        $str='';
        foreach ($data as $k=>$v){ $str.= '&'.$k.'='.$v; }
        $str = trim($str,'&');

        $result = GioHttp::post($url,$str,$header);

        $code = null;
        if( isset( $result['status'] ) && $result['status']=='success' ){
            $code = $result['code'];
        }
        return $code;
    }

    /*
     * 生成加密签名auth
     */
    public static function authToken($secret, $project, $ai, $tm) {
        $str = "POST\n/auth/token\nproject=${project}&ai=${ai}&tm=${tm}";
        $signature = hash_hmac("sha256", $str, $secret);
        return $signature;
    }

    /*
     * 生成 microtime 时间戳
     */
    protected static function currentMillisecond() {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectime;
    }

    /*
     * 毫秒转日期
     */
    public static function getMsecToMescdate($msectime)
    {
        $msectime = $msectime * 0.001;
        if(strstr($msectime,'.')){
            sprintf("%01.3f",$msectime);
            list($usec, $sec) = explode(".",$msectime);
            $sec = str_pad($sec,3,"0",STR_PAD_RIGHT);
        }else{
            $usec = $msectime;
            $sec = "000";
        }
        $date = date("Y-m-d H:i:s.x",$usec);
        return $mescdate = str_replace('x', $sec, $date);
    }

    /*
     * 日期转毫秒
     */
    public static function getDateToMesc($mescdate)
    {
        list($usec, $sec) = explode(".", $mescdate);
        $date = strtotime($usec);
        $return_data = str_pad($date.$sec,13,"0",STR_PAD_RIGHT);
        return $msectime = $return_data;
    }

}