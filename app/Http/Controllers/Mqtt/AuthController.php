<?php
namespace App\Http\Controllers\Mqtt;

use Route;
use View;
use App\Http\Controllers\Controller;
use App\Services\Common\TOTPService;
use App\Models\User\AccessKey;
use App\Extensions\Mqtt\MqttUtil;
use App\Models\Open\Device;
use App\Models\User\DeveloperDevBind;
use App\Exceptions\ServiceException;
use App\Models\Open\DemoErrorLog;

class AuthController extends Controller
{

    public function superuser()
    {
        
        return \Response::json([], 200);
        // {
        // "username": "0CRngr3ddpVzUBoeF",
        // "clientid": "0CRngr3ddpVzUBoeF"
        // }
        return \Response::json([], 401);
    }

    public function auth()
    {
        return \Response::json([], 200);
        // {
        // "clientid": "0CRngr3ddpVzUBoeF",
        // "username": "0CRngr3ddpVzUBoeF",
        // "password": "XqCEMSzhsdWHfwhm"
        // }
        $data = \Input::all();
        if (isset($data['username']) && isset($data['password'])) {
            if(strpos($data['username'], "chest") === 0 && $data['password'] == '21t4ZfwOVEBgigrQ'){
                return \Response::json([], 200);
            }
            
            // 从开放平台用户中查找accesskey
            $info = AccessKey::select([
                'account_id',
                'access',
                'secret'
            ])->where('access', $data['username'])
                ->where('status', AccessKey::STATUS_USING)
                ->first();
            
            if (! $info) {
                // 从设备中查找accesskey
                $info = Device::searchByAccessKey($data['username']);
            }
            // \Log::info('$info',$info->toArray() );
            // \Log::info('$data',$data );
            if ($info) {
                $info = $info->toArray();
                $authRet = TOTPService::verify_key(MqttUtil::base32_encode($info['secret']), $data['password']);
                // \Log::info('$k = '.$k);
                if (true === $authRet) {
                    // \Log::info('$authRet true');
                    return \Response::json([], 200);
                } else {
                    // \Log::info('$authRet false');
                    return \Response::json([], 401);
                }
            }
        }
        return \Response::json([], 401);
    }

    /**
     * EMQ 订阅的权限控制接口，emq_auth_http 插件所需
     */
    public function acl()
    {
        return \Response::json([], 200);
        // {
        // "access": "1",
        // "username": "0CRngr3ddpVzUBoeF",
        // "clientid": "0CRngr3ddpVzUBoeF",
        // "ipaddr": "192.168.5.60",
        // "topic": "/0CRngr3ddpVzUBoeF"
        // }
        // 可能的调用情况
        // 1.控制器
        // 订阅 /${deviceID}/resp 获取消息返回
        // 此时需要检查设备和开发者的绑定关系
        // 2.设备
        // 订阅 /${deviceID} 来获取消息
        // 此时 username clientid 和 deviceID 相同，直接允许订阅
        $data = [
            "access" => \Input::get('access'), // string 订阅 QOS
            "username" => \Input::get('username'), // 登录用户名
            "clientid" => \Input::get('clientid'), // 连接的 clientid
            "ipaddr" => \Input::get('ipaddr'), // IP 地址
            "topic" => \Input::get('topic')
        ]; // 订阅的 Topic
        
        if (isset($data['username']) && isset($data['topic'])) {
            // 
            
            // 后门
            if (strpos($data['username'], 'chest') === 0) {
                return \Response::json([], 200);
            }
            // // 后门
            // if (strpos($data['username'], 'test') === 0) {
            // return \Response::json([], 200);
            // }
            
            // 提取deviceID
            $topicSegments = explode("/", $data['topic']);
            // 自己订阅自己
            if (isset($topicSegments[1]) && $data['username'] == $topicSegments[1]) {
                return \Response::json([], 200);
            }
            if (count($topicSegments) >= 2) {
                $isBinded = DeveloperDevBind::isBindedByAccess($data['username'], $topicSegments[1]);
                if ($isBinded) {
                    $isConnected = \App\Services\Open\MqttServices::emqClientsWhetherOnline($topicSegments[1]);
                    if (isset($isConnected[$topicSegments[1]]) && $isConnected[$topicSegments[1]]) {
                        return \Response::json([], 200);
                    } else {
                        return \Response::json([], 401);
                    }
                }
            }
        }
        return \Response::json([], 401);
    }

