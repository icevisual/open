<?php
namespace SmellOpen\Handler;

use sskaje\mqtt\MQTT;
use sskaje\mqtt\MessageHandler;
use sskaje\mqtt\Message\PUBLISH;

use SmellOpen\Core\Utils;
use SmellOpen\Core\Core;
use SmellOpen\Core\Logger;

class MsgHandler extends MessageHandler
{
    
    /**
     * 
     * @var \SmellOpen\Core\Core;
     */
    protected $Core;
    
    
    protected $callback;
    
    public function __construct(Core $Core,$callback = null){
        $this->Core = $Core;
        $this->callback = $callback;
    }
    

    public function publish(MQTT $mqtt, PUBLISH $publish_object)
    {
        
        Logger::info(sprintf(
            "(msgid=%d, QoS=%d, dup=%d, topic=%s)\n",
            $publish_object->getMsgID(),
            $publish_object->getQos(),
            $publish_object->getDup(),
            $publish_object->getTopic()
        ));
        
        $msg = $publish_object->getMessage();

        $header = Utils::analyzeHeader($msg);
//         Utils::dumpHexDec($msg,"Received Bytes Dec");
        Logger::debug('Received Bytes',$msg,['dumpByte']);

        if(false === $header){
            Logger::info('Header Not Match');
        }else{
            Logger::info('Header Found',$header);
            if(Utils::isProtoAvaliable()){
                $obj = $this->Core->decodePayload($header,$msg);
                Logger::info('decodePayload obj',$obj);
                $obj->dump();
                if($this->callback){
                    $rr =  call_user_func_array($this->callback, [$this->Core,$obj,$header,$msg]);
                    if(false === $rr){
                        $this->Core->disconnect();
                    }
                }
            }else{
                Logger::info('Protoc Not Avaliable');
//                 $this->Core->disconnect();
            }
        }
    }

}
