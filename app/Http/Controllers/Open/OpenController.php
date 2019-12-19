<?php
namespace App\Http\Controllers\Open;

use App\Extensions\Verify\XmasCaptcha;
use App\Http\Controllers\Controller;
use App\Models\User\Account;
use App\Models\Common\Region;
use App\Exceptions\ServiceException;
use App\Services\Email\EmailSender;
use App\Services\Sms\SmsServices;
use App\Extensions\Common\Throttles;
use App\Services\Open\OpenServices;
use App\Models\User\DeveloperApply;
use App\Extensions\Gee\GeetestLib;

class OpenController extends Controller
{
    use Throttles;
    
    /**
     * @var \App\Services\Open\OpenServices
     */
    protected $openServices;
    
    public function __construct(){
        parent::__construct();
        $this->openServices = new OpenServices();
    }
    
    public function __call($method, $parameters)
    {
        if(method_exists($this->openServices, $method)){
            return call_user_func_array([$this->openServices,$method], $parameters);
        }
        return parent::__call($method, $parameters);
    }
    
    
//     public function startCaptcha(){
//         $type = \Input::get('type');
//         $GtSdk = new GeetestLib($type);
//         $user_id = "test";
//         $status = $GtSdk->pre_process($user_id);
//         session(['gtserver' => $status]);
//         session(['user_id' => $user_id]);
//         echo $GtSdk->get_response_str();
//     }
    
//     public function verifyLogin(){
//         $type = \Input::get('type');
//         $GtSdk = new GeetestLib($type);
//         $geetest_challenge = \Input::get('geetest_challenge');
//         $geetest_validate = \Input::get('geetest_validate');
//         $geetest_seccode = \Input::get('geetest_seccode');
        
//         $user_id = session('user_id');
//         $geeResult = false;
//         if (session('gtserver') == 1) {   //服务器正常
//             $result = $GtSdk->success_validate($geetest_challenge, $geetest_validate, $geetest_seccode, $user_id);
//             if ($result) {
//                 $geeResult = true;
//             }
//         }else{  //服务器宕机,走failback模式
//             if ($GtSdk->fail_validate($geetest_challenge,$geetest_validate,$geetest_seccode)) {
//                 $geeResult = true;
//             }
//         }
//         if($geeResult){
//             return redirect(route('api_login',\Input::only([
//                 'account','password','remember'
//             ])));
//         }
//         return \Response::json([
//             'geefail' => 1
//         ]);
//     }
    
    
    
    /**
     * 1.1获取验证码
     * @apiContentType image/png
     */
    public function captcha()
    {
        $config = [
            'foregroundColor' => '#1D2024',
            'width' => 100,
            'height' => 30,
            'backgroundColor' => "#FFFFFF"
        ];
        $captcha = new XmasCaptcha($config);
        $captcha->entry('register');
    }