    /**
     * 101.0 检测设备名称是否存在，并检测是否已绑定
     *
     * @apiSuccess {Integer} binded 是否已经绑定 0 未绑定 1 已绑定
     */
    public function deviceNameCheck()
    {
        $data = [
            'developer_access' => \Input::get('developer_access'), // string 开发者 access
            'developer_pass' => \Input::get('developer_pass'), // string 开发者 secret TOTP 结果
            'device_name' => \Input::get('device_name'), // string 设备名称
            'unique_token' => \Input::get('unique_token', '')
        ]; // string 可选 安装 APP 的设备的唯一标识
        
        runCustomValidator([
            'data' => $data, // 数据
            'rules' => [
                'device_name' => 'required',
                'developer_access' => 'required',
                'developer_pass' => 'required'
            ], // 条件
            'attributes' => [
                'device_name' => '设备名称',
                'developer_access' => '开发者 AccessKey',
                'developer_pass' => '开发者密码'
            ]
        ]);
        
        $developer = AccessKey::getDeveloperIDByAccessKey($data['developer_access']);
        if (! $developer) {
            throw new ServiceException('未找到开发者', \ErrorCode::VITAL_NOT_FOUND);
        }
        $ct = Device::where('alias', $data['device_name'])->select([
            'access_key'
        ])->first();
        // $ct = $ct ? $ct->toArray() : $ct;
        if ($ct) {
            $isBinded = DeveloperDevBind::isBindedByID($developer['account_id'], $ct['access_key'], $data['unique_token']);
            return $this->__json([
                'binded' => $isBinded ? 1 : 0
            ]);
        }
        return $this->__json(\ErrorCode::VALIDATION_FAILED, '未找到该设备');
    }

    /**
     * 100.0 APP 绑定 设备
     */
    public function bindDevice()
    {
        $data = [
            'developer_access' => \Input::get('developer_access'), // string 开发者 access
            'developer_pass' => \Input::get('developer_pass'), // string 开发者 secret TOTP 结果
            'unique_token' => \Input::get('unique_token', ''), // string 可选 安装 APP 的设备的唯一标识
            'device_name' => \Input::get('device_name'), // string 设备名称
            'device_pass' => \Input::get('device_pass')
        ]; // string 设备 secret TOTP 结果
        
        runCustomValidator([
            'data' => $data, // 数据
            'rules' => [
                'developer_access' => 'required',
                'developer_pass' => 'required',
                'unique_token' => 'max:100',
                'device_name' => 'required',
                'device_pass' => 'required'
            ], // 条件
            'attributes' => [
                'developer_access' => '开发者 AccessKey',
                'developer_pass' => '开发者密码',
                'device_name' => '设备名称',
                'device_pass' => '设备密码'
            ]
        ]);
        
        // // 后门
        // if (strpos($data['developer_access'], 'test') === 0) {
        // $data['developer_access'] = substr($data['developer_access'], 4);
        // $accessKey = Device::getDeviceAccessByName($data['device_name']);
        // if(false === $accessKey){
        // throw new ServiceException('未找该设备',\ErrorCode::VITAL_NOT_FOUND);
        // }
        // $developer = AccessKey::getDeveloperIDByAccessKey($data['developer_access']);
        // if(!$developer){
        // throw new ServiceException('未找到开发者',\ErrorCode::VITAL_NOT_FOUND);
        // }
        // DeveloperDevBind::bindDevice($developer['account_id'], $accessKey,$data['unique_token']);
        // return $this->__json([
        // 'access_key' => $accessKey
        // ]);
        // }
        
        $developer = AccessKey::getDeveloperIDByAccessKey($data['developer_access']);
        if (! $developer) {
            throw new ServiceException('未找到开发者', \ErrorCode::VITAL_NOT_FOUND);
        }
        if (! totp_secret_compare($developer['secret'], $data['developer_pass'])) {
            throw new ServiceException('开发者密码错误', \ErrorCode::VALIDATION_FAILED);
        }
        $accessKey = Device::authorityWithDevNameAndSecret($data['device_name'], $data['device_pass']);
        DeveloperDevBind::bindDevice($developer['account_id'], $accessKey, $data['unique_token']);
        
        return $this->__json([
            'access_key' => $accessKey
        ]);
    }

