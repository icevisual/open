<?php
namespace App\Services\Log;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class CLogs extends \Monolog\Logger
{

    private $show = false;

    private $instance_id ;

    public function __construct($filename,$folder = 'log')
    {
        parent::__construct('local');
        
        defined('DS') or define('DS', DIRECTORY_SEPARATOR);
        
        $logFileAbsolutePath = storage_path($folder);
        
        if(!is_dir($logFileAbsolutePath)){
            @mkdir($logFileAbsolutePath);
            @chmod($logFileAbsolutePath, 0755);
        }
        $logFileAbsoluteName = $logFileAbsolutePath.DS.$filename.date('Y-m-d');

        
        $this->pushHandler(new StreamHandler($logFileAbsoluteName, Logger::INFO));
        @chmod($logFileAbsoluteName, 0444);
        
        $this->setInstanceId();
        
    }

    public function setInstanceId($id = null)
    {
        $id || $id = substr(sha1(microtime()), 0, 6);
        $this->instance_id = $id;
    }

    public function showLog($show = true)
    {
        $this->show = $show;
    }

    public function addRecord($level, $message, array $context = array())
    {
        if ($this->instance_id) {
            $context['_iid'] = $this->instance_id;
        }
        if ($this->show) {
            echo $message.PHP_EOL;
            print_r($context);
        }
        return parent::addRecord($level, $message,$context);
    }
    
}