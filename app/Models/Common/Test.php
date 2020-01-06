<?php

namespace App\Models\Common;

use App\Extensions\Lock\Locker;
use App\Extensions\Lock\FileLock;
use App\Extensions\Lock\MySQLLock;
use App\Extensions\Lock\RedisLock;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{

    protected $table = "test_cur";
    public $timestamps = false;
    public $guarded = [];

    public function WithLock(callable $func)
    {
        return resolve(\App\Extensions\Lock\Locker::class)->WithLock("prefix--key--lock",$func);
    }

    public function cacheLastID()
    {
        $newID = 0;
        $last_id = \LRedis::GET("LastID");
        if(!$last_id)
        {
            $exec_r = $this->WithLock(function() use(&$newID) {
                $last_id = \LRedis::GET("LastID");
                if($last_id)
                {
                    $newID = intval(\LRedis::INCR("LastID"));
                    return;
                }
                \Log::info("<=LastID=>");
                $last = self::orderBy("id","desc")->first();
                if($last)
                {
                    $newID = $last['id'] + 1;
                }
                else
                {
                    $newID = 1;
                }
                \LRedis::SET("LastID",$newID);
            });
            if(!$exec_r)
            {
                \Log::info("\$exec_r = " . $exec_r);
            }
        }
        else
        {
            $newID = intval(\LRedis::INCR("LastID"));
        }
        return $newID;
    }


    public function CreateNew1($name)
    {
        $last = self::orderBy("id","desc")->first();
        if($last)
        {
            $newID = $last['id'] + 1;
        }
        else
        {
            $newID = 1;
        }
        $r = self::create([
            'id' => $newID,
            'name' => $name,
            'created_at' => now()
        ]);
        return $r;
    }

    public function CreateNew2($name)
    {
        $r = 0;

        $exec_r = $this->WithLock(function() use($name,&$r) {
            $last = self::orderBy("id","desc")->first();
            if($last)
            {
                $newID = $last['id'] + 1;
            }
            else
            {
                $newID = 1;
            }
            $r = self::create([
                'id' => $newID,
                'name' => $name,
                'created_at' => now()
            ]);
        });

        if(!$exec_r)
            return false;
        return $r;
    }



    public function CreateNew3($name)
    {
        $newID = $this->cacheLastID();
        $r = self::create([
            'id' => $newID,
            'name' => $name,
            'created_at' => now()
        ]);
        return $r;
    }



    public function CreateNew0($name)
    {
        // 140 concur
        $r = self::create([
            'name' => $name,
            'created_at' => now()
        ]);
        return $r;
    }



    public function CreateNew($name)
    {
        return $this->CreateNew3($name);
        //\Log::info("LastID=" . $this->cacheLastID());
        \Log::info("LastID=" . \LRedis::INCR("KKKKKK"));
        return;
        return $this->CreateNew0($name);
//        \DB::update("SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE;");
//        \DB::beginTransaction();
    }
    //
}