    /**
     * 1.0用户注册
     * @apiSuccess {String} emailHome 注册邮箱的登录入口地址
     * @apiSuccess {Integer} interval 下次发送剩余时间（秒）
     * @apiSuccess {Integer} retriesLeft 剩余可发送次数 
     */
    public function register()
    {
        // 生成一个会话ID，关联UID
        // 存储、keyString
        // $instanceID = \In put::get('instance');
        $data = [
            'email' => \Input::get('email'),// string  邮箱
            'password' => \Input::get('password'),//密码
            'repassword' => \Input::get('repassword'),//确认密码
            'code' => \Input::get('code'),//int 验证码
            //错误返回格式，取值（signle|all），signle信息在msg字段中，,all则数据放在data中 
            'syntax' => \Input::get('syntax', 'all'),
            'sendemail' => \Input::get('sendemail', 'yes'),// 注册成功后是否发送邮件,取值（yes|no）,默认no，错误传值自动忽略
        ];
        $uid = $this->getSessionUid();
        // 已存在实例信息，则判断为重新填写邮箱的请求 
        if ($uid) {
            // 需验证账户状态 
            // 是否需要清除原先邮箱的验证码
            $ret = $this->resetEmailOps($uid, $data);
            $home = $this->getEmailHomeAddr($data['email']);
            $this->setRegistingEmail($data['email']);
            $retData = [
                'emailHome' => $home
            ];
            // TODO 
            // sendEmail and set a limitation without interval
            // set old email validation token invalid
//             if($ret['emailChanged']){
                // 清除旧的验证TOKEN
                \LRedis::DEL($this->getCacheKey($ret['oldEmail'], OpenServices::KEY_EMAIL_ACTIVATION));
                // 清除旧的发送时间间隔的限制
                \LRedis::DEL($this->getCacheKey($uid, OpenServices::KEY_REGISTER_EMAIL_LIMIT));
                
                if($data['sendemail'] == 'yes'){
                    $sendRet = $this->sendRegisterEmail();
                    $jsonData = json_decode($sendRet->content(),1);
                    $retData += array_get($jsonData, 'data',[]);
                }
//             }
            return $this->__json($retData);
        } else {
            // 注册 
            $uid = $this->openServices->registerOps($data);
            $instanceID = $this->setSessionUid($uid);
            
            $this->setRegistingEmail($data['email']);
            // $instanceID = $this->generateInstanceID($uid);
            $home = $this->getEmailHomeAddr($data['email']);
            $retData = [
                'instance' => $instanceID,
                'emailHome' => $home
            ];
            // TODO
            // sendEmail and set a limitation without interval
            
            if($this->getSetting(OpenServices::SET_USE_SESSION)){
                unset($retData['instance']);
            }
            if($data['sendemail'] == 'yes'){
                $sendRet = $this->sendRegisterEmail();
                $jsonData = json_decode($sendRet->content(),1);
                $retData += array_get($jsonData, 'data',[]);
            }
            
            return $this->__json($retData);
        }
    }
    
    /**
     * 3.1获取下层地区信息
     */
    public function region()
    {
        $fid = \Input::get('fid', 0);// Integer 上层ID
        $nul2Err = \Input::get('nul2err', 0);// Integer 为空是否返回错误（状态码非1）
        $data = Region::listSubitems($fid);
        if ($nul2Err) {
            if (! $data) {
                throw new ServiceException('没有子项',\ErrorCode::NOTHING_FOUND);
            }
        }
        return $this->__json($data);
    }

    /**
     * 2.1获取邮箱的登录链接
     */
    public function emailHomeAddr()
    {
        $email = \Input::get('email');// 邮箱
        
        runCustomValidator([
            'data' => [
                'email' => $email
            ], // 数据 
            'rules' => [
                'email' => 'bail|required|email'
            ], // 条件  
            'attributes' => [
                'email' => '邮箱'
            ]
        ]);
        
        return $this->__json([
            'home' => $this->getEmailHomeAddr($email)
        ]);
    }

    /**
     * 2.3邮箱激活检测
     */
    public function validateEmail()
    {
        $token = \Input::get('token');// token
        $resultStatus = true;
        try {
            $this->openServices->validateEmailActivationToken($token);
        }catch (\Exception $e){
            $resultStatus = false;
        }
        if (\Request::ajax() || \Request::wantsJson()) {
            if($resultStatus){
                return $this->__json();
            }
            throw $e;
        }
        if($resultStatus){
            $email = $this->openServices->getRegistingEmail();
            Account::setRegisterOverAndLogin($email);
            return redirect(route('register',['step' => 'step3']));
        }
        return error_400($e->getMessage());
    }

    /**
     * 6.1验证忘记密码邮件中的token
     */
    public function validateForgetEmail()
    {
        $token = \Input::get('token');// token
        $resultStatus = true;
        try {
            $this->validatePasswdResetEmailToken($token);
        }catch (\Exception $e){
            $resultStatus = false;
        }
        if (\Request::ajax() || \Request::wantsJson()) {
            if($resultStatus){
                return $this->__json();
            }
            throw $e;
        }
        if($resultStatus){
            return redirect(route('reset_password'));
        }
        return error_400($e->getMessage());
    }

