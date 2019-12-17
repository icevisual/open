<?php
namespace SmellOpen\Core;

use Proto2\Scentrealm\Simple\SrCmdId;
use SmellOpen\Handler\MsgHandler;

class CmdApi
{

    protected $deviceID;

    protected $Core;

    public function __construct(Core $Core, $deviceID)
    {
        $this->Core = $Core;
        $this->deviceID = $deviceID;
    }

    public function sleep($callback = '')
    {
        Logger::debug('request sleep');
        
        $this->Core->sendCmd2Dev($this->deviceID, SrCmdId::SCI_req_sleep, '');
        
        $topics = [
            "/{$this->deviceID}/resp" => 1
        ];
        Logger::debug('subscribe topics', $topics);
        $handler = new MsgHandler($this->Core, function () {
            Logger::debug('func_get_args', func_get_args());
            return false;
        });
        $this->Core->subscribe($topics, $handler);
        
        Logger::debug(__FUNCTION__ . '--END');
    }

    public function wakeup()
    {}

    public function usedSeconds()
    {}

    public function playSmell()
    {}

    public function getDevAttr()
    {}

    public function setDevAttr()
    {}

    public function featureReport()
    {}
}