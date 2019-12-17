<?php
namespace App\Services\Open;

use App\Models\User\Account;
use App\Models\Common\Region;
use App\Exceptions\ServiceException;
use App\Services\Email\EmailSender;
use App\Services\Sms\SmsServices;
use App\Extensions\Common\Throttles;

class OpenServices 
{
    use Throttles;
    
    const KEY_SEE_SECRET_TOKEN = 'sstn';
    const KEY_REGISTER_CAPTACH = 'rtca';
    const KEY_REGISTER_SESSION = 'rtss';
    const KEY_REGISTER_EMAIL = 'rtem';
    const KEY_RESETTING_TOKEN = 'rttn';
    const KEY_LOGIN_SESSION = 'liss';
    const KEY_INSTANCE_ID = 'isti';
    const KEY_EMAIL_ACTIVATION = 'emac';
    const KEY_PHONE_ACTIVATION = 'phac';
    const KEY_REGISTER_EMAIL_LIMIT = 'srel';
    const KEY_REGISTER_PHOME_LIMIT = 'srps';
    const KEY_PASSWORD_RESET_EMAIL_LIMIT = 'sprel';
    const KEY_PASSWORD_RESET_EMAIL_TOKEN = 'spret';
    
    /**
     * 是否使用SESSION
     * @var unknown
     */
    const SET_USE_SESSION = 'set_use_session';
    
    
    public $_settings = [
        self::SET_USE_SESSION => true,
    ];
    
    public $redisPrefiex = 'open';
    
    public function getRedisPrefix($key = 'default')
    {
        return $this->redisPrefiex . '-' . substr(sha1(static::class), 0, 6) . '-' . $key;
    }
    
    public function getCacheKey($dt, $type)
    {
        return $this->getRedisPrefix($dt . '-' . $type);
    }
    
    public function getSetting($key){
        return isset($this->_settings[$key]) ? $this->_settings[$key] : false;
    }
    

    /**
     * 信息分隔符
     *
     * @var unknown
     */
    public $separator = '###';

    
    public function setRegistingEmail($email){
        session([self::KEY_REGISTER_EMAIL => $email]);
    }
    
    public function clearRegistingEmail(){
        session([self::KEY_REGISTER_EMAIL => null]);
    }
    
    public function getRegistingEmail(){
        return session(self::KEY_REGISTER_EMAIL);
    }
    
    public function clearSessionUid()
    {
        session([self::KEY_REGISTER_SESSION => null]);
    }
    
    /**
     * 设置正在做登录3步骤的UID
     *
     * @param unknown $uid            
     */
    public function setSessionUid($uid)
    {
        if($this->getSetting(self::SET_USE_SESSION)){
            session([self::KEY_REGISTER_SESSION => $uid]);
            return true;
        }
        return $this->generateInstanceID($uid);
    }

    /**
     * 获取当前会话的UID
     */
    public function getSessionUid()
    {
        if($this->getSetting(self::SET_USE_SESSION)){
            return session(self::KEY_REGISTER_SESSION);
        }
        $instanceID = \Input::get('instance');
        return $instanceID ? $uid = $this->validateInstanceID($instanceID) : false;
    }

    /**
     * 检测实例ID的有效性，有效则返回UID，否则返回FALSE
     *
     * @param unknown $instanceID   
     *  实例ID         
     * @return number|false
     */
    public function validateInstanceID($instanceID)
    {
        $uid = $this->validateTempAESToken($instanceID, self::KEY_INSTANCE_ID, '实例ID');
        $info = Account::where('id', $uid)->count();
        if (! $info) {
            throw new ServiceException('实例ID信息缺失',\ErrorCode::AUTH_FAILED);
        }
        return $uid;
    }

    /**
     * 生成请求实例ID
     *
     * @param unknown $uid            
     * @return \App\Extensions\Common\str
     */
    public function generateInstanceID($uid,$significantInterval = 86400)
    {
        return $this->generateTempAESToken($uid, self::KEY_INSTANCE_ID,$significantInterval);
    }

