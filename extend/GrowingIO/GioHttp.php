<?php

namespace extend\GrowingIO;

class GioHttp
{

    public static function get($url,$header=null){

        $curl= curl_init();

        $curlHeader = ["content-type: application/json"];
        if(!empty($header)){
            $curlHeader=[];
            foreach ($header as $k => $v) {
                $curlHeader[] = $k . ": " . $v;
            }
        }

        $option = [
            CURLOPT_PORT => "443",
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $curlHeader,
            CURLOPT_SSL_VERIFYPEER => false,
        ];
        curl_setopt_array($curl,$option);

        $output = curl_exec($curl);
        $curlInfo=curl_getinfo($curl);

        curl_close($curl);
        
        if(!empty($output)){ $output = json_decode($output,true); }
        return $output;
    }

    public static function post($url,$data='',$header=null){

        if(gettype($data)=='array'){ $data = json_encode($data); }

        $curlHeader = ["content-type: application/json"];
        if(!empty($header)){
            $curlHeader=[];
            foreach ($header as $k => $v) {
                $curlHeader[] = $k . ": " . $v;
            }
        }

        $option = [
            CURLOPT_PORT => "443",
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $curlHeader,
            CURLOPT_SSL_VERIFYPEER => false,
        ];
        $curl= curl_init();
        curl_setopt_array($curl,$option);

        $output = curl_exec($curl);
        $curlInfo=curl_getinfo($curl);

        curl_close($curl);

        if(!empty($output)){ $output = json_decode($output,true); }
        return $output;
    }


}