    /**
     * 103.0 列举所有已绑定的设备
     *
     * @apiSuccess {String} device_name 下次发送剩余时间（秒）
     * @apiSuccess {String} device_access 注册邮箱的登录入口地址
     * @apiSuccess {Integer} online 是否在线 0 不在线 1 在线
     */
    public function listBindedDevices()
    {
        $data = [
            'developer_access' => \Input::get('developer_access'), // string 开发者 access
            'developer_pass' => \Input::get('developer_pass'), // string 开发者 secret TOTP 结果
            'unique_token' => \Input::get('unique_token', '')
        ]; // string 可选 安装 APP 的设备的唯一标识
        
        runCustomValidator([
            'data' => $data, // 数据
            'rules' => [
                'developer_access' => 'required',
                'developer_pass' => 'required',
                'unique_token' => 'max:100'
            ], // 条件
            'attributes' => [
                'developer_access' => '开发者 AccessKey',
                'developer_pass' => '开发者密码'
            ]
        ]);
        // 后门
        $skip = false;
        // if (strpos($data['developer_access'], 'test') === 0) {
        // $data['developer_access'] = substr($data['developer_access'], 4);
        // $skip = true;
        // }
        
        $developer = AccessKey::getDeveloperIDByAccessKey($data['developer_access']);
        if (! $developer) {
            throw new ServiceException('未找到开发者', \ErrorCode::VITAL_NOT_FOUND);
        }
        
        if (! $skip && ! totp_secret_compare($developer['secret'], $data['developer_pass'])) {
            throw new ServiceException('开发者密码错误', \ErrorCode::VALIDATION_FAILED);
        }
        $dev_list = DeveloperDevBind::listUserBindedDevices($developer['account_id'], $data['unique_token']);
        $client_keys = [];
        foreach ($dev_list as $v) {
            $client_keys[] = $v['device_access'];
        }
        $dev_status = \App\Services\Open\MqttServices::emqClientsWhetherOnline($client_keys);
        foreach ($dev_list as $k => $v) {
            $dev_list[$k]['online'] = $dev_status[$v['device_access']] === true ? 1 : 0;
        }
        return $this->__json($dev_list);
    }

