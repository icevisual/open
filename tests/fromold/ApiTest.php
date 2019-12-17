<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\Open\IndexController;
use App\Exceptions\ServiceException;
use App\Extensions\DataSeeder\Seeder;
use App\Extensions\DataSeeder\Seeds\EmailSeeds;
use App\Extensions\DataSeeder\Seeds\DbEmailSeeds;
use App\Extensions\DataSeeder\Seeds\TruenameSeeds;
use App\Extensions\DataSeeder\Seeds\PhoneSeeds;
use App\Extensions\DataSeeder\Seeds\DbPhoneSeeds;
use App\Services\Open\OpenServices;
use App\Extensions\DataSeeder\Seeds\DbAccessSeeds;
use App\Extensions\DataSeeder\Seeds\DbDevNameSecretPairSeeds;

class ApiTest extends TestRoutes
{

    public function clearAccount($email)
    {
        \App\Models\User\Account::where('email', $email)->delete();
    }

    public function registerProvider()
    {
        return [
            [
                [
                    'email' => Seeder::seed(EmailSeeds::class, 'new'),
                    'password' => '123456',
                    'repassword' => '123456',
                    'code' => 'CDhlv',
                    'syntax' => 'all',
                    'sendemail' => 'no'
                ]
            ]
        ];
    }

    protected function reqGetRegisterCaptcha()
    {
        $this->api_captcha();
        return OpenServices::getDebugCachedData(\Session::getId(), OpenServices::KEY_REGISTER_CAPTACH);
    }

    protected function getRegisterActivationEmailToken($email)
    {
        return OpenServices::getDebugCachedData($email, OpenServices::KEY_EMAIL_ACTIVATION);
    }

    protected function getForgetEmailToken($email)
    {
        return OpenServices::getDebugCachedData($email, OpenServices::KEY_PASSWORD_RESET_EMAIL_TOKEN);
    }

    protected function getPhoneSmsCode($phone)
    {
        return OpenServices::getDebugCachedData($phone, OpenServices::KEY_PHONE_ACTIVATION);
    }

    protected function reqGetPhoneSmsCode($phone)
    {
        $this->api_sendApplySms($phone);
        return OpenServices::getDebugCachedData($phone, OpenServices::KEY_PHONE_ACTIVATION);
    }

    protected function getUnActivtionAccount()
    {
        $data = \App\Models\User\Account::select([
            'email'
        ])->where('email_activation', \App\Models\User\Account::EMAIL_ACTIVATION_NO)->first();
        if ($data) {
            return $data['email'];
        }
        throw new \Exception('VITAL_NOT_FOUND');
    }

    protected function getActivtionAccount()
    {
        $data = \App\Models\User\Account::select([
            'email'
        ])->where('email_activation', \App\Models\User\Account::EMAIL_ACTIVATION_YES)->first();
        if ($data) {
            return $data['email'];
        }
        throw new \Exception('VITAL_NOT_FOUND');
    }

    protected function getDeveloperAccount()
    {
        $data = \App\Models\User\Account::select([
            'email'
        ])->where('email_activation', \App\Models\User\Account::EMAIL_ACTIVATION_YES)
            ->where('apply_status', \App\Models\User\Account::APPLY_STATUS_PASS)
            ->first();
        if ($data) {
            return $data['email'];
        }
        throw new \Exception('VITAL_NOT_FOUND');
    }

    protected function getNotDeveloperAccount()
    {
        $data = \App\Models\User\Account::select([
            'email'
        ])->where('email_activation', \App\Models\User\Account::EMAIL_ACTIVATION_YES)
            ->whereNotIn('apply_status', [
            \App\Models\User\Account::APPLY_STATUS_PASS,
            \App\Models\User\Account::APPLY_STATUS_APPLYING
        ])
            ->first();
        if ($data) {
            return $data['email'];
        }
        throw new \Exception('VITAL_NOT_FOUND');
    }

