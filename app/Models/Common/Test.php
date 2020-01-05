<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{

    protected $table = "test_cur";
    public $timestamps = false;
    public $guarded = [];

    public function ReleaseLock()
    {
        $key = "prefix--key";
        \LRedis::DEL($key);
    }

    public function GetLock()
    {
        $key = "prefix--key--lock";
        $r = \LRedis::SET($key,"1","NX","EX",3);
        if($r)
            return true;
        return false;
    }

    public function WithLock(callable $func)
    {
        $r = true;
        try {
            while(!$this->GetLock()) sleep(0.02);
            //$this->GetLock();
            $func();
        }
        catch(\Exception $ex)
        {
            $r = false;
            \Log::error($ex->getMessage());
        }
        finally {
            $this->ReleaseLock();
        }
        return $r;
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
                    $newID = $last_id;
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
        \Log::info("LastID=" . $this->cacheLastID());
        return;
        return $this->CreateNew0($name);
//        \DB::update("SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE;");
//        \DB::beginTransaction();
    }
    //
}