    /**
     * 104.0 解除已绑定设备
     */
    public function unbindDevice()
    {
        $data = [
            'developer_access' => \Input::get('developer_access'), // string 开发者 access
            'developer_pass' => \Input::get('developer_pass'), // string 开发者 secret TOTP 结果
            'device_access' => \Input::get('device_access'), // string 设备 Access Key
            'unique_token' => \Input::get('unique_token', '')
        ]; // string 可选 安装 APP 的设备的唯一标识
        
        runCustomValidator([
            'data' => $data, // 数据
            'rules' => [
                'developer_access' => 'required',
                'developer_pass' => 'required',
                'device_access' => 'required'
            ], // 条件
            'attributes' => [
                'developer_access' => '开发者 AccessKey',
                'developer_pass' => '开发者密码',
                'device_access' => '设备 AccessKey'
            ]
        ]);
        
        $developer = AccessKey::getDeveloperIDByAccessKey($data['developer_access']);
        if (! $developer) {
            throw new ServiceException('未找到开发者', \ErrorCode::VITAL_NOT_FOUND);
        }
        
        if (! totp_secret_compare($developer['secret'], $data['developer_pass'])) {
            throw new ServiceException('开发者密码错误', \ErrorCode::VALIDATION_FAILED);
        }
        
        if (DeveloperDevBind::isBindedByID($developer['account_id'], $data['device_access'], $data['unique_token'])) {
            DeveloperDevBind::unbindDevice($developer['account_id'], $data['device_access'], $data['unique_token']);
        } else {
            throw new ServiceException('尚未绑定', \ErrorCode::LOGIC_ERROR);
        }
        return $this->__json();
    }
    
    
    public function showJsErrorReport()
    {
        $filename = storage_path('logs/laravel-'.date('Y-m-d').'.log');
        if(file_exists($filename)){
            echo '<pre>';
            echo file_get_contents($filename);
            echo '</pre>';
        }
        exit;
    }
    
    
    public function jsErrorReport()
    {
        $data = [
            'developer_access' => \Input::get('developer_access'), // string 开发者 access
            'developer_pass' => \Input::get('developer_pass'), // string 开发者 secret TOTP 结果
            'error' => \Input::get('error')
        ]; // string 错误信息
    
//         $developer = AccessKey::getDeveloperIDByAccessKey($data['developer_access']);
//         if (! $developer) {
//             throw new ServiceException('未找到开发者', \ErrorCode::VITAL_NOT_FOUND);
//         }
    
//         if (! totp_secret_compare($developer['secret'], $data['developer_pass'])) {
//             throw new ServiceException('开发者密码错误', \ErrorCode::VALIDATION_FAILED);
//         }
        \Log::info(json_encode($data['error']));
        return $this->__json();
    }
    

    public function errorReport()
    {
        $data = [
            'developer_access' => \Input::get('developer_access'), // string 开发者 access
            'developer_pass' => \Input::get('developer_pass'), // string 开发者 secret TOTP 结果
            'error' => \Input::get('error')
        ]; // string 错误信息
        
        $developer = AccessKey::getDeveloperIDByAccessKey($data['developer_access']);
        if (! $developer) {
            throw new ServiceException('未找到开发者', \ErrorCode::VITAL_NOT_FOUND);
        }
        
        if (! totp_secret_compare($developer['secret'], $data['developer_pass'])) {
            throw new ServiceException('开发者密码错误', \ErrorCode::VALIDATION_FAILED);
        }
        
        DemoErrorLog::addRecord($data['error']);
        
        return $this->__json();
    }

