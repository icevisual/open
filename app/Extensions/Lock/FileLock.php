<?php

namespace App\Extensions\Lock;

class FileLock implements ILock
{
    /**
     * @var resource
     */
    private $fp;

    private $_lockFileDir = 'lock';

    public function __construct()
    {
        if(!file_exists(storage_path($this->_lockFileDir)))
        {
            @mkdir(storage_path($this->_lockFileDir));
        }
    }

    public function GetLock($key, $block = true, $time = 3): bool
    {
        $this->fp = fopen(storage_path('lock/'.md5($key)), "w+");
        if ($block)
            return flock($this->fp, LOCK_EX);
        else
            return flock($this->fp, LOCK_EX | LOCK_NB);
    }

    public function ReleaseLock($key)
    {
        flock($this->fp, LOCK_UN);
        // TODO: Implement ReleaseLock() method.
    }
}