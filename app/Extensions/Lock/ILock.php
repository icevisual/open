<?php
namespace App\Extensions\Lock;

interface ILock {

    public function GetLock($key,$block=true, $time=3) :bool ;

    public function ReleaseLock($key);

}