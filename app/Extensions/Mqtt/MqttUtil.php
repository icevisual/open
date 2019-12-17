<?php
namespace App\Extensions\Mqtt;

use Proto2\Scentrealm\Simple\SrCmdId;

class MqttUtil
{

    /**
     * Console.color
     * 
     * @var unknown
     */
    const COLOR_BLACK = 30;

    const COLOR_RED = 31;

    const COLOR_GREEN = 32;

    const COLOR_YELLOW = 33;

    const COLOR_BLUE = 34;

    const COLOR_LIGHT_PURPLE = 35;

    const COLOR_LIGHT_BLUE = 36;

    /**
     * Header.const
     * 
     * @var unknown
     */
    const PROTO_HEADER_LENGRH = 10;

    const PROTO_HEADER_MAGIC_NUMBER = 0xfe;

    const PROTO_HEADER_VERSION = 0x01;
    
    public static $headerStruct = [
        'MAGIC_NUMBER' => 1,
        'VERSION' => 1,
        'BODY_LENGTH' => 2,
        'COMMAND_ID' => 2,
        'SEQUENCE_NUMBER' => 4,
    ];

    /**
     * CmdID & Class Map
     * 
     * @var unknown
     */
    public static $CmdIDMap = [
        SrCmdId::SCI_req_sleep => '',
        SrCmdId::SCI_resp_sleep => '',
        SrCmdId::SCI_req_wakeup => '',
        SrCmdId::SCI_resp_wakeup => '',
        SrCmdId::SCI_req_usedSeconds => '',
        SrCmdId::SCI_resp_usedSeconds => \Proto2\Scentrealm\Simple\UsedTimeResponse::class,
        SrCmdId::SCI_req_playSmell => \Proto2\Scentrealm\Simple\PlaySmell::class,
        SrCmdId::SCI_resp_playSmell => \Proto2\Scentrealm\Simple\BaseResponse::class,
        SrCmdId::SCI_req_getDevAttr => \Proto2\Scentrealm\Simple\GetDevAttrsRequest::class,
        SrCmdId::SCI_resp_getDevAttr => \Proto2\Scentrealm\Simple\DevAttrs::class,
        SrCmdId::SCI_req_setDevAttr => \Proto2\Scentrealm\Simple\DevAttrs::class,
        SrCmdId::SCI_resp_setDevAttr => \Proto2\Scentrealm\Simple\BaseResponse::class,
        SrCmdId::SCI_req_featureReport => '',
        SrCmdId::SCI_resp_featureReport => \Proto2\Scentrealm\Simple\FeatureReportResponse::class,
    ];
    
    /**
     * CmdID & Class Map
     * 
     * @param unknown $cmdID            
     */
    public static function getDecodeClass($cmdID)
    {
        $map = self::$CmdIDMap;
        return isset($map[$cmdID]) ? $map[$cmdID] : false;
    }