    /**
     * 验证注册第一步的信息
     *
     * @param unknown $data            
     * @param number $uid            
     */
    public function validateRegisterData($data, $uid = 0)
    {
        $firstOrAll = $data['syntax'];
        $firstOrAll = $firstOrAll == 'all' ? 1 : 0;
        $rules = [
            //|unique:account,account,' . $uid . ',id
            'email' => 'bail|required|email',
            'password' => [
                'bail',
                'required',
                'password'
            ],
            'repassword' => 'bail|required|same:password',
            'code' => 'bail|required|checkCaptcha'
        ];
        runCustomValidator([
            'data' => $data, // 数据 
            'rules' => $rules, // 条件  
            'messages' => [
                'password.password' => '密码由字母、数字或者英文字符组成，最短6位，区分大小写'
            ], // 错误信息 
            'attributes' => [
                'email' => '邮箱',
                'password' => '密码',
                'repassword' => '确认密码',
                'code' => '验证码'
            ], // 属性名映射 
            'config' => [
                'FirstOrAll' => $firstOrAll
            ]
        ]);
        
        $query = Account::select([
            'id',
            'email',
            'email_activation'
        ])->where('account',$data['email']);
        if($uid){
            $query->where('id','!=',$uid);
        }
        $info = $query->first();
        
        if($info){
            if($info->email_activation == Account::EMAIL_ACTIVATION_YES){
                throw new ServiceException('error',\ErrorCode::VALIDATION_FAILED,[
                    'email' => '用户已注册'
                ]);
            }
            return $info->id;
        }
        return false;
    }

    /**
     * 注册 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerOps($data)
    {
        $uid = $this->validateRegisterData($data);
        if($uid){
            // 表示之前已注册第一步、未激活，然后再次执行注册第一步
            $user = Account::where('id',$uid)->update([
                'account' => $data['email'], // 账号
                'password' => \Hash::make($data['password']), // 密码
                'email' => $data['email'], // 邮箱
                'registered_at' => \Carbon\Carbon::now()
            ]);
        }else{
            $user = Account::create([
                'account' => $data['email'], // 账号
                'password' => \Hash::make($data['password']), // 密码
                'email' => $data['email'], // 邮箱
                'registered_at' => \Carbon\Carbon::now()
            ]);
            $uid = $user['id'];
        }
        return $uid;
    }

    public function resetEmailOps($uid, $data)
    {
        $info = Account::betweenFirstSecond($uid);
        $validUid = $this->validateRegisterData($data, $uid);
        if($validUid && $validUid != $uid){
            // 填入了一个未激活的邮箱（未注册），删了原先的
            Account::where('id', $validUid)->delete();
        }
        $user = Account::where('id', $uid)->update([
            'account' => $data['email'], // 账号
            'password' => \Hash::make($data['password']), // 密码
            'email' => $data['email'], // 邮箱
            'registered_at' => \Carbon\Carbon::now()
        ]);
        
        $changedEmail = $data['email'] != $info->email;
        return [
            'oldEmail' => $info->email,
            'emailChanged' => $changedEmail
        ];
    }

    /**
     * 解析邮箱登录地址
     * 
     * @param unknown $email            
     * @return string
     */
    public function getEmailHomeAddr($email)
    {
        $map = [
            'http://mail.qq.com/',// QQ邮箱
            'http://mail.aliyun.com/',// 阿里云邮箱
            'http://mail.163.com/',// 网易163邮箱
            'http://www.126.com/',// 网易126邮箱
            'http://www.hotmail.com/',// Hotmail
            'http://mail.sohu.com/',// 搜狐邮箱
            'http://mail.sina.com.cn/',// 新浪邮箱
            'http://web.mail.tom.com/',// TOM邮箱
            'http://www.yeah.net/',// Yeah.net邮箱
            'http://mail.21cn.com/',// 21CN邮箱
            'http://ym.163.com/',// 网易企业邮箱
            'http://mail.qq.com/cgi-bin/loginpage?t=fox_loginpage&amp;sid=,2,zh_CN&amp;r=a5df221d27ddbb13cc2182e934baa805',// Foxmail
            'http://vip.163.com/',// 163VIP邮箱
            'http://www.outlook.com/',// OUTLOOK邮箱
            'http://mail.189.cn/',// 189邮箱
            'http://mail.10086.cn/',// 139手机邮箱
            'http://www.263.net/',// 263企业邮箱
            'http://exmail.qq.com/',// 腾讯企业邮箱
        ];
        list ($header, $tail) = explode('@', $email);

        foreach ($map as $v){
            if(strpos(trim(strrev($v),"/"), trim(strrev($tail),"/")) === 0){
                return $v;
            }
        }
        return 'http://'.$tail;
    }

