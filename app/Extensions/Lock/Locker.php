<?php

namespace App\Extensions\Lock;

use Illuminate\Contracts\Cache\LockTimeoutException;

class Locker implements ILock
{

    /**
     * @var ILock
     */
    public $_locker;

    /**
     * @var int
     */
    public $_waitLockIntervalMicroSeconds = 20000;

    /**
     * @var int
     */
    public $_maxWaitMicroSeconds = 2000000;

    /**
     * @var int
     */
    public $_lockTime = 3;

    /**
     * @var bool
     */
    public $_blocking = false;

    public function __construct(ILock $locker, $blocking = true, $lockTime = 3)
    {
        $this->_locker = $locker;
        $this->_blocking = $blocking;
        $this->_lockTime = $lockTime;
    }

    public function GetLock($key, $block = true, $time = 3): bool
    {
        return $this->_locker->GetLock($key, $block, $time);
    }

    public function ReleaseLock($key)
    {
        return $this->_locker->ReleaseLock($key);
    }

    public function WithLock($key, callable $func)
    {
        $r = true;
        try {
            $waited = 0;
            $isLocked = false;

            do {
                $isLocked = $this->_locker->GetLock(
                    $key,
                    $this->_blocking,
                    $this->_lockTime
                );
                if(!$isLocked)
                {
                    usleep($this->_waitLockIntervalMicroSeconds);
                    $waited += $this->_waitLockIntervalMicroSeconds;
                }
                else
                    break;
            } while ($this->_blocking && $waited <= $this->_maxWaitMicroSeconds);

            $r = $isLocked;
            if ($isLocked)
                $func();
        } catch (\Exception $ex) {
            $r = false;
            \Log::error($ex->getMessage());
        } finally {
            $this->_locker->ReleaseLock($key);
        }
        return $r;

    }
}
