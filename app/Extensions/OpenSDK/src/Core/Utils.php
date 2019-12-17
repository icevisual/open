<?php
namespace SmellOpen\Core;

use Proto2\Scentrealm\Simple\SrCmdId;

class Utils
{

    /**
     * CmdID & Class Map
     * 
     * @param unknown $cmdID            
     */
    public static function getDecodeClass($cmdID)
    {
        $map = Config::$CmdIDMap;
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
            $output .= "[ " . Utils::colorString($msg, Config::COLOR_GREEN) . " ] " . PHP_EOL;
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
            $output .= "[ " . Utils::colorString($msg, Config::COLOR_GREEN) . " ] " . PHP_EOL;
        }
        echo $output.self::string2byte($data) . PHP_EOL;
    }
    
    public static function string2byte($data)
    {
        $output = '';
        for ($i = 0; $i < strlen($data); $i ++) {
            $output .= ' ' . sprintf('%02x', ord($data[$i]));
            if (($i + 1) % 10 == 0) {
                $output .= "\t";
            }
            if (($i + 1) % 20 == 0) {
                $output .= PHP_EOL;
            }
        }
        return $output;
    }
    
    public static function info($msg){
        
        $prefix = "[ ".now()." ] info : ";
        
        echo $prefix.self::colorString($msg, Config::COLOR_GREEN).PHP_EOL;
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
            Logger::debug("Received Body Bytes",$packed,['dumpByte']);
        }
        try {
            $class = new $class();
            if ($aes) {
                $decryptedPack = Utils::aesDecrypt($packed, $key, $iv, false);
                Logger::debug("aesDecrypt Body Bytes",$decryptedPack,['dumpByte']);
                $obj = $class->parseFromString($decryptedPack);
                return $obj;
            } else {
                $obj = $class->parseFromString($packed);
                return $obj;
            }
        } catch (\Exception $e) {
            Logger::error('error decodeProtoData');
        }
        return false;
    }

    public static function assemblePayload($content, $cmdID, $seq = '')
    {
        $bodyLength = strlen($content);
        $seq = $seq ? $seq : time();
        $headerData = [
            Config::PROTO_HEADER_MAGIC_NUMBER,
            Config::PROTO_HEADER_VERSION,
            $bodyLength,
            $cmdID,
            $seq
        ];
        $headerStruct = Config::$headerStruct;
        $headerLength = array_sum($headerStruct);
        $headerStr = str_pad('', $headerLength);
        $index = 0;
        $i = 0 ;
        foreach ($headerStruct as $k => $v){
            while ($v -- ) $headerStr{$index ++} = chr($headerData[$i] >> ($v * 8) & 0xff);
            $i ++ ;
        }
        $payload = $headerStr . $content;
        
        Logger::debug('Header Bytes',$headerStr,['dumpByte']);
        Logger::debug('Content Bytes',$content,['dumpByte']);
        Logger::debug('Payload With Header',$payload,['dumpByte']);
        
        return $payload;
    }
    
    /**
     * Analyze header , Return Header Information If it matches The Protobuf syntax
     * , false otherwise
     * @param unknown $msg
     * @return boolean
     */
    public static function analyzeHeader($msg){
        
        $headerStruct = Config::$headerStruct;
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
            if($headerBytes[0] == Config::PROTO_HEADER_MAGIC_NUMBER){
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
    
    public static function generateSequenceID(){
        return time();
    }
}