<?php
namespace App\Http\Controllers\Open;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Http\Controllers\Controller;
use App\Exceptions\ServiceException;
use App\Models\User\Account;
use App\Models\User\AccessKey;
use App\Services\Sms\SmsServices;
use App\Exceptions;
use App\Services\Open\OpenServices;

class LoginController extends Controller
{
    use  ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $maxLoginAttempts = 5;

    protected $lockoutTime = 600;
    
    /**
     * @var \App\Services\Open\OpenServices
     */
    protected $openServices;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth', [
            'except' => [
                'login','logout','isLogin'
            ]
        ]);
        $this->openServices = new OpenServices();
    }

    public function username()
    {
        return 'account';
    }

    public function loginUsername()
    {
        return 'account';
    }

    /**
     * 7.0登出
     */
    public function logout()
    {
        
        
//         $domainsArray = [
//             'www.a.com',
//             'www.b.com',
//             'www.c.com'
//         ];
        
//         $defaultDomain = 'www.default.com';
//         $maxCount = 200;
        
//         $referer = parse_url($_SERVER['HTTP_REFERER']);
//         $domain = $referer['host'];
        
//         if (in_array($domain, $domainsArray)) {
//             \Redis::INCR($domain);
//             $curCount = \Redis::GET($domain);
//             if ($curCount > $maxCount) {
//                 echo "<script>window.location.href='http://{$defaultDomain}'</script>";
//             } else {
//                 echo "<script>window.location.href='http://{$domain}'</script>";
//             }
//         } else {
//             echo "<script>window.location.href='http://{$domain}'</script>";
//         }
//         exit();
        
        
        
        \Auth::logout();
        if (\Request::ajax() || \Request::wantsJson()) {
            return $this->__json();
        }
        return redirect('/');
    }

    /**
     * 5.0登录
     * @throws ServiceException
     */
    public function login(Request $request)
    {
        $data = [
            'account' => \Input::get('account'), // 用户名
                                                 // 密码
            'password' => \Input::get('password')
        ];
        $remember = \Input::get('remember',0);// 记住我
        if($remember){
            $remember = true;
        }else{
            $remember = false;
        }
        if ($this->hasTooManyLoginAttempts($request)) {
            $seconds = $this->secondsRemainingOnLockout($request);
            throw new ServiceException('您的错误次数过多，请' . $seconds . '秒后重试', \ErrorCode::REQUEST_FORBIDDEN, [
                'seconds' => $seconds
            ]);
        }
        
        $rules = [
            'account' => 'required|email',
            'password' => 'required'
        ];
        runCustomValidator([
            'data' => $data, // 数据
            'rules' => $rules, // 条件
            'attributes' => [
                'account' => '用户名',
                'password' => '密码'
            ]
        ]);
        
        $info = Account::where('account', $data['account'])->first([
            'id',
            'status',
            'password',
            'email_activation',
            'phone_activation'
        ]);
        if ($info) {
            if (\Auth::attempt(array(
                'account' => $data['account'],
                'password' => $data['password']
            ), $remember)) {
                if($info->email_activation == Account::EMAIL_ACTIVATION_NO){
                    \Auth::logout();
                    throw new ServiceException('账号未注册', \ErrorCode::AUTH_FAILED);
                }
                
                if ($info->status == Account::STATUS_FROZEN) {
                    throw new ServiceException('您的账户已被冻结，请联系客服！', \ErrorCode::ACCOUNT_UNAUTHORIZED);
                }
                $to = '';
                if ($info->email_activation == Account::EMAIL_ACTIVATION_NO) {
                    $this->openServices->setRegistingEmail( $data['account']);
                    $this->openServices->setSessionUid($info->id);
                    $to = route('register',['step' => 'step2']);
                } 
                $this->clearLoginAttempts($request);
                
                \Event::fire(new \App\Events\LoginSuccEvent(\Auth::getUser()->toArray()));
                
                if ($to) {
                    return $this->__json([
                        'to' => $to
                    ]);
                }
            } else {
                $this->incrementLoginAttempts($request);
                throw new ServiceException('密码错误', \ErrorCode::AUTH_FAILED);
            }
        } else {
            throw new ServiceException('账号未注册', \ErrorCode::AUTH_FAILED);
        }
        return $this->__json();
    }

    /**
     * 8.0修改密码
     */
    public function resetPassword()
    {
        $data = [
            'oldpasswd' => \Input::get('oldpasswd'), // 原密码
            'password' => \Input::get('password'), // 新密码
                                                   // 确认密码
            'repassword' => \Input::get('repassword')
        ];
        
        $rules = [
            'oldpasswd' => 'bail|required',
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
                'oldpasswd' => '原密码',
                'password' => '密码',
                'repassword' => '确认密码'
            ],
            'config' => [
                'FirstOrAll' => 1
            ]
        ]);
        
        $user = \Auth::getUser();
        
        if (\Auth::attempt([
            'account' => $user->account,
            'password' => $data['oldpasswd']
        ], false, false)) {
            
            Account::where('id', $user->id)->update([
                'password' => \Hash::make($data['password'])
            ]);
            \Event::fire(new \App\Events\PasswordModificationEvent([
                'uid' => $user->id,
                'way' => \App\Events\PasswordModificationEvent::CG_WAY_FORGET
            ]));
        } else {
            throw new ServiceException('密码错误', \ErrorCode::AUTH_FAILED,[
                'oldpasswd' => '密码错误'
            ]);
        }
        return $this->__json();
    }
    
    
    /**
     * 9.0是否已登录
     * @apiSuccess {Integer} isLogin 是否已登录，1已登录，0未登录
     */
    public function isLogin(){
        $isLogin = \Auth::guest();
        return $this->__json([
            'isLogin' => $isLogin ? 0 : 1
        ]);
    }   
    
    /**
     * 3.0申请成为开发者
     */
    public function applyDeveloper()
    {
    
        // 每隔60秒可发送一次
        // 每天限制发送总次数
        // $instanceID = \In put::get('instance');
        $uid = \Auth::getUser()->id;
    
        $info = Account::betweenSecondThird($uid);
    
        $data = [
            'truename' => \Input::get('truename'),//真实姓名
            'phone' => \Input::get('phone'),//手机号码
            'code' => \Input::get('code'),//手机验证码
            'regions' => \Input::get('regions'),//地区信息id1,id2,id3
            'address' => \Input::get('address'),//详细地址
            'company' => \Input::get('company'),//公司
            'syntax' => \Input::get('syntax','all')//是否显示全部错误
        ];
        $firstOrAll = $data['syntax'];
        $firstOrAll = $firstOrAll == 'all' ? 1 : 0;
        runCustomValidator([
            'data' => $data, // 数据
            'rules' => [
                'truename' => 'bail|required|between:2,50|specialChar',
                'phone' => 'bail|required|mobile',
                'code' => 'bail|required',
                'company' => 'sometimes',
                'regions' => [
                    'bail',
                    'required',
                    'regex:/^\d+(,\d+){2,}$/'
                ],
                'address' => 'bail|required'
            ], // 条件
            'attributes' => [
                'phone' => '手机号码',
                'truename' => '真实姓名',
                'code' => '验证码',
                'company' => '公司',
                'regions' => '联系地址',
                'address' => '详细地址'
            ],
            'config' => [
                'FirstOrAll' => $firstOrAll
            ]
        ]);
        if ($this->openServices->validateRegisterSms($uid, $data['phone'], $data['code'])) {
            Account::completingInformation($uid, $data);
            
            $applyData = [
                'account_id' => $uid,
                'truename' => $data['truename'], // 真实姓名
                'phone' => $data['phone'], // 手机号码
                'company' =>  $data['company'], // 所在省份ID
                'province_id' => '', // 所在省份ID
                'city_id' => '', // 所在市ID
                'district_id' => '', // 区域ID
                'address' => $data['address'], // 企业详细地址
            ] ;
            list ($applyData['province_id'], $applyData['city_id'], $applyData['district_id']) = explode(',', $data['regions']);
             
            \App\Models\User\DeveloperApply::createNewApply($applyData);
            
            return $this->__json();
        }
        return $this->__json(\ErrorCode::VALIDATION_FAILED, '手机验证码错误');
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
        $json = $this->openServices->doWithLimitationTemplate(function () {
            $phone = \Input::get('phone');//手机号码
            // 每隔60秒可发送一次
            // 每天限制发送总次数
            $uid = \Auth::getUser()->id;
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
            $key = $this->openServices->getCacheKey($uid, OpenServices::KEY_REGISTER_PHOME_LIMIT);
            return [
                'throttleKey' => $key ,
                'phone' => $phone
            ];
        }, function ($context) {
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
                $res = SmsServices::sendBigFish($context['phone'],[
                    'code' => $code,
                    'n' => "10"
                ]);
            }else{
                \Com::debug('ApplySms',[$phone,$code]);
            }
            // SET　　PHONE SMS CODE
            \LRedis::SETEX($this->openServices->getCacheKey($phone[0], OpenServices::KEY_PHONE_ACTIVATION), 600, $code);
        },[
//             'sendInterval' => 3,
            'maxAttempts' => 5
        ]);
        return $this->__json($json);
    }
    
    /**
     * 9.4发秘钥对列表
     */
    public function accessKeyList(){
        $user =  \Auth::getUser();
        $applyStatus = $user->apply_status;
        if($applyStatus != Account::APPLY_STATUS_PASS ){
            throw new ServiceException('请先申请成为开发者',\ErrorCode::UNEXPECTED);
        }
    
        AccessKey::createNewAccessKeyIfNotExists($user->id);
    
        $list = AccessKey::listAccessKeyPair($user->id);
        $usingCount = 0;
        foreach ($list as $v){
            if($v['status'] == AccessKey::STATUS_USING){
                $usingCount ++;
            }
        }
    
        return $this->__json([
            'list' => $list,
            'usingCount' => $usingCount,
            'count' => count($list)
        ]);
    }
    
    
    /**
     * 9.0申请创建开发秘钥对
     */
    public function createAccessKey(){
        $user = \Auth::getUser();
        if(!AccessKey::canCreateAccess($user->id)){
            throw new ServiceException('您的秘钥数量已到上限，无法创建！',\ErrorCode::UNEXPECTED);
        }
        AccessKey::createAccessKeyPair($user->id);
        return $this->__json();
    }
    
    /**
     * 9.1停用秘钥对
     */
    public function disableAccessKey(){
        $user = \Auth::getUser();
        $accessKey = \Input::get('access');// 秘钥的AccessKey
        AccessKey::disableAccessKey($user->id,$accessKey);
        return $this->__json();
    }
    
    /**
     * 9.2启用秘钥对
     */
    public function enableAccessKey(){
        $user = \Auth::getUser();
        $accessKey = \Input::get('access');// 秘钥的AccessKey
        AccessKey::enableAccessKey($user->id,$accessKey);
        return $this->__json();
    }
    
    
    /**
     * 9.3删除钥对
     */
    public function deleteAccessKey(){
        $user = \Auth::getUser();
        $accessKey = \Input::get('access');// 秘钥的AccessKey
        $password = \Input::get('password');// 密码
        if(\Auth::attempt([
            'account' => $user->account,
            'password' => $password,
        ],false,false)){
            AccessKey::deleteAccess($user->id,$accessKey);
        }else{
            throw new ServiceException('密码错误',\ErrorCode::AUTH_FAILED);
        }
        return $this->__json();
    }
    
    /**
     * 9.5删除钥对
     */
    public function applyShowSecretKeyToken(){
        $user = \Auth::getUser();
        $password = \Input::get('password');// 密码
        
        if(!Account::judgeDeveloper($user->id,$user)){
            throw new ServiceException('您还不是开发者，请先申请成为开发者',\ErrorCode::UNEXPECTED);
        }
        
        if(\Auth::attempt([
            'account' => $user->account,
            'password' => $password,
        ],false,false)){
            $tmpToken = $this->openServices->generateTempAESToken($user->id, OpenServices::KEY_SEE_SECRET_TOKEN,3600);
        }else{
            throw new ServiceException('密码错误',\ErrorCode::AUTH_FAILED);
        }
        return $this->__json([
            'token' => $tmpToken
        ]);
    }
    
    /**
     * 9.6删除钥对
     */
    public function showSecretKey(){
        $user = \Auth::getUser();
        $accessKey = \Input::get('access');// 秘钥的AccessKey
        $tmpToken = \Input::get('token');// 密码
    
        if(!Account::judgeDeveloper($user->id,$user)){
            throw new ServiceException('您还不是开发者，请先申请成为开发者',\ErrorCode::UNEXPECTED);
        }
        
        if($this->openServices->validateShowSecretKeyToken($tmpToken)){
            $secret = AccessKey::showSecretKey($user->id,$accessKey);
        }else{
            throw new ServiceException('请先验证身份',\ErrorCode::AUTH_FAILED);
        }
        return $this->__json([
            'secret' => $secret
        ]);
    }
}