<?php 

use App\Services\Open\OpenServices;

class TestRoutes extends TestCase
{
    
    /**
     * 1.1获取验证码
     *
     * 
     */
    public function api_captcha($params = [])
    {
        $data = [

        ];
        $ret = $this->post(route('api_captcha'), $data);
        return $ret;
    }
        
    /**
     * 1.0用户注册
     *
     * @param array $params
     *            <pre>
     *            'email' => '', //String 邮箱
     *            'password' => '', //String 密码
     *            'repassword' => '', //String 确认密码
     *            'code' => '', //Int 验证码
     *            'syntax' => '', //String 错误返回格式，取值（signle|all），signle信息在msg字段中，,all则数据放在data中
     *            'sendemail' => '', //String 注册成功后是否发送邮件,取值（yes|no）,默认no，错误传值自动忽略
     *            </pre>
     */
    public function api_register($params = [])
    {
        $data = [
            'email' => array_get($params,'email',''),// 邮箱 String
            'password' => array_get($params,'password',''),// 密码 String
            'repassword' => array_get($params,'repassword',''),// 确认密码 String
            'code' => array_get($params,'code',''),// 验证码 Int
            'syntax' => array_get($params,'syntax',''),// 错误返回格式，取值（signle|all），signle信息在msg字段中，,all则数据放在data中 String
            'sendemail' => array_get($params,'sendemail',''),// 注册成功后是否发送邮件,取值（yes|no）,默认no，错误传值自动忽略 String
        ];
        $ret = $this->postRetJson(route('api_register'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 2.0发送激活邮件
     *
     * 
     */
    public function api_sendRegisterEmail($params = [])
    {
        $data = [

        ];
        $ret = $this->postRetJson(route('api_sendRegisterEmail'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 2.3邮箱激活检测
     *
     * @param array $params
     *            <pre>
     *            'token' => '', //String token
     *            </pre>
     */
    public function api_validateEmail($params = [])
    {
        $data = [
            'token' => array_get($params,'token',''),// token String
        ];
        $ret = $this->postRetJson(route('api_validateEmail'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 2.1获取邮箱的登录链接
     *
     * @param array $params
     *            <pre>
     *            'email' => '', //String 邮箱
     *            </pre>
     */
    public function api_emailHomeAddr($params = [])
    {
        $data = [
            'email' => array_get($params,'email',''),// 邮箱 String
        ];
        $ret = $this->postRetJson(route('api_emailHomeAddr'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 3.1获取下层地区信息
     *
     * @param array $params
     *            <pre>
     *            'fid' => '', //Integer 上层ID
     *            'nul2err' => '', //Integer 为空是否返回错误（状态码非1）
     *            </pre>
     */
    public function region($params = [])
    {
        $data = [
            'fid' => array_get($params,'fid',''),// 上层ID Integer
            'nul2err' => array_get($params,'nul2err',''),// 为空是否返回错误（状态码非1） Integer
        ];
        $ret = $this->postRetJson(route('region'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 6.0发送忘记密码邮件
     *
     * @param array $params
     *            <pre>
     *            'email' => '', //String 邮箱
     *            </pre>
     */
    public function api_sendForgetEmail($params = [])
    {
        $data = [
            'email' => array_get($params,'email',''),// 邮箱 String
        ];
        $ret = $this->postRetJson(route('api_sendForgetEmail'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 6.1验证忘记密码邮件中的token
     *
     * @param array $params
     *            <pre>
     *            'token' => '', //String token
     *            </pre>
     */
    public function api_validateForgetEmail($params = [])
    {
        $data = [
            'token' => array_get($params,'token',''),// token String
        ];
        $ret = $this->postRetJson(route('api_validateForgetEmail'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 6.2重设密码
     *
     * @param array $params
     *            <pre>
     *            'token' => '', //String token
     *            'password' => '', //String 密码
     *            'repassword' => '', //String 确认密码
     *            </pre>
     */
    public function api_resetForgottenPasswd($params = [])
    {
        $data = [
            'token' => array_get($params,'token',''),// token String
            'password' => array_get($params,'password',''),// 密码 String
            'repassword' => array_get($params,'repassword',''),// 确认密码 String
        ];
        $ret = $this->postRetJson(route('api_resetForgottenPasswd'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 5.0登录
     *
     * @param array $params
     *            <pre>
     *            'account' => '', //String 用户名
     *            'password' => '', //String 密码
     *            'remember' => '', //String 记住我
     *            </pre>
     */
    public function api_login($params = [])
    {
        $data = [
            'account' => array_get($params,'account',''),// 用户名 String
            'password' => array_get($params,'password',''),// 密码 String
            'remember' => array_get($params,'remember',''),// 记住我 String
        ];
        $ret = $this->postRetJson(route('api_login'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 7.0登出
     *
     * 
     */
    public function api_logout($params = [])
    {
        $data = [

        ];
        $ret = $this->postRetJson(route('api_logout'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 8.0修改密码
     *
     * @param array $params
     *            <pre>
     *            'oldpasswd' => '', //String 原密码
     *            'password' => '', //String 新密码
     *            'repassword' => '', //String 确认密码
     *            </pre>
     */
    public function api_password($params = [])
    {
        $data = [
            'oldpasswd' => array_get($params,'oldpasswd',''),// 原密码 String
            'password' => array_get($params,'password',''),// 新密码 String
            'repassword' => array_get($params,'repassword',''),// 确认密码 String
        ];
        $ret = $this->postRetJson(route('api_password'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 3.2发送激活短信
     *
     * @param array $params
     *            <pre>
     *            'phone' => '', //String 手机号码
     *            </pre>
     */
    public function api_sendApplySms($params = [])
    {
        $data = [
            'phone' => array_get($params,'phone',''),// 手机号码 String
        ];
        $ret = $this->postRetJson(route('api_sendApplySms'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 3.0申请成为开发者
     *
     * @param array $params
     *            <pre>
     *            'truename' => '', //String 真实姓名
     *            'phone' => '', //String 手机号码
     *            'code' => '', //String 手机验证码
     *            'regions' => '', //String 地区信息id1,id2,id3
     *            'address' => '', //String 详细地址
     *            'company' => '', //String 公司
     *            'syntax' => '', //String 是否显示全部错误
     *            </pre>
     */
    public function api_applyDeveloper($params = [])
    {
        $data = [
            'truename' => array_get($params,'truename',''),// 真实姓名 String
            'phone' => array_get($params,'phone',''),// 手机号码 String
            'code' => array_get($params,'code',''),// 手机验证码 String
            'regions' => array_get($params,'regions',''),// 地区信息id1,id2,id3 String
            'address' => array_get($params,'address',''),// 详细地址 String
            'company' => array_get($params,'company',''),// 公司 String
            'syntax' => array_get($params,'syntax',''),// 是否显示全部错误 String
        ];
        $ret = $this->postRetJson(route('api_applyDeveloper'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 9.4发秘钥对列表
     *
     * 
     */
    public function api_accessKeyList($params = [])
    {
        $data = [

        ];
        $ret = $this->postRetJson(route('api_accessKeyList'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 9.0申请创建开发秘钥对
     *
     * 
     */
    public function api_createAccessKey($params = [])
    {
        $data = [

        ];
        $ret = $this->postRetJson(route('api_createAccessKey'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 9.1停用秘钥对
     *
     * @param array $params
     *            <pre>
     *            'access' => '', //String 秘钥的AccessKey
     *            </pre>
     */
    public function api_disableAccessKey($params = [])
    {
        $data = [
            'access' => array_get($params,'access',''),// 秘钥的AccessKey String
        ];
        $ret = $this->postRetJson(route('api_disableAccessKey'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 9.2启用秘钥对
     *
     * @param array $params
     *            <pre>
     *            'access' => '', //String 秘钥的AccessKey
     *            </pre>
     */
    public function api_enableAccessKey($params = [])
    {
        $data = [
            'access' => array_get($params,'access',''),// 秘钥的AccessKey String
        ];
        $ret = $this->postRetJson(route('api_enableAccessKey'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 9.3删除钥对
     *
     * @param array $params
     *            <pre>
     *            'access' => '', //String 秘钥的AccessKey
     *            'password' => '', //String 密码
     *            </pre>
     */
    public function api_deleteAccessKey($params = [])
    {
        $data = [
            'access' => array_get($params,'access',''),// 秘钥的AccessKey String
            'password' => array_get($params,'password',''),// 密码 String
        ];
        $ret = $this->postRetJson(route('api_deleteAccessKey'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 9.6删除钥对
     *
     * @param array $params
     *            <pre>
     *            'access' => '', //String 秘钥的AccessKey
     *            'token' => '', //String 密码
     *            </pre>
     */
    public function api_showSecretKey($params = [])
    {
        $data = [
            'access' => array_get($params,'access',''),// 秘钥的AccessKey String
            'token' => array_get($params,'token',''),// 密码 String
        ];
        $ret = $this->postRetJson(route('api_showSecretKey'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 9.5删除钥对
     *
     * @param array $params
     *            <pre>
     *            'password' => '', //String 密码
     *            </pre>
     */
    public function api_applyShowSecretKeyToken($params = [])
    {
        $data = [
            'password' => array_get($params,'password',''),// 密码 String
        ];
        $ret = $this->postRetJson(route('api_applyShowSecretKeyToken'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 99.0开发者申请审核列表
     *
     * @param array $params
     *            <pre>
     *            'pageSize' => '', //String 分页每页数量
     *            'page' => '', //String 请求的页数
     *            </pre>
     */
    public function api_developerApplyReviewList($params = [])
    {
        $data = [
            'pageSize' => array_get($params,'pageSize',''),// 分页每页数量 String
            'page' => array_get($params,'page',''),// 请求的页数 String
        ];
        $ret = $this->postRetJson(route('api_developerApplyReviewList'), $data)->toJson();
        return $ret;
    }
        
    /**
     * 99.1审核开发者申请
     *
     * @param array $params
     *            <pre>
     *            'apply_id' => '', //String 申请ID
     *            'deal' => '', //String 处理结果 取值(agree|refuse)
     *            'reason' => '', //String 失败原因
     *            'note' => '', //String 备注
     *            </pre>
     */
    public function api_developerApplyReview($params = [])
    {
        $data = [
            'apply_id' => array_get($params,'apply_id',''),// 申请ID String
            'deal' => array_get($params,'deal',''),// 处理结果 取值(agree|refuse) String
            'reason' => array_get($params,'reason',''),// 失败原因 String
            'note' => array_get($params,'note',''),// 备注 String
        ];
        $ret = $this->postRetJson(route('api_developerApplyReview'), $data)->toJson();
        return $ret;
    }
        
    /**
     * superuser
     *
     * 
     */
    public function api_mqtt_superuser($params = [])
    {
        $data = [

        ];
        $ret = $this->postRetJson('api/mqtt/superuser', $data)->toJson();
        return $ret;
    }
        
    /**
     * auth
     *
     * 
     */
    public function api_mqtt_auth($params = [])
    {
        $data = [

        ];
        $ret = $this->postRetJson('api/mqtt/auth', $data)->toJson();
        return $ret;
    }
        
    /**
     * EMQ 订阅的权限控制接口，emq_auth_http 插件所需
     *
     * @param array $params
     *            <pre>
     *            'access' => '', //String 订阅 QOS
     *            'username' => '', //String 登录用户名
     *            'clientid' => '', //String 连接的 clientid
     *            'ipaddr' => '', //String IP 地址
     *            'topic' => '', //String 订阅的 Topic
     *            </pre>
     */
    public function api_mqtt_acl($params = [])
    {
        $data = [
            'access' => array_get($params,'access',''),// 订阅 QOS String
            'username' => array_get($params,'username',''),// 登录用户名 String
            'clientid' => array_get($params,'clientid',''),// 连接的 clientid String
            'ipaddr' => array_get($params,'ipaddr',''),// IP 地址 String
            'topic' => array_get($params,'topic',''),// 订阅的 Topic String
        ];
        $ret = $this->postRetJson('api/mqtt/acl', $data)->toJson();
        return $ret;
    }
        
    /**
     * 101.0 检测设备名称是否存在，并检测是否已绑定
     *
     * @param array $params
     *            <pre>
     *            'developer_access' => '', //String 开发者 access
     *            'developer_pass' => '', //String 开发者 secret TOTP 结果
     *            'device_name' => '', //String 设备名称
     *            'unique_token' => '', //String 可选 安装 APP 的设备的唯一标识
     *            </pre>
     */
    public function api_mqtt_deviceNameCheck($params = [])
    {
        $data = [
            'developer_access' => array_get($params,'developer_access',''),// 开发者 access String
            'developer_pass' => array_get($params,'developer_pass',''),// 开发者 secret TOTP 结果 String
            'device_name' => array_get($params,'device_name',''),// 设备名称 String
            'unique_token' => array_get($params,'unique_token',''),// 可选 安装 APP 的设备的唯一标识 String
        ];
        $ret = $this->postRetJson('api/mqtt/deviceNameCheck', $data)->toJson();
        return $ret;
    }
        
    /**
     * 100.0 APP 绑定 设备
     *
     * @param array $params
     *            <pre>
     *            'developer_access' => '', //String 开发者 access
     *            'developer_pass' => '', //String 开发者 secret TOTP 结果
     *            'unique_token' => '', //String 可选 安装 APP 的设备的唯一标识
     *            'device_name' => '', //String 设备名称
     *            'device_pass' => '', //String 设备 secret TOTP 结果
     *            </pre>
     */
    public function api_mqtt_bindDevice($params = [])
    {
        $data = [
            'developer_access' => array_get($params,'developer_access',''),// 开发者 access String
            'developer_pass' => array_get($params,'developer_pass',''),// 开发者 secret TOTP 结果 String
            'unique_token' => array_get($params,'unique_token',''),// 可选 安装 APP 的设备的唯一标识 String
            'device_name' => array_get($params,'device_name',''),// 设备名称 String
            'device_pass' => array_get($params,'device_pass',''),// 设备 secret TOTP 结果 String
        ];
        $ret = $this->postRetJson('api/mqtt/bindDevice', $data)->toJson();
        return $ret;
    }
        
    /**
     * 103.0 列举所有已绑定的设备
     *
     * @param array $params
     *            <pre>
     *            'developer_access' => '', //String 开发者 access
     *            'developer_pass' => '', //String 开发者 secret TOTP 结果
     *            'unique_token' => '', //String 可选 安装 APP 的设备的唯一标识
     *            </pre>
     */
    public function api_mqtt_listBindedDevices($params = [])
    {
        $data = [
            'developer_access' => array_get($params,'developer_access',''),// 开发者 access String
            'developer_pass' => array_get($params,'developer_pass',''),// 开发者 secret TOTP 结果 String
            'unique_token' => array_get($params,'unique_token',''),// 可选 安装 APP 的设备的唯一标识 String
        ];
        $ret = $this->postRetJson('api/mqtt/listBindedDevices', $data)->toJson();
        return $ret;
    }
        
    /**
     * 104.0 解除已绑定设备
     *
     * @param array $params
     *            <pre>
     *            'developer_access' => '', //String 开发者 access
     *            'developer_pass' => '', //String 开发者 secret TOTP 结果
     *            'device_access' => '', //String 设备 Access Key
     *            'unique_token' => '', //String 可选 安装 APP 的设备的唯一标识
     *            </pre>
     */
    public function api_mqtt_unbindDevice($params = [])
    {
        $data = [
            'developer_access' => array_get($params,'developer_access',''),// 开发者 access String
            'developer_pass' => array_get($params,'developer_pass',''),// 开发者 secret TOTP 结果 String
            'device_access' => array_get($params,'device_access',''),// 设备 Access Key String
            'unique_token' => array_get($params,'unique_token',''),// 可选 安装 APP 的设备的唯一标识 String
        ];
        $ret = $this->postRetJson('api/mqtt/unbindDevice', $data)->toJson();
        return $ret;
    }
        
    /**
     * errorReport
     *
     * @param array $params
     *            <pre>
     *            'developer_access' => '', //String 开发者 access
     *            'developer_pass' => '', //String 开发者 secret TOTP 结果
     *            'error' => '', //String 错误信息
     *            </pre>
     */
    public function api_mqtt_errorReport($params = [])
    {
        $data = [
            'developer_access' => array_get($params,'developer_access',''),// 开发者 access String
            'developer_pass' => array_get($params,'developer_pass',''),// 开发者 secret TOTP 结果 String
            'error' => array_get($params,'error',''),// 错误信息 String
        ];
        $ret = $this->postRetJson('api/mqtt/errorReport', $data)->toJson();
        return $ret;
    }

}
