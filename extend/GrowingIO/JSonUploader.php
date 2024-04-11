<?php

namespace extend\GrowingIO;

class JSonUploader
{
    private $accountId;
    private $curl;
    public function __construct($options)
    {
        $this->accountId = $options["accountId"];

    }

    protected function currentMillisecond() {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectime;
    }

    public function uploadEvents($events = array())
    {
        $curl= curl_init();
        $data = json_encode($events);
        //printf("request url: https://api.growingio.com/v3/{$this->accountId}/s2s/cstm\n");

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "443",
            CURLOPT_URL => "https://api.growingio.com/v3/{$this->accountId}/s2s/cstm?stm=".$this->currentMillisecond(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json"
            ),
            CURLOPT_SSL_VERIFYPEER => false,
        ));
        $response = curl_exec($curl);
        if (false === $response) {
            $curl_error = curl_error($curl);
            $curl_errno = curl_errno($curl);
            curl_close($curl);
            printf("errno:[".$curl_errno."] error:[".$curl_error."]\n");
            return false;
        } else {
            curl_close($curl);
        }
    }

}