    /**
     * @group mqtt
     */
    public function testMqtt001()
    {
        $data = [
            'datetime' => '14:55:22.211',
            'errortype' => 'ResponesTimeOut',
            'errormsg' => 'Respones Time Out In 5 seconds',
            'developer_access' => 'NF3DyoBL8bjT6sjkM9a5',
            'device_name' => 'nnnnnnnnnn',
            'device_access' => 'aaaaaaaaaa',
            'api_name' => 'playSmell',
            'req_seq' => '1481717462',
            'req_timeout' => '5',
            'req_params' => [
                'actions' => [
                    [
                        'bottle' => '000000001',
                        'duration' => '3',
                        'power' => '5'
                    ]
                ]
            ],
            'loopPlayConfig' => [
                'expectedLoopTime' => '6',
                'interval' => '1',
                'loopTime' => '6',
                'flags' => [
                    '000000001' => '0',
                    '000000002' => '0',
                    '000000003' => '0'
                ],
                'timer' => '88'
            ]
        ];
        
        $ret = $this->api_mqtt_errorReport([
            'developer_access' => 'NF3DyoBL8bjT6sjkM9a5', //String 开发者 access
            'developer_pass' => totp_secret_encode('ADJSnMGU2riyhOWagkVb'), //String 开发者 secret TOTP 结果
            'error' => $data, //String 错误信息
        ]);
        
        $this->output($ret);
        
        exit;
        
        
        \DB::beginTransaction();
        $accessPair = Seeder::seed(DbAccessSeeds::class, 'new'); // String 用户名
        $devicePair = Seeder::seed(DbDevNameSecretPairSeeds::class, 'new'); // String 用户名
        $unique_token = 'unique_token';
        {
            $ret = $this->api_mqtt_listBindedDevices([
                'developer_access' => 'TCeOp0gzzrWhAMoOa3Mm', // String 开发者access
                'developer_pass' => totp_secret_encode('POVX1lgIvo8q1KHYpoD9'), // String 开发者 secret TOTP 结果
                'unique_token' => $unique_token
            ] // String 安装 APP 的设备的唯一标识
);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            $this->assertEquals(count(array_get($ret, 'data')), 1);
            
            $ret = $this->api_mqtt_listBindedDevices([
                'developer_access' => 'TCeOp0gzzrWhAMoOa3Mm', // String 开发者access
                'developer_pass' => totp_secret_encode('POVX1lgIvo8q1KHYpoD9')
            ] // String 开发者 secret TOTP 结果
);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            $this->assertEquals(count(array_get($ret, 'data')), 1);
            $this->output($ret);
            
            $ret = $this->api_mqtt_listBindedDevices([
                'developer_access' => 'testTCeOp0gzzrWhAMoOa3Mm', // String 开发者access
                'developer_pass' => 'asdd'
            ] // String 开发者 secret TOTP 结果
);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            $this->output($ret);
            
            $ret = $this->api_mqtt_listBindedDevices([
                'developer_access' => 'testBwFSfU0uMxodVBcAAGYs', // String 开发者access
                'developer_pass' => 'asdd'
            ] // String 开发者 secret TOTP 结果
);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            $this->output($ret);
        }
        
        $acc = 'BwFSfU0uMxodVBcAAGYs';
        { // 测试后门
            $ret = $this->api_mqtt_bindDevice([
                'developer_access' => 'test' . $acc, // String 开发者access
                'developer_pass' => '123456', // String 开发者 secret TOTP 结果
                'device_name' => $devicePair['name'], // String 设备名称
                'device_pass' => 'asdd'
            ] // String 设备密码
);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            $this->assertEquals($devicePair['access'], array_get($ret, 'data.access_key'));
            
            $ret = $this->api_mqtt_bindDevice([
                'developer_access' => $acc, // String 开发者access
                'developer_pass' => '123456', // String 开发者 secret TOTP 结果
                'device_name' => $devicePair['name'], // String 设备名称
                'device_pass' => 'asdd'
            ] // String 设备密码
);
            $this->assertCodeEqual($ret, \ErrorCode::VALIDATION_FAILED);
        }
        
        { // 解绑测试
            $ret = $this->api_mqtt_bindDevice([
                'device_name' => '5-vr', // String 设备名称
                'device_pass' => totp_secret_encode('9c32626fc323'), // String 设备 secret TOTP 结果
                
                'developer_access' => $accessPair['access'], // String 开发者access
                'developer_pass' => totp_secret_encode($accessPair['secret'])
            ] // String 开发者 secret TOTP 结果
);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            $this->output($ret);
            
            $ret = $this->api_mqtt_bindDevice([
                'device_name' => '5-vr', // String 设备名称
                'device_pass' => totp_secret_encode('9c32626fc323'), // String 设备 secret TOTP 结果
                'unique_token' => $unique_token, // String 安装 APP 的设备的唯一标识
                'developer_access' => $accessPair['access'], // String 开发者access
                'developer_pass' => totp_secret_encode($accessPair['secret'])
            ] // String 开发者 secret TOTP 结果
);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            
            $ret = $this->api_mqtt_listBindedDevices([
                'developer_access' => $accessPair['access'], // String 开发者access
                'developer_pass' => totp_secret_encode('POVX1lgIvo8q1KHYpoD9'), // String 开发者 secret TOTP 结果
                'unique_token' => $unique_token
            ] // String 安装 APP 的设备的唯一标识
);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            $this->assertEquals(count(array_get($ret, 'data')), 1);
            $this->output($ret);
            $first_device_access = array_get($ret, 'data.0.device_access');
            
            $ret = $this->api_mqtt_deviceNameCheck([
                'device_name' => '5-vr', // String 设备名称
                'unique_token' => $unique_token, // String 安装 APP 的设备的唯一标识
                'developer_access' => $accessPair['access'], // String 开发者access
                'developer_pass' => totp_secret_encode($accessPair['secret'])
            ] // String 开发者 secret TOTP 结果
);
            $this->output($ret);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            $this->assertEquals(1, array_get($ret, 'data.binded'));
            
            $ret = $this->api_mqtt_unbindDevice([
                'developer_access' => $accessPair['access'], // String 开发者access
                'developer_pass' => totp_secret_encode($accessPair['secret']), // String 开发者 secret TOTP 结果
                'device_access' => $first_device_access, // String 设备名称
                'unique_token' => $unique_token
            ] // String 安装 APP 的设备的唯一标识
);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            $this->output($ret);
            
            $ret = $this->api_mqtt_listBindedDevices([
                'developer_access' => $accessPair['access'], // String 开发者access
                'developer_pass' => totp_secret_encode('POVX1lgIvo8q1KHYpoD9'), // String 开发者 secret TOTP 结果
                'unique_token' => $unique_token
            ] // String 安装 APP 的设备的唯一标识
);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            $this->assertEquals(count(array_get($ret, 'data')), 0);
            $this->output($ret);
            
            $ret = $this->api_mqtt_listBindedDevices([
                'developer_access' => $accessPair['access'], // String 开发者access
                'developer_pass' => totp_secret_encode('POVX1lgIvo8q1KHYpoD9')
            ] // String 开发者 secret TOTP 结果
);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            $this->assertEquals(count(array_get($ret, 'data')), 1);
            $this->output($ret);
            
            return;
            
            $ret = $this->api_mqtt_deviceNameCheck([
                'device_name' => '5-vr', // String 设备名称
                'developer_access' => $accessPair['access'], // String 开发者access
                'developer_pass' => totp_secret_encode($accessPair['secret'])
            ] // String 开发者 secret TOTP 结果
);
            $this->output($ret);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            $this->assertEquals(1, array_get($ret, 'data.binded'));
            
            // return ;
            
            $ret = $this->api_mqtt_listBindedDevices([
                'developer_access' => $accessPair['access'], // String 开发者access
                'developer_pass' => totp_secret_encode($accessPair['secret'])
            ] // String 开发者 secret TOTP 结果
);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            $this->output($ret);
            
            $ret = $this->api_mqtt_unbindDevice([
                'developer_access' => $accessPair['access'], // String 开发者access
                'developer_pass' => totp_secret_encode($accessPair['secret']), // String 开发者 secret TOTP 结果
                'device_access' => array_get($ret, 'data.0.device_access')
            ] // String 设备名称
);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        }
        // return;
        
        {
            $ret = $this->api_mqtt_deviceNameCheck([
                'device_name' => '123312'
            ] // String 设备名称
);
            $this->assertCodeEqual($ret, \ErrorCode::VALIDATION_FAILED);
            
            $ret = $this->api_mqtt_deviceNameCheck([
                'device_name' => $devicePair['name'], // String 设备名称
                'developer_access' => $accessPair['access'], // String 开发者access
                'developer_pass' => totp_secret_encode($accessPair['secret'])
            ] // String 开发者 secret TOTP 结果
);
            $this->output($ret);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            $this->assertEquals(0, array_get($ret, 'data.binded'));
        }
        return;
        // \DB::beginTransaction();
        
        { // 验证参数
            $ret = $this->api_mqtt_bindDevice();
            $this->assertCodeEqual($ret, \ErrorCode::VALIDATION_FAILED);
            
            $ret = $this->api_mqtt_bindDevice([
                'device_name' => $devicePair['name'], // String 设备名称
                'device_pass' => $devicePair['secret']
            ] // String 设备密码
);
            $this->assertCodeEqual($ret, \ErrorCode::VALIDATION_FAILED);
            
            $ret = $this->api_mqtt_bindDevice([
                'device_name' => $devicePair['name'], // String 设备名称
                'device_pass' => $devicePair['secret']
            ] // String 设备密码
);
            $this->assertCodeEqual($ret, \ErrorCode::VALIDATION_FAILED);
        }
        
        { // 流程错误
            $ret = $this->api_mqtt_acl([
                'access' => '1', // String 订阅 QOS
                'username' => $accessPair['access'], // String 登录用户名
                'clientid' => $accessPair['access'], // String 连接的 clientid
                'ipaddr' => '192.168.1.1', // String IP 地址
                'topic' => '/' . $accessPair['access']
            ] // String 订阅的 Topic
);
            $status = $this->response->getStatusCode();
            $this->assertEquals(200, $status);
            
            $ret = $this->api_mqtt_acl([
                'access' => '1', // String 订阅 QOS
                'username' => $accessPair['access'], // String 登录用户名
                'clientid' => $accessPair['access'], // String 连接的 clientid
                'ipaddr' => '192.168.1.1', // String IP 地址
                'topic' => '/' . $devicePair['access']
            ] // String 订阅的 Topic
);
            $status = $this->response->getStatusCode();
            $this->assertEquals(401, $status);
            
            $ret = $this->api_mqtt_acl([
                'access' => '1', // String 订阅 QOS
                'username' => $accessPair['access'], // String 登录用户名
                'clientid' => $accessPair['access'], // String 连接的 clientid
                'ipaddr' => '192.168.1.1', // String IP 地址
                'topic' => '/' . $devicePair['access'] . '/resp'
            ] // String 订阅的 Topic
);
            $status = $this->response->getStatusCode();
            $this->assertEquals(401, $status);
        }
        
        { // 数据错误
            $ret = $this->api_mqtt_bindDevice([
                'developer_access' => $accessPair['access'] . 'asdd', // String 开发者access
                'developer_pass' => totp_secret_encode($accessPair['secret']), // String 开发者 secret TOTP 结果
                'device_name' => $devicePair['name'], // String 设备名称
                'device_pass' => totp_secret_encode($devicePair['secret'])
            ] // String 设备密码
);
            $this->assertCodeEqual($ret, \ErrorCode::VITAL_NOT_FOUND);
            
            $ret = $this->api_mqtt_bindDevice([
                'developer_access' => $accessPair['access'], // String 开发者access
                'developer_pass' => totp_secret_encode($accessPair['secret']), // String 开发者 secret TOTP 结果
                'device_name' => $devicePair['name'], // String 设备名称
                'device_pass' => totp_secret_encode($devicePair['secret']) . 'asdd'
            ] // String 设备密码
);
            $this->assertCodeEqual($ret, \ErrorCode::VALIDATION_FAILED);
        }
        
        { // 正确流程
            $ret = $this->api_mqtt_bindDevice([
                'developer_access' => $accessPair['access'], // String 开发者access
                'developer_pass' => totp_secret_encode($accessPair['secret']), // String 开发者 secret TOTP 结果
                'device_name' => $devicePair['name'], // String 设备名称
                'device_pass' => totp_secret_encode($devicePair['secret'])
            ] // String 设备密码
);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            $this->assertEquals($devicePair['access'], array_get($ret, 'data.access_key'));
            
            $ret = $this->api_mqtt_acl([
                'access' => '1', // String 订阅 QOS
                'username' => $accessPair['access'], // String 登录用户名
                'clientid' => $accessPair['access'], // String 连接的 clientid
                'ipaddr' => '192.168.1.1', // String IP 地址
                'topic' => '/' . $devicePair['access']
            ] // String 订阅的 Topic
);
            $status = $this->response->getStatusCode();
            $this->assertEquals(200, $status);
            
            $ret = $this->api_mqtt_acl([
                'access' => '1', // String 订阅 QOS
                'username' => $accessPair['access'], // String 登录用户名
                'clientid' => $accessPair['access'], // String 连接的 clientid
                'ipaddr' => '192.168.1.1', // String IP 地址
                'topic' => '/' . $devicePair['access'] . '/resp'
            ] // String 订阅的 Topic
);
            $status = $this->response->getStatusCode();
            $this->assertEquals(200, $status);
        }
        
        return;
        $oldEmail = $data['email'];
        $newEmail = Seeder::seed(EmailSeeds::class, 'new');
        
        $data['code'] = $this->reqGetRegisterCaptcha();
        $data['sendemail'] = 'yes';
        $ret = $this->api_register($data);
        // $this->output($ret);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        $this->assertEquals(1, array_get($ret, 'data.retriesLeft'));
        
        $data['sendemail'] = 'no'; // 加入参数sendemail，表示是否注册完发送邮件
        $data['code'] = $this->reqGetRegisterCaptcha();
        $ret = $this->api_register($data);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        // $this->output($ret);
        
        $data['sendemail'] = 'yes';
        
        $ret = $this->api_sendRegisterEmail();
        $this->assertCodeEqual($ret, \ErrorCode::REQUEST_TOO_OFFEN);
        // $this->output($ret);
        
        $oldToken = $this->getRegisterActivationEmailToken($oldEmail);
        // $this->output($oldToken);
        // 更改邮箱后发送邮件计入总次数，清除旧的token
        $data['email'] = $newEmail;
        $data['code'] = $this->reqGetRegisterCaptcha();
        $ret = $this->api_register($data);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        $this->assertEquals(0, array_get($ret, 'data.retriesLeft'));
        
        $ret = $this->api_validateEmail([
            'token' => $oldToken
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::UNEXPECTED);
        
        $newToken = $this->getRegisterActivationEmailToken($newEmail);
        // $this->output($newToken);
        $ret = $this->api_validateEmail([
            'token' => $newToken
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
    }

    /**
     * @group register01
     * @dataProvider registerProvider
     */
    public function testRegister01($data)
    {
        \Config::set('testing.sendInterval', 1);
        \Config::set('testing.maxAttempts', 2);
        \Config::set('testing.lockoutTime', 1);
        
        $oldEmail = $data['email'];
        $newEmail = Seeder::seed(EmailSeeds::class, 'new');
        
        $data['code'] = $this->reqGetRegisterCaptcha();
        $data['sendemail'] = 'yes';
        $ret = $this->api_register($data);
        // $this->output($ret);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        $this->assertEquals(1, array_get($ret, 'data.retriesLeft'));
        
        $data['sendemail'] = 'no'; // 加入参数sendemail，表示是否注册完发送邮件
        $data['code'] = $this->reqGetRegisterCaptcha();
        $ret = $this->api_register($data);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        // $this->output($ret);
        
        $data['sendemail'] = 'yes';
        
        $ret = $this->api_sendRegisterEmail();
        $this->assertCodeEqual($ret, \ErrorCode::REQUEST_TOO_OFFEN);
        // $this->output($ret);
        
        $oldToken = $this->getRegisterActivationEmailToken($oldEmail);
        // $this->output($oldToken);
        // 更改邮箱后发送邮件计入总次数，清除旧的token
        $data['email'] = $newEmail;
        $data['code'] = $this->reqGetRegisterCaptcha();
        $ret = $this->api_register($data);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        $this->assertEquals(0, array_get($ret, 'data.retriesLeft'));
        
        $ret = $this->api_validateEmail([
            'token' => $oldToken
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::UNEXPECTED);
        
        $newToken = $this->getRegisterActivationEmailToken($newEmail);
        // $this->output($newToken);
        $ret = $this->api_validateEmail([
            'token' => $newToken
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
    }

    /**
     * @group registerEmailLimit
     * @dataProvider registerProvider
     */
    public function testRegisterEmailLimit($data)
    {
        \Config::set('testing.sendInterval', 1);
        \Config::set('testing.maxAttempts', 2);
        \Config::set('testing.lockoutTime', 1);
        
        $data['code'] = $this->reqGetRegisterCaptcha();
        $ret = $this->api_register($data);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        
        $ret = $this->api_sendRegisterEmail();
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        $this->assertEquals(1, array_get($ret, 'data.retriesLeft'));
        
        $ret = $this->api_sendRegisterEmail();
        $this->assertCodeEqual($ret, \ErrorCode::REQUEST_TOO_OFFEN);
        
        $this->sleep(2);
        
        $ret = $this->api_sendRegisterEmail();
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        $this->assertEquals(0, array_get($ret, 'data.retriesLeft'));
        
        $this->sleep(2);
        
        $ret = $this->api_sendRegisterEmail();
        $this->assertCodeEqual($ret, \ErrorCode::REQUEST_FORBIDDEN);
        
        $this->sleep(2);
        $ret = $this->api_sendRegisterEmail();
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        $this->assertEquals(1, array_get($ret, 'data.retriesLeft'));
    }

    /**
     * @group registerOk
     * @dataProvider registerProvider
     */
    public function testRegisterOk($data)
    {
        \Config::set('testing.sendInterval', 2);
        \Config::set('testing.maxAttempts', 2);
        \Config::set('testing.lockoutTime', 1);
        
        $data['code'] = $this->reqGetRegisterCaptcha();
        $ret = $this->api_register($data);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        
        $ret = $this->api_emailHomeAddr([
            'email' => $data['email']
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        
        $ret = $this->api_sendRegisterEmail();
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        $token = $this->getRegisterActivationEmailToken($data['email']);
        $this->sleep(3);
        
        $ret = $this->api_sendRegisterEmail();
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        $token1 = $this->getRegisterActivationEmailToken($data['email']);
        
        $this->assertEquals($token, $token1);
        
        $ret = $this->api_validateEmail([
            'token' => $token
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        
        $ret = $this->api_validateEmail([
            'token' => $token
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::UNEXPECTED);
    }

    /**
     * @group password
     */
    public function testLoginaction()
    {
        $pwd = '123456';
        $ret = $this->api_login([
            'account' => Seeder::seed(DbEmailSeeds::class, 'new'), // String 用户名
            'password' => $pwd, // String 密码
            'remember' => $pwd
        ] // String 记住我
);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        
        $ret = $this->api_password([
            'oldpasswd' => $pwd,
            'password' => $pwd,
            'repassword' => $pwd
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        
        $ret = $this->api_logout();
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        
        $ret = $this->api_password([
            'oldpasswd' => $pwd,
            'password' => $pwd,
            'repassword' => $pwd
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::UNAUTHORIZED);
    }

    /**
     * @group login
     */
    public function testSlogin()
    {
        $pwd = '123456';
        $ret = $this->api_login([
            'account' => Seeder::seed(DbEmailSeeds::class, 'new'), // String 用户名
            'password' => $pwd
        ] // String 密码
);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        $ret = $this->api_login([
            'account' => Seeder::seed(DbEmailSeeds::class, 'new'), // String 用户名
            'password' => $pwd . 'asd'
        ] // String 密码
);
        $this->assertCodeEqual($ret, \ErrorCode::AUTH_FAILED);
    }

    /**
     * @group forget
     * @dataProvider registerProvider
     */
    public function testForget($data)
    {
        \Config::set('testing.sendInterval', 2);
        \Config::set('testing.maxAttempts', 2);
        \Config::set('testing.lockoutTime', 1);
        
        \DB::beginTransaction();
        // 不是合法邮箱
        $email = '1231232444';
        $ret = $this->api_sendForgetEmail([
            'email' => $email
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::VALIDATION_FAILED);
        
        // 不存在
        $email = Seeder::seed(EmailSeeds::class, 'new');
        $ret = $this->api_sendForgetEmail([
            'email' => $email
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::VITAL_NOT_FOUND);
        
        // 未激活
        $email = $this->getUnActivtionAccount(); // jinyanlin@renrenfenqi.com 未激活
        $ret = $this->api_sendForgetEmail([
            'email' => $email
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::LOGIC_ERROR);
        // 限制测试
        $email = $this->getActivtionAccount(); // 已激活
        $ret = $this->api_sendForgetEmail([
            'email' => $email
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        $this->assertEquals(1, array_get($ret, 'data.retriesLeft'));
        
        $ret = $this->api_sendForgetEmail([
            'email' => $email
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::REQUEST_TOO_OFFEN);
        $this->sleep(2); // sendInterval
        
        \Config::set('testing.sendInterval', 0);
        
        $ret = $this->api_sendForgetEmail([
            'email' => $email
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        $this->assertEquals(0, array_get($ret, 'data.retriesLeft'));
        
        // sleep(2); // sendInterval
        
        $ret = $this->api_sendForgetEmail([
            'email' => $email
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::REQUEST_FORBIDDEN);
        $this->sleep(2); // lockoutTime 1
        
        $ret = $this->api_sendForgetEmail([
            'email' => $email
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        $this->assertEquals(1, array_get($ret, 'data.retriesLeft'));
        
        $oldpwd = '123456';
        $ret = $this->api_login([
            'account' => $email,
            'password' => $oldpwd
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK); // 旧密码可登陆
        
        $ret = $this->api_validateForgetEmail([
            'token' => $this->getForgetEmailToken($email)
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        
        $newpwd = 'a123456';
        $ret = $this->api_resetForgottenPasswd([
            'token' => $this->getForgetEmailToken($email),
            'password' => $newpwd,
            'repassword' => $newpwd . 'asd'
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::VALIDATION_FAILED);
        
        $ret = $this->api_resetForgottenPasswd([
            'token' => $this->getForgetEmailToken($email),
            'password' => '123',
            'repassword' => '123'
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::VALIDATION_FAILED);
        
        $ret = $this->api_resetForgottenPasswd([
            'token' => $this->getForgetEmailToken($email),
            'password' => $newpwd,
            'repassword' => $newpwd
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        
        $ret = $this->api_login([
            'account' => $email,
            'password' => $oldpwd
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::AUTH_FAILED);
        
        $ret = $this->api_login([
            'account' => $email,
            'password' => $newpwd
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        // /Z2Ty1xsE@hotmail.qq
        // 邮箱错误
        // 未注册
        // 未激活
        
        // jinyanlin@renrenfenqi.com
        
        // 频繁、禁止、验证、更改密码、登录成功
    }

    /**
     * @group registerError
     */
    public function testRegisterError11()
    {
        $registered = Seeder::seed(DbEmailSeeds::class, 'new');
        $data = [
            'email' => Seeder::seed(EmailSeeds::class, 'new'),
            'password' => '123456',
            'repassword' => '123456',
            'syntax' => 'all'
        ];
        $data['email'] = $registered;
        $data['password'] = '123';
        $data['code'] = $this->reqGetRegisterCaptcha();
        $ret = $this->api_register($data);
        $this->assertCodeEqual($ret, \ErrorCode::VALIDATION_FAILED);
    }

    /**
     * @group register02
     * @dataProvider registerProvider
     */
    public function testRegisterError($data)
    {
        \Config::set('testing.sendInterval', 2);
        \Config::set('testing.maxAttempts', 1);
        \Config::set('testing.lockoutTime', 2);
        
        // $this->markTestSkipped('Skipped as there is no need');
        $data['code'] = $this->reqGetRegisterCaptcha();
        $ret = $this->api_register($data);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        
        $data['email'] = Seeder::seed(EmailSeeds::class, 'new');
        $data['code'] = $this->reqGetRegisterCaptcha();
        $ret = $this->api_register($data);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        
        $ret = $this->api_sendRegisterEmail();
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        
        $token = $this->getRegisterActivationEmailToken($data['email']);
        
        $ret = $this->api_sendRegisterEmail();
        $this->assertCodeEqual($ret, \ErrorCode::REQUEST_TOO_OFFEN);
        // $this->output($ret);
        // TODO 语义化
        $data['email'] = Seeder::seed(EmailSeeds::class, 'new');
        $data['code'] = $this->reqGetRegisterCaptcha();
        $ret = $this->api_register($data);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        
        $data['email'] = Seeder::seed(EmailSeeds::class, 'new');
        $data['code'] = $this->reqGetRegisterCaptcha();
        $ret = $this->api_register($data);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        
        // 更改邮箱后，邮件发送间隔限制清除，只记录次数
        $ret = $this->api_sendRegisterEmail();
        $this->assertCodeEqual($ret, \ErrorCode::REQUEST_FORBIDDEN);
        
        $this->sleep(3);
        
        $ret = $this->api_sendRegisterEmail();
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        // 旧的TOKEN无效
        $ret = $this->api_validateEmail([
            'token' => $token
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::UNEXPECTED);
        
        $token = $this->getRegisterActivationEmailToken($data['email']);
        
        $ret = $this->api_validateEmail([
            'token' => $token
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
    }

    /**
     * @group developerApply
     */
    public function testDeveloperApply()
    {
        // Key Analysis
        $phone = Seeder::seed(PhoneSeeds::class, 'new');
        
        $ret = $this->api_sendApplySms([
            'phone' => $phone
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::UNAUTHORIZED);
        $ret = $this->api_applyDeveloper([
            'truename' => Seeder::seed(TruenameSeeds::class, 'new'), // String 真实姓名
            'phone' => $phone, // String 手机号码
            'code' => $this->getPhoneSmsCode($phone), // String 手机验证码
            'regions' => '1,2,3', // String 地区信息id1,id2,id3
            'address' => 'asdasd', // String 详细地址
            'company' => 'adsasd', // String 公司
            'syntax' => ''
        ] // String 是否显示全部错误
);
        $this->assertCodeEqual($ret, \ErrorCode::UNAUTHORIZED);
        
        $ret = $this->api_login([
            'account' => $this->getNotDeveloperAccount(),
            'password' => '123456'
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        
        $ret = $this->api_sendApplySms([
            'phone' => $phone
        ]);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        $ret = $this->api_applyDeveloper([
            'truename' => Seeder::seed(TruenameSeeds::class, 'new'), // String 真实姓名
            'phone' => $phone, // String 手机号码
            'code' => $this->getPhoneSmsCode($phone), // String 手机验证码
            'regions' => '1,2,3', // String 地区信息id1,id2,id3
            'address' => 'asdasd', // String 详细地址
            'company' => 'adsasd', // String 公司
            'syntax' => ''
        ] // String 是否显示全部错误
);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
    }

    /**
     * @group review
     */
    public function testDeveloperApplyReview()
    {
        // \DB::beginTransaction();
        $ret = $this->api_developerApplyReviewList();
        
        if (! empty($ret['data']['list'])) {
            $apply = array_shift($ret['data']['list']);
            $applyID = $apply['apply_id'];
            
            $reviewData = [
                'apply_id' => $applyID, // String 申请ID
                'deal' => 'agree1', // String 处理结果 取值(agree|refuse)
                'reason' => 'asd', // String 失败原因
                'note' => ''
            ] // String 备注
;
            $applyRet = $this->api_developerApplyReview($reviewData);
            $this->assertCodeEqual($applyRet, \ErrorCode::VALIDATION_FAILED);
            $reviewData['deal'] = 'refuse';
            $reviewData['reason'] = '';
            $applyRet = $this->api_developerApplyReview($reviewData);
            $this->assertCodeEqual($applyRet, \ErrorCode::VALIDATION_FAILED);
            $reviewData['deal'] = 'agree';
            $applyRet = $this->api_developerApplyReview($reviewData);
            $this->assertCodeEqual($applyRet, \ErrorCode::STATUS_OK);
            $applyRet = $this->api_developerApplyReview($reviewData);
            $this->assertCodeEqual($applyRet, \ErrorCode::LOGIC_ERROR);
        } else {
            $this->output('Nothing To Review');
        }
    }

    /**
     * @group accesskey
     */
    public function testAccessKey()
    {
        $pwd = '123456';
        $email = $this->getNotDeveloperAccount();
        $ret = $this->api_login([
            'account' => $email, // String 用户名
            'password' => $pwd
        ] // String 密码
);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        $ret = $this->api_accessKeyList();
        $this->assertCodeEqual($ret, \ErrorCode::UNEXPECTED);
        
        $email = $this->getDeveloperAccount();
        $ret = $this->api_login([
            'account' => $email, // String 用户名
            'password' => $pwd
        ] // String 密码
);
        $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        $accessRet = $this->api_accessKeyList();
        $this->assertCodeEqual($accessRet, \ErrorCode::STATUS_OK);
        $this->assertCodeEqual($accessRet, \ErrorCode::STATUS_OK);
        // $this->output($accessRet);
        
        $count = array_get($accessRet, 'data.count');
        $usingCount = array_get($accessRet, 'data.usingCount');
        $accessList = array_get($accessRet, 'data.list');
        
        if ($count == 1) {
            // $this->markTestSkipped('skip');
            $this->assertEquals(array_get($accessList, '0.status'), \App\Models\User\AccessKey::STATUS_USING);
            $access = array_get($accessList, '0.access');
            $ret = $this->api_deleteAccessKey([
                'access' => $access, // String 秘钥的AccessKey
                'password' => $pwd
            ] // String 密码
);
            $this->assertCodeEqual($ret, \ErrorCode::UNEXPECTED);
            
            $ret = $this->api_enableAccessKey([
                'access' => $access
            ] // String 秘钥的AccessKey
);
            $this->assertCodeEqual($ret, \ErrorCode::UNEXPECTED);
            
            $ret = $this->api_disableAccessKey([
                'access' => $access
            ] // String 秘钥的AccessKey
);
            $this->assertCodeEqual($ret, \ErrorCode::UNEXPECTED);
            
            $ret = $this->api_applyShowSecretKeyToken([
                'password' => $pwd
            ] // String 秘钥的AccessKey
);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            // $this->output($ret);
            
            $ret = $this->api_showSecretKey([
                'access' => $access, // String 秘钥的AccessKey
                'token' => $ret['data']['token']
            ]);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            // $this->output($ret);
            
            $ret = $this->api_createAccessKey();
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
        } else 
            if ($count == 2) {
                $access01 = array_get($accessList, '0.access');
                $access02 = array_get($accessList, '1.access');
                $ret = $this->api_applyShowSecretKeyToken([
                    'password' => $pwd
                ] // String 秘钥的AccessKey
);
                $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
                $token = $ret['data']['token'];
                // $this->output($ret);
                
                $ret = $this->api_showSecretKey([
                    'access' => $access01, // String 秘钥的AccessKey
                    'token' => $token
                ]);
                $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
                
                $ret = $this->api_showSecretKey([
                    'access' => $access02, // String 秘钥的AccessKey
                    'token' => $token
                ]);
                $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
                $ret = $this->api_createAccessKey();
                $this->assertCodeEqual($ret, \ErrorCode::UNEXPECTED);
                
                $ret = $this->api_disableAccessKey([
                    'access' => $access01
                ] // String 秘钥的AccessKey
);
                $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
                $ret = $this->api_deleteAccessKey([
                    'access' => $access01, //String 秘钥的AccessKey
                'password' => $pwd, //String 密码
            ]);
            $this->assertCodeEqual($ret, \ErrorCode::STATUS_OK);
            
        }else{
            throw new \Exception('Unexpected Count');
        }
        
        // a email developer
        // a email not developer
        // a email email_activation eq EMAIL_ACTIVATION_YES
        // a new email 
        // a wrong email
        // a email in db
        // a new phone
        // a wrong phone
        // a phone in db
        // a new truename
        // a truename in db
        // 
        
        
    }
    
    
}
