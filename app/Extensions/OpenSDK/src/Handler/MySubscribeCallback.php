<?php
namespace SmellOpen\Handler;

use sskaje\mqtt\MQTT;
use sskaje\mqtt\MessageHandler;
use sskaje\mqtt\Message\PUBLISH;

use SmellOpen\Core\Utils;


class MySubscribeCallback extends MessageHandler
{

    public function publish(MQTT $mqtt, PUBLISH $publish_object)
    {
        
        Utils::info( sprintf(
            "(msgid=%d, QoS=%d, dup=%d, topic=%s)\n",
            $publish_object->getMsgID(),
            $publish_object->getQos(),
            $publish_object->getDup(),
            $publish_object->getTopic()
        ));
        $msg = $publish_object->getMessage();
        
        $header = Utils::analyzeHeader($msg);
        Utils::dumpHexDec($msg,"Received Bytes Dec");
        Utils::dumpByte($msg,"Received Bytes");
        
        if(false === $header){
            Utils::info('Header Not Match'); 
            Utils::info($msg);
        }else{
            Utils::dumpByte(substr($msg, 0,Utils::PROTO_HEADER_LENGRH),"HeaderBytes Bytes");
            Utils::dump($header);
            if(Utils::isProtoAvaliable()){
            
                if($cmdID == \Proto2\Scentrealm\Simple\SrCmdId::SCI_req_playSmell){
                    $class = new \Proto2\Scentrealm\Simple\PlaySmell();
                }
                $ret = Utils::decodeProtoData($msg, $class,$headerLength);
                Utils::dump($ret);
                $class->dump();
            }
        }
    }
    
}
