<?php

namespace App\Extensions\Lock;

class MySQLLock implements ILock
{
    public function GetLock($key, $block = true, $time = 3): bool
    {
        // block when getting lock
        $result = \DB::select("SELECT GET_LOCK('" . $key . "'," . $time . ") AS MyLOCK ");
        return intval($result[0]->MyLOCK) == 1;
    }

    public function ReleaseLock($key)
    {
        \DB::select("SELECT RELEASE_LOCK('" . $key . "') ");
    }
}