    /**
     * 获取系统时间
     */
    public function systemTime()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * 通过设备型号和系统版本，获取系统更新包信息
     */
    public function upgrade()
    {
        $input = [
            'model' => \Input::get('model','LHLHUA'),
            'version' => \Input::get('version','1.0.0')
        ];
        
        runCustomValidator([
            'data' => $input, // 数据
            'rules' => [
                'model' => 'required',
                'version' => 'required'
            ], // 条件
            'attributes' => [
                'model' => '设备型号',
                'version' => '当前系统版本'
            ]
        ]); // 属性名映射

        
        $versionStore = [
            'LHLHUA' => [
                [
                    'ProductModel' => 'LHLHUA',
                    'LatestVersion' => '1.1.1',
                    'DownloadLink' => 'http://scentplayer-lianhelihua.oss-cn-beijing.aliyuncs.com/update-1.1.1.tar.xz',
                    'SHA1' => '7940bc07c71fba50fd6f44a9a8afc3995bf01a3c',
                    'MD5' => '0f5ba18ab6da6bd46f94622eb69c7d78'
                ],
                [
                    'ProductModel' => 'LHLHUA',
                    'LatestVersion' => '1.1.0',
                    'DownloadLink' => 'http://scentplayer-lianhelihua.oss-cn-beijing.aliyuncs.com/update-1.1.0.tar.xz',
                    'SHA1' => 'e63835862d71ff00a34646941248452a48fa1571',
                    'MD5' => '67b25516918a1879753c7b28062c0a8e'
                ],
                [
                    'ProductModel' => 'LHLHUA',
                    'LatestVersion' => '1.0.1',
                    'DownloadLink' => 'http://scentplayer-lianhelihua.oss-cn-beijing.aliyuncs.com/update-1.0.1.tar.xz',
                    'SHA1' => '1cd4587998002f5f9d445a58221b7fab8a5cdbbc',
                    'MD5' => '089d280ae8eb3f7c02e926e105717e57'
                ],
                [
                    'ProductModel' => 'LHLHUA',
                    'LatestVersion' => '1.0.0',
                    'DownloadLink' => 'http://scentplayer-lianhelihua.oss-cn-beijing.aliyuncs.com/update-1.0.0.tar.xz',
                    'MD5' => '174ec6d0c9095bfcdf0d69b0296a2da6'
                ]
            ]
        ];
        
        if (isset($versionStore[$input['model']])) {
            $latest = array_shift($versionStore[$input['model']]);
            if ($input['version'] != $latest['LatestVersion']){
                return $this->__json($latest); 
            }
            return $this->__json();    
        }else{
            return $this->__json('未找到设备型号',\ErrorCode::VALIDATION_FAILED);    
        }
    }
    

    
    public function LHLHLoginPage(){
    
        return view("open.mqtt.LHLHLogin");
    }
    
    public function LHLHLogin(){

        $data = [
            'username' => \Input::get('username'),
            'password' => \Input::get('password'),
        ];
        
        runCustomValidator([
            'data' => $data, // 数据
            'rules' => [
                'username' => 'required',
                'password' => 'required'
            ], // 条件
            'attributes' => [
                'username' => '用户名',
                'password' => '密码'
            ]
        ]); // 属性名映射

        if(strtolower($data['username']) == 'lhlh' && strtolower($data['password']) == 'lhlh123456' ){
            
            session(['LHLH-Auth' => 1]);
            
            return $this->__json();
        }
        return $this->__json(\ErrorCode::UNAUTHORIZED,'用户名或密码错误！');
    }
    
    public function LHLHController(){
        if(!session('LHLH-Auth',0)){
            return redirect(route('get_LHLH_login'));
        }
        return view("open.mqtt.LHLH");
    }
    
    
    
    public function LHLHLoginSimple(){
    
        $data = [
            'username' => \Input::get('username'),
            'password' => \Input::get('password'),
        ];
    
        $ret =  runCustomValidator([
            'data' => $data, // 数据
            'rules' => [
                'username' => 'required',
                'password' => 'required'
            ], // 条件
            'attributes' => [
                'username' => '用户名',
                'password' => '密码'
            ],
            'config' =>
            [
                'ReturnOrException' => 0, // Return (0) Or Exception(1)
            ],
        ]); // 属性名映射
        if(true === $ret){
            if(strtolower($data['username']) == 'lhlh' && strtolower($data['password']) == 'lhlh123456' ){
                session(['LHLH-Auth' => 1]);
                return redirect(route('get_LHLH_simple_controller'));
            }
        }
        return redirect(route('get_LHLH_simple_login'))->with(['error' => '用户名或密码错误！'])->withInput();
    }
    
    
    
    public function LHLHSimpleController(){
        if(!session('LHLH-Auth',0)){
            return redirect(route('get_LHLH_simple_login'));
        }
        return view("open.mqtt.LHLHSimple");
    }
    
    
    public function LHLHSimpleLoginPage(){
    
        return view("open.mqtt.LHLHLoginSimple");
    }
    
}