    public function setForgetResetToken($token){
        
    }
    
    public function setLogin($uid)
    {
        // 加一个midw做登录检测，重制一种instanceID做登录检测，auth-header等
        if($this->getSetting(self::SET_USE_SESSION)){
            session([self::KEY_LOGIN_SESSION => [
                'uid' => $uid
            ]]);
            return true;
        }
//         return $this->generateInstanceID($uid);
    }

    /**
     * 
     * @param callable $before
     *  返回数组，作为$main的参数，必须包含一个 throttleKey 字段
     * @param callable $main
     *  主要执行的操作
     * @param array $settings 
     *  <pre>[
     *      'sendInterval',//每次尝试的间隔（秒）
     *      'maxAttempts',// 最大连续尝试次数
     *      'lockoutTime',// 锁定时间（秒）
     *  ]</pre>
     * @return multitype:mixed number
     */
    public function doWithLimitationTemplate(callable $before,callable $main, array $settings = []){
        
        $sendInterval   = array_get($settings, 'sendInterval',60); // S
        $maxAttempts    = array_get($settings, 'maxAttempts',10);
        $lockoutTime    = array_get($settings, 'lockoutTime',86400); // S
        
        if(\App::environment('testing')){
            $sendInterval   = \Config::get('testing.sendInterval',$sendInterval);
            $maxAttempts    = \Config::get('testing.maxAttempts',$maxAttempts);
            $lockoutTime    = \Config::get('testing.lockoutTime',$lockoutTime);
        }
        $this->setLockoutTime($lockoutTime);
        $this->setMaxAttempts($maxAttempts);
        // 必须返回一个 throttleKey 字段
        $returnData = $before();
        
        $throttleKey = array_get($returnData, 'throttleKey');
        
        if(!$throttleKey){
            throw new ServiceException('系统暂时不可用',\ErrorCode::SYSTEM_ERROR);
        }
        // 考虑缓存的抽象
        // TODO  RateLimiter 不用redis
        if (\LRedis::GET($throttleKey)) {
            $ttl = \LRedis::TTL($throttleKey);
            throw new ServiceException('发送过于频繁，请稍后再试',\ErrorCode::REQUEST_TOO_OFFEN,[
                'restTime' => $ttl
            ]);
        }
        
        if ($this->hasTooManyAttempts($throttleKey)) {
            $seconds = $this->secondsRemainingOnLockout($throttleKey);
            if($seconds < 0 ){
                $this->clearAttempts($throttleKey);
            }else{
                throw new ServiceException('发送过于频繁，请'.$seconds.'秒后重试',\ErrorCode::REQUEST_FORBIDDEN,[
                    'seconds' => $seconds
                ]);
            }
        }
        
        $main($returnData);
        
        $retData = [];
        if($sendInterval){
            \LRedis::SETEX($throttleKey, $sendInterval, 1);
            $retData['interval'] = $sendInterval;
        }
        
        $this->incrementAttempts($throttleKey);
        $retriesLeft = $this->retriesLeft($throttleKey);
        $retData['retriesLeft'] = $retriesLeft;
        return $retData;
    }
        

    /**
     * 验证手机验证码
     *
     * @param unknown $uid            
     * @param unknown $phone            
     * @param unknown $code            
     * @return boolean
     */
    public function validateRegisterSms($uid, $phone, $code)
    {
        $cacheKey = $this->getCacheKey($phone, self::KEY_PHONE_ACTIVATION);
        if (\LRedis::GET($cacheKey) == $code) {
            \LRedis::DEL($cacheKey);
            $this->clearAttempts($this->getCacheKey($uid, self::KEY_REGISTER_PHOME_LIMIT));
            return true;
        }
        return false;
    }