    /**
     * 6.2重设密码
     */
    public function resetForgottenPasswd()
    {
        $data = [
            'token' => \Input::get('token'),// token
            'password' => \Input::get('password'),// 密码
            'repassword' => \Input::get('repassword')// 确认密码
        ];
        $rules = [
            'token' => 'required',
            'password' => [
                'bail',
                'required',
                'password'
            ],
            'repassword' => 'bail|required|same:password'
        ];
        runCustomValidator([
            'data' => $data, // 数据 
            'rules' => $rules, // 条件 
            'messages' => [
                'password.password' => '密码由字母、数字或者英文字符组成，最短6位，区分大小写'
            ], // 错误信息
            'attributes' => [
                'password' => '密码',
                'repassword' => '确认密码'
            ],
            'config' => [
                'FirstOrAll' => 1
            ]
        ]);
        list ($uid, $email) = $this->validatePasswdResetEmailToken($data['token']);
        \LRedis::DEL($this->getCacheKey($email, OpenServices::KEY_PASSWORD_RESET_EMAIL_TOKEN));
        \LRedis::DEL($this->getCacheKey($uid, OpenServices::KEY_PASSWORD_RESET_EMAIL_LIMIT));
        Account::where('id', $uid)->update([
            'password' => \Hash::make($data['password'])
        ]);
        $this->clearResetingToken();
        \Event::fire(new \App\Events\PasswordModificationEvent([
            'uid' => $uid,
            'way' => \App\Events\PasswordModificationEvent::CG_WAY_FORGET
        ]));
        return $this->__json();
    }

    /**
     * 2.0发送激活邮件
     * @apiSuccess {Integer} interval 下次发送剩余时间（秒）
     * @apiSuccess {Integer} retriesLeft 剩余可发送次数 
     * 
     * @apiError {Integer} restTime 发送间隔剩余时间（秒）
     * @apiError {Integer} seconds 禁止发送剩余时间（秒）
     */
    public function sendRegisterEmail()
    {
        $json = $this->doWithLimitationTemplate(function(){
            // 每隔60秒可发送一次
            // 每天限制发送总次数
            $uid = $this->getSessionUid();
            if (! $uid) {
                throw new ServiceException('未找到用户信息',\ErrorCode::VITAL_NOT_FOUND);
            }
            $info = Account::betweenFirstSecond($uid);
            
            // SRE short for send register email
            // $key = $this->getSendRegisterEmailCacheKey($uid);
            $key = $this->getCacheKey($uid, OpenServices::KEY_REGISTER_EMAIL_LIMIT);
            return [
                'throttleKey' => $key ,
                'info' => $info
            ];
        }, function($context){
            $info = $context['info'];
            
            // Q 发送激活邮件的限制的作用是什么，
            // A 防止恶意攻击，但也要保证用户体验
            // Q 那么 重新更改邮箱地址，需要重新发送邮件么，并且这种重新更改邮箱地址的行为有限制么
            // A 要重发，限制也是有的，限制的行为主要依赖于用户行为的恶意性诊断，如判断为恶意攻击就需要限制，这里不需要操作时间间隔
            
            // 抽象一种限制行为的操作
            
            // 激活邮件的作用是确定用户填入邮箱的所属关系，
            // 所以激活码需要和邮箱地址相关，和UID相关，（更改之前的链接点击效果,应为无效|新的用户注册该邮箱应为OK）
            
            $EmailSender = new EmailSender();
            // TODO : The Validate URL should be setted in admin
            $token = $this->openServices->generateEmailActivationToken($info->email,300);
            $param = [
                'email' => $info->email,
                'validateUrl' => route('api_validateEmail') . '?token=' . urlencode($token)
            ];
            
            if (! \App::environment('testing')) {
                // TODO LOG
                $ret = $EmailSender->sendEmail($info->email, EmailSender::EMAIL_REGISTER, $param);
            }
        },[
            'maxAttempts' => 20
        ]);
        return $this->__json($json);
    }

