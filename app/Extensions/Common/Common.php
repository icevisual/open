<?php
namespace App\Extensions\Common;

class Common
{

    public static function debug($message, array $data = [])
    {
        return \App\Services\Log\ServiceLog::record('debug', $message, $data, 'debug');
    }
}