    /**
     * 3.2发送激活短信
     * @apiSuccess {Integer} interval 下次发送剩余时间（秒）
     * @apiSuccess {Integer} retriesLeft 剩余可发送次数
     * 
     * @apiError {Integer} restTime 发送间隔剩余时间（秒）
     * @apiError {Integer} seconds 禁止发送剩余时间（秒）
     */
    public function sendRegisterSms()
    {
        $json = $this->doWithLimitationTemplate(function (){
            $phone = \Input::get('phone');//手机号码
            // 每隔60秒可发送一次
            // 每天限制发送总次数
            $uid = $this->getSessionUid();
            if (! $uid) {
                throw new ServiceException('未找到用户信息',\ErrorCode::VITAL_NOT_FOUND);
            }
            $info = Account::betweenSecondThird($uid);
            
            runCustomValidator([
                'data' => [
                    'phone' => $phone
                ], // 数据
                'rules' => [
                    'phone' => 'bail|required|mobile|unique:account,phone,' . $uid . ',id'
                ], // 条件
                'attributes' => [
                    'phone' => '手机号码'
                ]
            ]);
            // SRE short for send register email
            // $key = $this->getSendRegisterPhoneCacheKey($uid);
            $key = $this->getCacheKey($uid, self::KEY_REGISTER_PHOME_LIMIT);
            return [
                'throttleKey' => $key ,
                'phone' => $phone
            ];
        }, function ($context){
            $code = randStr(6, 'NUMBER');
            
            $phone = [
                $context['phone']
            ];
            // YuntongxunSms 参数示例
            $params = array(
                'smsService' => 'YuntongxunSms', // YuntongxunSms AirleadSms
                'phone' => $phone,
                'message' => [
                    $code,
                    '10'
                ], // 模板内的参数，这个是验证码短信，这个数字是验证码
                'expand' => [
                    'tempId' => 98838
                ]
            ); // 模板id
            
            if (! \App::environment('local', 'testing')) {
                $res = SmsServices::send($params);
            }
            // SET　　PHONE SMS CODE
            \LRedis::SETEX($this->getCacheKey($phone[0], self::KEY_PHONE_ACTIVATION), 600, $code);
        });
        return $this->__json($json);
    }

    /**
     * 验证邮箱
     *
     * @param unknown $token            
     * @throws ServiceException
     * @return boolean
     */
    public function validateTempAESToken($token, $cachedMix, $name)
    {
        $info = aes_decrypt($token);
        if (false === $info) {
            throw new ServiceException($name . '解析失败',\ErrorCode::ANALYZE_FAILED);
        }
        $exp = explode($this->separator, $info);
        
        if (count($exp) != 2) {
            throw new ServiceException($name . '信息缺失',\ErrorCode::ANALYZE_DATA_MISSING);
        }
        
        list ($keyData, $expiredAt) = $exp;
        if (! $keyData || ! $expiredAt) {
            throw new ServiceException($name . '解析错误',\ErrorCode::ANALYZE_DATA_ERROR);
        }
        if ($expiredAt < time()) {
            throw new ServiceException($name . '超时',\ErrorCode::TIME_OUT);
        }
        $cached = \LRedis::GET($this->getCacheKey($keyData, $cachedMix));
        if ($cached != $token) {
            throw new ServiceException('不存在的' . $name,\ErrorCode::UNEXPECTED);
        }
        return $keyData;
    }

    public function validatePasswdResetEmailToken($token)
    {
        $email = $this->validateTempAESToken($token, self::KEY_PASSWORD_RESET_EMAIL_TOKEN, 'TOKEN');
        $info = Account::where('account', $email)->first([
            'email_activation',
            'id'
        ]);
        if (! $info) {
            throw new ServiceException('未找到该用户',\ErrorCode::VITAL_NOT_FOUND);
        }
        if (Account::EMAIL_ACTIVATION_YES != $info->email_activation) {
            throw new ServiceException('邮箱未激活',\ErrorCode::LOGIC_ERROR);
        }
        $this->setResetingToken($token);
        // \LRedis::DEL($this->getCacheKey($email, self::KEY_PASSWORD_RESET_EMAIL_TOKEN));
        return [
            $info->id,
            $email
        ];
    }
    
