<?php

namespace App\Contracts;
use Illuminate\Contracts\Filesystem\Filesystem;

class UserProvider implements UserContract
{
    public $uid;

    public $fs;

    public function __construct(Filesystem $fs,$var=0)
    {
        dg("__construct");
        $this->fs = $fs;

        $this->uid = random_int(1,100);
        if($var)
            $this->uid = $var;
    }


    public function GetUserById(int $id)
    {
        // TODO: Implement GetUserById() method.
        return "[User={$id},uid={$this->uid}]:". implode(",",$this->fs->allDirectories());
    }
}