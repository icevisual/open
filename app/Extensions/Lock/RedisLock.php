<?php

namespace App\Extensions\Lock;

class RedisLock implements ILock
{
    public function GetLock($key,$block=true, $time = 3):bool
    {
        // not block
        $r = \LRedis::SET($key,"1","NX","EX",$time);
        if($r)
            return true;
        return false;
    }

    public function ReleaseLock($key)
    {
        \LRedis::DEL($key);
    }
}
