<?php
namespace SmellOpen\Core;

use sskaje\mqtt\MQTT;
use sskaje\mqtt\Debug;
use sskaje\mqtt\MessageHandler;
use Proto2\Scentrealm\Simple;
use SmellOpen\Exceptions\DecodeException;

class Core {
    
    /**
     * 
     * @var \sskaje\mqtt\MQTT
     */
    protected $mqtt ;
    
    protected $config = [
        'dsn' => 'tcp://192.168.5.21:1883/',
        'accessKey' => 'BwFSfU0uMxodVBcAAGYs',
        'accessSecret' => 'fMakdIbruxuLOOwFvidR',
        'logLevel' => 'debug',
    ];
    
    
    public function __construct($config){
        $this->setConfig($config);
        
        $this->init();
    }
    
    public function setConfig($key ,$value = null){
        if(is_array($key)){
            $this->config = $key + $this->config;
        }else{
            $this->config[$key] = $value;
        }
    
    }
    
    public function getConfig($key){
        return isset($this->config[$key]) ? $this->config[$key] : false;
    }
    
    public function init(){
        $mqtt = new MQTT($this->getConfig('dsn'), $this->getConfig('accessKey'));
        
        $context = stream_context_create();
        $mqtt->setSocketContext($context);
//         Debug::Enable();
        $InitalizationKey = Utils::base32_encode($this->getConfig('accessSecret')) ;
        $pwd = \SmellOpen\Libs\TOTPService::get_otp($InitalizationKey);
        
        $mqtt->setAuth($this->getConfig('accessKey'), $pwd);
        $mqtt->setKeepalive(36);
        $connected = $mqtt->connect();
        if (! $connected) {
            Logger::debug($connected);
            die("Not connected\n");
        }
        
        $this->mqtt = $mqtt;
    }
    
    public function disconnect (){
        $this->mqtt->disconnect();
    }
    
    
    public function unsubscribe ($topics){
        $this->mqtt->unsubscribe(array_keys($topics));
    }
    
    /**
     * 
     * @param unknown $topics
     * [ 'topic' => 'qos' ]
     * @param unknown $callback
     * extends MessageHandler Object
     */
    public function subscribe ($topics,$callbackObj){
        $this->mqtt->subscribe($topics);
        $this->mqtt->setHandler($callbackObj);
        $this->mqtt->loop();
    }
    
    public function publish($topic, $message,$qos = 0, $retain = 0, $msgid = null){
        Logger::debug('publish '.$topic);
        Logger::debug('message',$message,['dumpByte']);
        $ret = $this->mqtt->publish_async($topic, $message, $qos, $retain,$msgid);
        Logger::debug('ret',$ret);
    }
    
    public function package($content,$cmdID,$seq = ''){
        return Utils::assemblePayload($content, $cmdID,$seq);
    }
    
    public function analyzeHeader($msg){
        return Utils::analyzeHeader($msg);
    }
    
    public function decodePayload($headerOrCmdID,$payload,$options = []){
        $cmdID = $headerOrCmdID;
        if(is_array($headerOrCmdID)){
            $cmdID = $headerOrCmdID['COMMAND_ID'];
        }
        $class = Utils::getDecodeClass($cmdID);
        
//         Logger::debug('message',$message,['dumpByte']);
        
        if(false !== $class){
            return Utils::decodeProtoData($payload,$class,Config::PROTO_HEADER_LENGRH);
        }
        throw new DecodeException($message);
    }
    
    public function sendCmd2Dev($deviceID,$cmdID,$protoData){
        $seq = Utils::generateSequenceID();
        if(!is_string($protoData)){
            $protoData = $protoData->serializeToString();
        }
        $data = $this->package($protoData, $cmdID,$seq);
        $this->publish("/".$deviceID, $data);
    }
    
    public function usingDevice($deviceID){
        return new CmdApi($this,$deviceID);
    }
}