    /**
     * 验证查看秘钥的TOKEN
     * @param unknown $token
     * @return Ambigous <boolean, multitype:>
     */
    public function validateShowSecretKeyToken($token)
    {
        $uid = $this->validateTempAESToken($token, self::KEY_SEE_SECRET_TOKEN, 'TOKEN');
        return $uid;
    }
    
    

    public function setResetingToken($uid){
        session([self::KEY_RESETTING_TOKEN => $uid]);
    }
    
    /**
     * 进入密码修改页面的凭证
     * @return mixed
     */
    public function getResetingToken(){
        return session(self::KEY_RESETTING_TOKEN);
    }
    
    public function clearResetingToken(){
        return session([self::KEY_RESETTING_TOKEN => null]);
    }
    
    /**
     * 验证邮箱
     *
     * @param unknown $token            
     * @throws ServiceException
     * @return boolean
     */
    public function validateEmailActivationToken($token)
    {
        $email = $this->validateTempAESToken($token, self::KEY_EMAIL_ACTIVATION, '邮箱激活TOKEN');
        $info = Account::where('account', $email)->first([
            'email_activation',
            'id'
        ]);
        if (! $info) {
            throw new ServiceException('未找到该用户',\ErrorCode::VITAL_NOT_FOUND);
        }
        if (Account::EMAIL_ACTIVATION_YES == $info->email_activation) {
            throw new ServiceException('邮箱已激活',\ErrorCode::LOGIC_ERROR);
        }
        
        Account::where('id', $info->id)->update([
            'email_activation' => Account::EMAIL_ACTIVATION_YES,
            'registered_at' => date('Y-m-d H:i:s')
        ]);
        $this->setRegistingEmail($email);
        $this->setSessionUid($info->id);
        $this->clearAttempts($this->getCacheKey($info->id, self::KEY_REGISTER_EMAIL_LIMIT));
        \LRedis::DEL($this->getCacheKey($email, self::KEY_EMAIL_ACTIVATION));
        return true;
    }

    /**
     * 生成邮箱验证的TOKEN
     *
     * @param unknown $email            
     * @return \App\Extensions\Common\str
     */
    public function generateEmailActivationToken($email,$significantInterval = 86400)
    {
        $key = $this->getCacheKey($email, self::KEY_EMAIL_ACTIVATION);
        $cached = \LRedis::GET($key);
        if($cached){
            return $cached;
        }
        return $this->generateTempAESToken($email, self::KEY_EMAIL_ACTIVATION,$significantInterval);
    }

    /**
     * 生成邮箱验证的TOKEN
     *
     * @param unknown $email            
     * @return \App\Extensions\Common\str
     */
    public function generatePasswdResetEmailToken($email,$significantInterval = 86400)
    {
        return $this->generateTempAESToken($email, self::KEY_PASSWORD_RESET_EMAIL_TOKEN,$significantInterval);
    }

    /**
     * 生成临时加密信息，用于 邮箱的激活验证、密码找回、短期授权TOKEN
     *
     * @param unknown $keyData            
     * @param unknown $cachedKeyMix            
     * @param unknown $significantInterval            
     * @return string
     */
    public function generateTempAESToken($keyData, $cachedKeyMix, $significantInterval = 86400)
    {
        $expiredAt = time() + $significantInterval;
        $str = $keyData . $this->separator . $expiredAt;
        $str = aes_encrypt($str);
        \LRedis::SETEX($this->getCacheKey($keyData, $cachedKeyMix), $significantInterval, $str);
        return $str;
    }

    /**
     * For Test Only
     *
     * @param unknown $email            
     */
    public static function getDebugCachedData($keyData, $type)
    {
        $thi = new static();
        return \LRedis::GET($thi->getCacheKey($keyData, $type));
    }

}


