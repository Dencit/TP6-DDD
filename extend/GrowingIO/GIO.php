<?php

namespace extend\GrowingIO;


class GIO
{

    protected static $gio;

    public static function instance( $options = null){
        $accountID = config('api.gio.account_id');

        if($options==null){ $options=[]; }
        self::$gio = GrowingIO::getInstance($accountID, $options);

        return self::$gio;
    }

}