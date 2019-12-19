

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/captcha 1.1获取验证码
     * @apiName api_captcha
     * @apiGroup Open_Web
     *
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/captcha
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/register 1.0用户注册
     * @apiName api_register
     * @apiGroup Open_Web
     *
     * @apiParam {String} email 邮箱
     * @apiParam {String} password 密码
     * @apiParam {String} repassword 确认密码
     * @apiParam {Int} code 验证码
     * @apiParam {String} [syntax=all] 错误返回格式，取值（signle|all），signle信息在msg字段中，,all则数据放在data中
     * @apiParam {String} [sendemail=yes] 注册成功后是否发送邮件,取值（yes|no）,默认no，错误传值自动忽略
     *
     * @apiSuccess {Integer} retriesLeft 剩余可发送次数
     * @apiSuccess {Integer} interval 下次发送剩余时间（秒）
     * @apiSuccess {String} emailHome 注册邮箱的登录入口地址
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/register
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/sendRegisterEmail 2.0发送激活邮件
     * @apiName api_sendRegisterEmail
     * @apiGroup Open_Web
     *
     *
     * @apiSuccess {Integer} retriesLeft 剩余可发送次数
     * @apiSuccess {Integer} interval 下次发送剩余时间（秒）
     *
     *
     * @apiError {Integer} seconds 禁止发送剩余时间（秒）
     * @apiError {Integer} restTime 发送间隔剩余时间（秒）
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/sendRegisterEmail
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/validateEmail 2.3邮箱激活检测
     * @apiName api_validateEmail
     * @apiGroup Open_Web
     *
     * @apiParam {String} token token
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/validateEmail
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/emailHomeAddr 2.1获取邮箱的登录链接
     * @apiName api_emailHomeAddr
     * @apiGroup Open_Web
     *
     * @apiParam {String} email 邮箱
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/emailHomeAddr
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/region 3.1获取下层地区信息
     * @apiName api_region
     * @apiGroup Open_Web
     *
     * @apiParam {Integer} [fid=0] 上层ID
     * @apiParam {Integer} [nul2err=0] 为空是否返回错误（状态码非1）
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/region
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/sendForgetEmail 6.0发送忘记密码邮件
     * @apiName api_sendForgetEmail
     * @apiGroup Open_Web
     *
     * @apiParam {String} email 邮箱
     *
     * @apiSuccess {Integer} retriesLeft 剩余可发送次数
     * @apiSuccess {Integer} interval 下次发送剩余时间（秒）
     *
     *
     * @apiError {Integer} seconds 禁止发送剩余时间（秒）
     * @apiError {Integer} restTime 发送间隔剩余时间（秒）
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/sendForgetEmail
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/validateForgetEmail 6.1验证忘记密码邮件中的token
     * @apiName api_validateForgetEmail
     * @apiGroup Open_Web
     *
     * @apiParam {String} token token
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/validateForgetEmail
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/resetForgottenPasswd 6.2重设密码
     * @apiName api_resetForgottenPasswd
     * @apiGroup Open_Web
     *
     * @apiParam {String} token token
     * @apiParam {String} password 密码
     * @apiParam {String} repassword 确认密码
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/resetForgottenPasswd
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/login 5.0登录
     * @apiName api_login
     * @apiGroup Open_Web
     *
     * @apiParam {String} account 用户名
     * @apiParam {String} password 密码
     * @apiParam {String} [remember=0] 记住我
     *
     *
     * @apiSuccessExample Success-Response: HTTP/1.1 200 OK
     *{"code":1,"msg":"OK"}
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/login
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/logout 7.0登出
     * @apiName api_logout
     * @apiGroup Open_Web
     *
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/logout
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/developer/resetPassword 8.0修改密码
     * @apiName api_developer_resetPassword
     * @apiGroup Open_Web
     *
     * @apiParam {String} oldpasswd 原密码
     * @apiParam {String} password 新密码
     * @apiParam {String} repassword 确认密码
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/developer/resetPassword
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/developer/sendApplySms 3.2发送激活短信
     * @apiName api_developer_sendApplySms
     * @apiGroup Open_Web
     *
     * @apiParam {String} phone 手机号码
     *
     * @apiSuccess {Integer} retriesLeft 剩余可发送次数
     * @apiSuccess {Integer} interval 下次发送剩余时间（秒）
     *
     *
     * @apiError {Integer} seconds 禁止发送剩余时间（秒）
     * @apiError {Integer} restTime 发送间隔剩余时间（秒）
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/developer/sendApplySms
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/developer/applyDeveloper 3.0申请成为开发者
     * @apiName api_developer_applyDeveloper
     * @apiGroup Open_Web
     *
     * @apiParam {String} truename 真实姓名
     * @apiParam {String} phone 手机号码
     * @apiParam {String} code 手机验证码
     * @apiParam {String} regions 地区信息id1,id2,id3
     * @apiParam {String} address 详细地址
     * @apiParam {String} company 公司
     * @apiParam {String} [syntax=all] 是否显示全部错误
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/developer/applyDeveloper
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/developer/accessKeyList 9.4发秘钥对列表
     * @apiName api_developer_accessKeyList
     * @apiGroup Open_Web
     *
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/developer/accessKeyList
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/developer/createAccessKey 9.0申请创建开发秘钥对
     * @apiName api_developer_createAccessKey
     * @apiGroup Open_Web
     *
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/developer/createAccessKey
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/developer/disableAccessKey 9.1停用秘钥对
     * @apiName api_developer_disableAccessKey
     * @apiGroup Open_Web
     *
     * @apiParam {String} access 秘钥的AccessKey
     *
     *
     * @apiSuccessExample Success-Response: HTTP/1.1 200 OK
     *{"code":1,"msg":"OK"}
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/developer/disableAccessKey
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/developer/enableAccessKey 9.2启用秘钥对
     * @apiName api_developer_enableAccessKey
     * @apiGroup Open_Web
     *
     * @apiParam {String} access 秘钥的AccessKey
     *
     *
     * @apiSuccessExample Success-Response: HTTP/1.1 200 OK
     *{"code":1,"msg":"OK"}
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/developer/enableAccessKey
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/developer/deleteAccessKey 9.3删除钥对
     * @apiName api_developer_deleteAccessKey
     * @apiGroup Open_Web
     *
     * @apiParam {String} access 秘钥的AccessKey
     * @apiParam {String} password 密码
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/developer/deleteAccessKey
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/developer/showSecretKey 9.6删除钥对
     * @apiName api_developer_showSecretKey
     * @apiGroup Open_Web
     *
     * @apiParam {String} access 秘钥的AccessKey
     * @apiParam {String} token 密码
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/developer/showSecretKey
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/developer/applyShowSecretKeyToken 9.5删除钥对
     * @apiName api_developer_applyShowSecretKeyToken
     * @apiGroup Open_Web
     *
     * @apiParam {String} password 密码
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/developer/applyShowSecretKeyToken
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/back/developerApplyReviewList 99.0开发者申请审核列表
     * @apiName api_back_developerApplyReviewList
     * @apiGroup Open_Web
     *
     * @apiParam {String} [pageSize=10] 分页每页数量
     * @apiParam {String} [page=1] 请求的页数
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/back/developerApplyReviewList
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/back/developerApplyReview 99.1审核开发者申请
     * @apiName api_back_developerApplyReview
     * @apiGroup Open_Web
     *
     * @apiParam {String} apply_id 申请ID
     * @apiParam {String} [deal=refuse] 处理结果 取值(agree|refuse)
     * @apiParam {String} [reason=] 失败原因
     * @apiParam {String} [note=] 备注
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/back/developerApplyReview
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/mqtt/superuser superuser
     * @apiName api_mqtt_superuser
     * @apiGroup Open_Web
     *
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/mqtt/superuser
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/mqtt/auth auth
     * @apiName api_mqtt_auth
     * @apiGroup Open_Web
     *
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/mqtt/auth
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/mqtt/acl EMQ 订阅的权限控制接口，emq_auth_http 插件所需
     * @apiName api_mqtt_acl
     * @apiGroup Open_Web
     *
     * @apiParam {String} access 订阅 QOS
     * @apiParam {String} username 登录用户名
     * @apiParam {String} clientid 连接的 clientid
     * @apiParam {String} ipaddr IP 地址
     * @apiParam {String} topic 订阅的 Topic
     *
     *
     *
     *
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/mqtt/acl
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/mqtt/deviceNameCheck 101.0 检测设备名称是否存在，并检测是否已绑定
     * @apiName api_mqtt_deviceNameCheck
     * @apiGroup Open_Web
     *
     * @apiParam {String} developer_access 开发者 access
     * @apiParam {String} developer_pass 开发者 secret TOTP 结果
     * @apiParam {String} device_name 设备名称
     * @apiParam {String} [unique_token=] 可选 安装 APP 的设备的唯一标识
     *
     * @apiSuccess {Integer} binded 已绑定 1 未绑定 0  是否已经绑定
     *
     * @apiSuccessExample Success-Response: HTTP/1.1 200 OK
     *{"code":1,"msg":"OK","data":{"binded":1}}
     * @apiSuccessExample Success-Response: HTTP/1.1 200 OK
     *{"code":1,"msg":"OK"}
     *
     *
     * @apiErrorExample Error-Responsee: 
     *{"code":9003,"msg":"\u5f00\u53d1\u8005 AccessKey \u4e0d\u80fd\u4e3a\u7a7a\u3002","data":[]}
     * @apiErrorExample Error-Responsee: 
     *{"code":9003,"msg":"\u672a\u627e\u5230\u8be5\u8bbe\u5907"}
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/mqtt/deviceNameCheck
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/mqtt/bindDevice 100.0 APP 绑定 设备
     * @apiName api_mqtt_bindDevice
     * @apiGroup Open_Web
     *
     * @apiParam {String} developer_access 开发者 access
     * @apiParam {String} developer_pass 开发者 secret TOTP 结果
     * @apiParam {String} [unique_token=] 可选 安装 APP 的设备的唯一标识
     * @apiParam {String} device_name 设备名称
     * @apiParam {String} device_pass 设备 secret TOTP 结果
     *
     *
     * @apiSuccessExample Success-Response: HTTP/1.1 200 OK
     *{"code":1,"msg":"OK","data":{"access_key":"9c32626fc323"}}
     * @apiSuccessExample Success-Response: HTTP/1.1 200 OK
     *{"code":1,"msg":"OK","data":{"access_key":"9c32626fc323"}}
     * @apiSuccessExample Success-Response: HTTP/1.1 200 OK
     *{"code":1,"msg":"OK","data":{"access_key":"0A62012DFCAB"}}
     *
     *
     * @apiErrorExample Error-Responsee: 
     *{"code":9003,"msg":"\u8bbe\u5907\u5bc6\u7801\u9519\u8bef","data":[]}
     * @apiErrorExample Error-Responsee: 
     *{"code":9003,"msg":"\u5f00\u53d1\u8005 AccessKey \u4e0d\u80fd\u4e3a\u7a7a\u3002"}
     * @apiErrorExample Error-Responsee: 
     *{"code":9009,"msg":"\u672a\u627e\u5230\u8be5\u8bbe\u5907","data":[]}
     * @apiErrorExample Error-Responsee: 
     *{"code":9009,"msg":"\u672a\u627e\u5230\u5f00\u53d1\u8005"}
     * @apiErrorExample Error-Responsee: 
     *{"code":9003,"msg":"\u5f00\u53d1\u8005\u5bc6\u7801\u9519\u8bef"}
     * @apiErrorExample Error-Responsee: 
     *{"code":9009,"msg":"\u672a\u627e\u5230\u5f00\u53d1\u8005","data":[]}
     * @apiErrorExample Error-Responsee: 
     *{"code":9003,"msg":"\u5f00\u53d1\u8005 AccessKey \u4e0d\u80fd\u4e3a\u7a7a\u3002"}
     * @apiErrorExample Error-Responsee: 
     *{"code":9003,"msg":"\u8bbe\u5907\u5bc6\u7801\u9519\u8bef"}
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/mqtt/bindDevice
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/mqtt/listBindedDevices 103.0 列举所有已绑定的设备
     * @apiName api_mqtt_listBindedDevices
     * @apiGroup Open_Web
     *
     * @apiParam {String} developer_access 开发者 access
     * @apiParam {String} developer_pass 开发者 secret TOTP 结果
     * @apiParam {String} [unique_token=] 可选 安装 APP 的设备的唯一标识
     *
     * @apiSuccess {Integer} online 在线 1 不在线 0 是否在线
     * @apiSuccess {String} device_access 注册邮箱的登录入口地址
     * @apiSuccess {String} device_name 下次发送剩余时间（秒）
     *
     * @apiSuccessExample Success-Response: HTTP/1.1 200 OK
     *{"code":1,"msg":"OK","data":[{"device_name":"5-vr","device_access":"9c32626fc323","online":1}]}
     * @apiSuccessExample Success-Response: HTTP/1.1 200 OK
     *{"code":1,"msg":"OK","data":[{"device_name":"5-vr","device_access":"9c32626fc323","online":0},{"device_name":"5-vr1","device_access":"0A62012DFCAB","online":0}]}
     * @apiSuccessExample Success-Response: HTTP/1.1 200 OK
     *{"code":1,"msg":"OK","data":[]}
     * @apiSuccessExample Success-Response: HTTP/1.1 200 OK
     *{"code":1,"msg":"OK","data":[{"device_name":"5-vr1","device_access":"0A62012DFCAB","online":0}]}
     *
     *
     * @apiErrorExample Error-Responsee: 
     *{"code":9009,"msg":"\u672a\u627e\u5230\u5f00\u53d1\u8005"}
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/mqtt/listBindedDevices
     */

    /**
     * @apiVersion 1.0.0
     *
     * @api {POST} api/mqtt/unbindDevice 104.0 解除已绑定设备
     * @apiName api_mqtt_unbindDevice
     * @apiGroup Open_Web
     *
     * @apiParam {String} developer_access 开发者 access
     * @apiParam {String} developer_pass 开发者 secret TOTP 结果
     * @apiParam {String} device_access 设备 Access Key
     * @apiParam {String} [unique_token=] 可选 安装 APP 的设备的唯一标识
     *
     *
     * @apiSuccessExample Success-Response: HTTP/1.1 200 OK
     *{"code":1,"msg":"OK","data":[]}
     *
     *
     * @apiErrorExample Error-Responsee: 
     *{"code":9003,"msg":"\u5f00\u53d1\u8005\u5bc6\u7801\u9519\u8bef","data":[]}
     * @apiErrorExample Error-Responsee: 
     *{"code":9005,"msg":"\u5c1a\u672a\u7ed1\u5b9a","data":[]}
     * @apiErrorExample Error-Responsee: 
     *{"code":9003,"msg":"\u8bbe\u5907 AccessKey \u4e0d\u80fd\u4e3a\u7a7a\u3002","data":[]}
     *
     * @apiSampleRequest http://test.open.qiweiwangguo.com/api/mqtt/unbindDevice
     */