<?php
namespace SmellOpen\Core;

class Logger
{

    const DEBUG = 1;
    const INFO = 2;
    const WARNING = 3;
    const ERROR = 4;
    
    
    protected static $level = 'DEBUG';
    
    protected static $levels = [
        self::DEBUG => 'DEBUG',
        self::INFO => 'INFO',
        self::WARNING => 'WARNING',
        self::ERROR => 'ERROR',
    ];
    
    protected static $options = [
        'dumpByte'
    ];
    
    private static function log($level, $msg, $context = [],$options = [])
    {
        $lvs =  array_flip(self::$levels);
        $lv = $lvs[self::$level];
        if($lv > $level){
            return ;
        }
        $colorMap = [
            self::DEBUG => Config::COLOR_LIGHT_BLUE,
            self::INFO => Config::COLOR_GREEN,
            self::WARNING => Config::COLOR_YELLOW,
            self::ERROR => Config::COLOR_RED,
        ];
        $now = date('Y-m-d H:i:s');
        $levelName = self::$levels[$level];
        $string = "[{$now}]".Utils::colorString(" {$levelName} : ".$msg, $colorMap[$level]).PHP_EOL;
        echo $string;
        if($context){
            if(!empty($options)){
                $options = array_flip($options);
                if(isset($options['dumpByte'])){
                    echo Utils::colorString(Utils::string2byte($context), Config::COLOR_LIGHT_PURPLE).PHP_EOL;
                }
            }else{
                Utils::dump($context);
            }
            echo PHP_EOL;
        }
    }

    public static function debug($msg, $context = [],$options = [])
    {
        self::log(self::DEBUG, $msg, $context,$options);
    }

    public static function info($msg, $context = [],$options = [])
    {
        self::log(self::INFO, $msg, $context,$options);
    }

    public static function warning($msg, $context = [],$options = [])
    {
        self::log(self::WARNING, $msg, $context,$options);
    }

    public static function error($msg, $context = [],$options = [])
    {
        self::log(self::ERROR, $msg, $context,$options);
    }
}