<?php
namespace App\Services\Log;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class PayLogs
{

    private static $_instance = null;

    private $log;

    private $show = false;

    private $instance_id = '';

    private function __construct()
    {
        $this->log = new Logger('payLog');
    }

    static public function getInstance($newObj = false)
    {
        if ($newObj) {
            return new PayLogs();
        }
        if (is_null(self::$_instance)) {
            self::$_instance = new PayLogs();
        }
        self::$_instance->setInstanceId('');
        return self::$_instance;
    }

    public function setInstanceId($id = null)
    {
        $id || $id = substr(sha1(microtime()), 0, 6);
        $this->instance_id = $id;
    }

    /**
     * Set log shown
     */
    public function showLog()
    {
        $this->show = true;
    }

    /**
     * Set log file name
     * 
     * @param unknown $name            
     */
    public function setName($name)
    {
        $this->log->pushHandler(new StreamHandler(storage_path() . "/logs/{$name}" . date('Y-m-d'), Logger::INFO));
        @chmod(storage_path() . "/logs/{$name}" . date('Y-m-d'), 0777);
    }

    /**
     * Add log Info
     * 
     * @param unknown $name            
     * @param unknown $info            
     */
    public function setInfo($name, $info)
    {
        if (! is_array($info)) {
            $info = array(
                $info
            );
        }
        if ($this->show) {
            dump($info);
        }
        if ($this->instance_id) {
            $info = [
                '_iid' => $this->instance_id
            ] + $info;
        }
        
        $this->log->addInfo($name, $info);
    }

    /**
     * Set log file name with folder name
     * 
     * @param unknown $type            
     * @param unknown $filename            
     */
    public function setTypeName($type, $filename)
    {
        $this->log->pushHandler(new StreamHandler(storage_path() . "/{$type}/{$filename}" . date('Y-m-d'), Logger::INFO));
        @chmod(storage_path() . "/{$type}/{$filename}" . date('Y-m-d'), 0777);

    }

}