    public static function aesDecrypt($content, $key, $iv = '00000000000Pkcs7', $base64decode = true)
    {
        $key = md5($key);
        $base64decode && $content = base64_decode($content);
        $decode = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $content, MCRYPT_MODE_CBC, $iv);
        return trim($decode);
    }

    public static function aesEncrypt($content, $key, $iv = '00000000000Pkcs7', $base64encode = true)
    {
        $key = md5($key);
        $decode = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $content, MCRYPT_MODE_CBC, $iv);
        return $base64encode ? base64_encode($decode) : $decode;
    }

    public static function colorString($msg, $color)
    {
        return "\e[{$color}m{$msg}\e[0m";
    }

    /**
     * Output data Bytes in Dec Syntax
     * 
     * @param unknown $data            
     * @param string $msg            
     */
    public static function dumpHexDec($data, $msg = '')
    {
        $output = '';
        if ($msg) {
            $output .= "[ " . MqttUtil::colorString($msg, MqttUtil::COLOR_GREEN) . " ] " . PHP_EOL;
        }
        for ($i = 0; $i < strlen($data); $i ++) {
            $output .= ' ' . sprintf('%3d', ord($data[$i]));
            if (($i + 1) % 10 == 0) {
                $output .= "\t";
            }
            if (($i + 1) % 20 == 0) {
                $output .= PHP_EOL;
            }
        }
        echo $output . PHP_EOL;
    }

    /**
     * Output data Bytes in Hex Syntax
     * 
     * @param unknown $data            
     * @param string $msg            
     */
    public static function dumpByte($data, $msg = '')
    {
        $output = '';
        if ($msg) {
            $output .= "[ " . MqttUtil::colorString($msg, MqttUtil::COLOR_GREEN) . " ] " . PHP_EOL;
        }
        for ($i = 0; $i < strlen($data); $i ++) {
            $output .= ' ' . sprintf('%02x', ord($data[$i]));
            if (($i + 1) % 10 == 0) {
                $output .= "\t";
            }
            if (($i + 1) % 20 == 0) {
                $output .= PHP_EOL;
            }
        }
        echo $output . PHP_EOL;
    }
    
    public static function info($msg){
        
        $prefix = "[ ".now()." ] info : ";
        
        echo $prefix.self::colorString($msg, self::COLOR_GREEN).PHP_EOL;
    }
    
    public static function dump($msg){
        dump($msg);
    }

    /**
     * decode ProtoData
     * 
     * @param unknown $msg            
     * @param unknown $class            
     * @param number $headerLength            
     * @param string $aes            
     * @return unknown
     */
    public static function decodeProtoData($msg, $class, $headerLength = 0, $aes = false, $key = '', $iv = '')
    {
        $packed = $msg;
        if ($headerLength > 0) {
            $packed = substr($msg, $headerLength);
            MqttUtil::dumpByte($packed, "Received Body Bytes");
        }
        
        try {
            if ($aes) {
                $decryptedPack = MqttUtil::aesDecrypt($packed, $key, $iv, false);
                MqttUtil::dumpByte($decryptedPack, "aesDecrypt Body Bytes");
                $obj = $class->parseFromString($decryptedPack);
                return $obj;
            } else {
                $obj = $class->parseFromString($packed);
                return $obj;
            }
        } catch (\Exception $e) {
            dump("error decodeProtoData");
        }
        return false;
    }

    public static function assemblePayload($content, $cmdID, $seq = '')
    {
        $bodyLength = strlen($content);
        $seq = $seq ? $seq : time();
        $headerData = [
            MqttUtil::PROTO_HEADER_MAGIC_NUMBER,
            MqttUtil::PROTO_HEADER_VERSION,
            $bodyLength,
            $cmdID,
            $seq
        ];
        $headerStruct = self::$headerStruct;
        $headerLength = array_sum($headerStruct);
        $headerStr = str_pad('', $headerLength);
        $index = 0;
        $i = 0 ;
        foreach ($headerStruct as $k => $v){
            while ($v -- ) $headerStr{$index ++} = chr($headerData[$i] >> ($v * 8) & 0xff);
            $i ++ ;
        }
        $payload = $headerStr . $content;
        MqttUtil::dumpByte($headerStr, 'Header Bytes');
        MqttUtil::dumpByte($content, 'Content Bytes');
        MqttUtil::dumpByte($payload, 'Payload With Header');
        
        return $payload;
    }
    
    /**
     * Analyze header , Return Header Information If it matches The Protobuf syntax
     * , false otherwise
     * @param unknown $msg
     * @return boolean
     */
    public static function analyzeHeader($msg){
        
        $headerStruct = self::$headerStruct;
        $headerLength = array_sum($headerStruct);
        $headerBytes = [];
        $packed = '';
        for($i = 0 ; $i < strlen($msg) ; $i ++){
            if($i < $headerLength){
                $headerBytes[] = ord($msg[$i]);
            }else{
                break;
            }
        }
        if(count($headerBytes) == $headerLength){
            if($headerBytes[0] == MqttUtil::PROTO_HEADER_MAGIC_NUMBER){
                $ret = [];
                $index = 0 ;
                foreach ($headerStruct as $k => $v){
                    $ret[$k] = 0;
                    while ($v -- ) $ret[$k] |= $headerBytes[$index ++ ] << ($v * 8);
                }
                return $ret;
            }
        }
        return false;
    }
    
    public static function isProtoAvaliable(){
        return class_exists('\ProtobufMessage');
    }
    
    public static function base32_encode($str){
        $base32Map = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $len = strlen($str);
        $b32 = '';
        $rest = 0;
        $restLen = 0;
        for($i = 0 ; $i < $len ; $i ++ ){
            $chrCode = ord($str{$i});
            $thisByte = $rest << 8 | $chrCode;
            $thisByteLen = $restLen + 8;
            while($thisByteLen >= 5){
                $b32 .= $base32Map{$thisByte >> ($thisByteLen - 5)};
                $thisByteLen -= 5;
                $thisByte = $thisByte & (pow(2,$thisByteLen) - 1);
            }
            $rest = $thisByte;
            $restLen = $thisByteLen;
        }
        if($restLen > 0){
            $rest = $rest << ( 5 - $restLen);
            $b32 .= $base32Map{$rest};
        }
        return $b32;
    }
    
    
    
}