    /**
     * 6.0发送忘记密码邮件
     * @apiSuccess {Integer} interval 下次发送剩余时间（秒）
     * @apiSuccess {Integer} retriesLeft 剩余可发送次数 
     * 
     * @apiError {Integer} restTime 发送间隔剩余时间（秒）
     * @apiError {Integer} seconds 禁止发送剩余时间（秒）
     */
    public function sendForgetEmail()
    {
        
        
        
        $json = $this->openServices->doWithLimitationTemplate(function(){
            $data = [
                'email' => \Input::get('email')// 邮箱
            ];
            runCustomValidator([
                'data' => $data, // 数据
                'rules' => [
                    'email' => 'bail|required|email'
                ], // 条件
                'attributes' => [
                    'email' => '这'
                ]
            ]);
            
            $uid = Account::validForgetEmail($data['email']);
            
            // SRE short for send register email
            $key = $this->getCacheKey($uid, OpenServices::KEY_PASSWORD_RESET_EMAIL_LIMIT);
            return [
                'throttleKey' => $key ,
                'email' => $data['email'],
                'uid' => $uid,
            ];
        }, function($context){
            $email = $context['email'];
            $EmailSender = new EmailSender();
            // TODO : The Validate URL should be setted in admin
            $token = $this->generateTempAESToken($email, OpenServices::KEY_PASSWORD_RESET_EMAIL_TOKEN,3600);
            $param = [
                'username' => $email,
                'link' => route('api_validateForgetEmail') . '?token=' . urlencode($token)
            ];
            
            if (! \App::environment('testing')) {
                // TODO LOG
                $ret = $EmailSender->sendEmail($email, EmailSender::EMAIL_PASSWRD_RESET, $param);
            }
        },[
            'sendInterval' => 0,//每次尝试的间隔（秒）
            'maxAttempts' => 10,// 最大连续尝试次数
            'lockoutTime' => 86400,// 锁定时间（秒）
        ]);
        
        $json['home'] = $this->openServices->getEmailHomeAddr(\Input::get('email'));
        
        return $this->__json($json);
    }
    
    /**
     * 99.0开发者申请审核列表
     */
    public function developerApplyReviewList(){
        
        $pageSize = \Input::get('pageSize',10);// 分页每页数量
        $page = \Input::get('page',1); // 请求的页数
        
        $pageSize = intval($pageSize);
        $page = intval($page);
        
        $list = DeveloperApply::listApply([], $page, $pageSize);
        
        return $this->__json($list);
    }

    /**
     * 99.1审核开发者申请
     * @throws ServiceException
     */
    public function developerApplyReview(){
        $data['apply_id'] =  \Input::get('apply_id');//申请ID
        $data['deal'] = \Input::get('deal','refuse');// 处理结果 取值(agree|refuse)
        $data['reason'] = \Input::get('reason','');// 失败原因
        $data['note'] = \Input::get('note','');// 备注
        
        runCustomValidator([
            'data' => $data, // 数据
            'rules' => [
                'apply_id' => 'bail|required',
                'deal' => 'bail|required|in:agree,refuse',
                'reason' => 'bail|required_if:deal,refuse|min:10',
                'note' => 'sometimes|min:10',
            ], // 条件
            'attributes' => [
                'apply_id' => '申请ID',
                'deal' => '处理结果',
                'reason' => '不通过理由',
                'note' => '备注',
            ],
            'valueNames' => [
                'deal' => [
                    'refuse' => '不通过',
                    'agree' => '通过'
                ],
            ],
        ]);
        
        $toStatus = DeveloperApply::STATUS_FAIL;
        if($data['deal'] == 'agree'){
            $toStatus = DeveloperApply::STATUS_PASS;
        }
        DeveloperApply::reviewDeveloperApply($data['apply_id'], $toStatus,$data['reason'],$data['note']);     
        return $this->__json();
    }
    
}


