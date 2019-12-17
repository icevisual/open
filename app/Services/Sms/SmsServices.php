<?php
namespace App\Services\Sms;

class SmsServices
{
    /*
     * 加密
     */
    public static function encrypt($value, $key)
    {
        mt_srand();
        $randomizer = MCRYPT_RAND;
        defined('MCRYPT_DEV_URANDOM') && $randomizer = MCRYPT_DEV_URANDOM;
        defined('MCRYPT_DEV_RANDOM') && $randomizer = MCRYPT_DEV_RANDOM;
        $cipher = MCRYPT_RIJNDAEL_128;
        $mode = MCRYPT_MODE_CBC;
        $iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher, $mode), $randomizer);
        $block = mcrypt_get_iv_size($cipher, $mode);
        $inValue = serialize($value);
        $pad = $block - (strlen($inValue) % $block);
        $addPadding = $inValue . str_repeat(chr($pad), $pad);
        $padAndMcrypt = mcrypt_encrypt($cipher, $key, $addPadding, $mode, $iv);
        $value = base64_encode($padAndMcrypt);
        $mac = hash_hmac('sha256', ($iv = base64_encode($iv)) . $value, $key);
        $encryptStr = base64_encode(json_encode(compact('iv', 'value', 'mac')));
        return $encryptStr;
    }
    
    /**
     * 发送请求
     * @param unknown $params
     * @param number $timeout
     * @return boolean
     */
    public static function send($params)
    {
        $key = 'ipYoMcxXAzOSabm7';
        $url = 'http://service.xb.guozhongbao.com/sms/v1/send';
        $params['c'] = "xfd";
        $params['p'] = self::encrypt(json_encode($params), $key);
        $res =  self::curl_post($url, $params);
        if($res['status'] == 200){
            return true;
        }
        return false;
    }
    
    
    /**
     * 发送阿里大于短信
     * @param unknown $phone 
     * @param unknown $param
     * $param = [
            'code' => '',
            'n' => '',
        ];
     */
    public static function sendBigFish($phone,$param)
    {
        $config = \Config::get('sms.alidayu');
        $c = new \TopClient;
        $c->appkey = $config['app_key'];
        $c->secretKey = $config['app_secret'];;
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
//         $req->setExtend("123456");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName($config['sign']);
        $req->setSmsParam(json_encode($param));
        $req->setRecNum($phone);
        $req->setSmsTemplateCode($config['template_id']);
        $resp = $c->execute($req);
        
        \Log::info('SMS-'.$phone,['resp' => json_encode($resp)] + $param);
        
        return $resp;
    }
    
    /**
     * curl
     * @param unknown $url
     * @param unknown $data
     * @param string $json_decode
     * @return Ambigous <mixed, unknown>
     */
    public static function curl_post($url, $data, $json_decode = true) {
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url ); // url
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 8 );
        $User_Agen = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36';
        curl_setopt ( $ch, CURLOPT_USERAGENT, $User_Agen );
        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query($data) ); // 数据
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        $info = curl_exec ( $ch );
        curl_close ( $ch );
        $result =  $json_decode ? json_decode ( $info, 1 ) : $info;
        return $result;
    }
    /**
     * 调用实例
     */
    public static function invocationExample(){
        /**
         * 两种短信服务 YuntongxunSms 和 AirleadSms
         * YuntongxunSms 是云通讯 ，传模板ID以及模板参数来确定短信内容，每天对一个手机号码的发送次数有限（貌似是5条）
         * AirleadSms 直接通过传递短信内容
         */
        $phone = ['18767135775'];
        // YuntongxunSms 参数示例
        $params = array(
            'smsService' => 'YuntongxunSms', // YuntongxunSms AirleadSms
            'phone' => $phone,
            'message' => [
                '22222','12' // 模板内的参数，这个是验证码短信，这个数字是验证码
            ],
            'expand' => [
                'tempId' => 98838 // 模板id
            ]
        );
//         $params = array(
//             'smsService' => 'AirleadSms', // YuntongxunSms AirleadSms
//             'phone' => $phone,
//             'message' => 'dfdfdfdfd', // 短信内容
//         );
        
        $res =  self::send($params);
        var_dump($res);
    }
}