<?php
namespace App\Models\User;

use App\Models\BaseModel;
use App\Exceptions\ServiceException;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laravel\Passport\HasApiTokens;

class Account extends BaseModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    
    use HasApiTokens, Authenticatable, Authorizable, CanResetPassword;

    /**
     * 账户状态，正常
     * 
     * @var int
     */
    const STATUS_OK = 1;

    /**
     * 账户状态，冻结
     * 
     * @var int
     */
    const STATUS_FROZEN = 2;

    /**
     * 邮箱验证状态，未
     * 
     * @var int
     */
    const EMAIL_ACTIVATION_NO = 1;

    /**
     * 邮箱验证状态，已
     * 
     * @var int
     */
    const EMAIL_ACTIVATION_YES = 2;

    /**
     * 手机验证状态，未
     * 
     * @var int
     */
    const PHONE_ACTIVATION_NO = 1;

    /**
     * 手机验证状态，已
     * 
     * @var int
     */
    const PHONE_ACTIVATION_YES = 2;
    
    /**
     * 开发者申请状态，未申请
     *
     * @var int
     */
    const APPLY_STATUS_NO = 1;
    
    /**
     * 开发者申请状态，已申请
     *
     * @var int
     */
    const APPLY_STATUS_APPLYING = 2;
    
    /**
     * 开发者申请状态，已通过
     *
     * @var int
     */
    const APPLY_STATUS_PASS = 3;
    
    /**
     * 开发者申请状态，未通过
     *
     * @var int
     */
    const APPLY_STATUS_FAIL = 4;
    
    // `apply_status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1:未申请 2:已申请 3:申请通过 4:申请未通过',

    protected $table = 'account';
    
    // public $timestamps = false;
    public $guarded = [];

    /**
     * 为路由模型获取键名。
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'phone';
    }

    /**
     * 用户是否在注册的第一二步之间
     *
     * @param int $uid
     * @return Account|Account[]|\Illuminate\Database\Eloquent\Collection|Model|null
     * @throws ServiceException
     */
    public static function betweenFirstSecond($uid)
    {
        $info = Account::find($uid, [
            'email_activation',
            'email'
        ]);
        if (! $info) {
            throw new ServiceException('error', \ErrorCode::VITAL_NOT_FOUND,[
                'email' => '未找到该用户'
            ]);
        }
        if (Account::EMAIL_ACTIVATION_YES == $info->email_activation) {
            throw new ServiceException('error', \ErrorCode::LOGIC_ERROR,[
                'email' => '邮箱已激活'
            ]);
        }
        return $info;
    }

    /**
     * @param $uid
     * @return Account|Account[]|\Illuminate\Database\Eloquent\Collection|Model|null
     * @throws ServiceException
     */
    public static function betweenSecondThird($uid)
    {
        $info = Account::find($uid, [
            'email_activation',
            'email',
            'phone_activation',
            'phone',
            'apply_status',
        ]);
        if (! $info) {
            throw new ServiceException('未找到该用户', \ErrorCode::VITAL_NOT_FOUND);
        }
        if (Account::EMAIL_ACTIVATION_YES != $info->email_activation) {
            throw new ServiceException('请先激活邮箱', \ErrorCode::LOGIC_ERROR);
        }
        if (Account::APPLY_STATUS_APPLYING == $info->apply_status) {
            throw new ServiceException('您正在申请为开发者，请勿重复申请', \ErrorCode::LOGIC_ERROR);
        }
        return $info;
    }
    
    /**
     * 判断是否是开发者
     * @param unknown $uid
     * @param Object $user
     * @return boolean
     */
    public static function judgeDeveloper($uid,$user = null)
    {
        $user || $user = Account::find($uid, [
            'email_activation',
            'phone_activation',
            'apply_status'
        ]);
        if ($user) {
            if (Account::EMAIL_ACTIVATION_YES == $user->email_activation
                && Account::PHONE_ACTIVATION_YES == $user->phone_activation
                && Account::APPLY_STATUS_PASS == $user->apply_status){
                return true;
            }
        }
        return false;
    }

    public static function completingInformation($uid, $data)
    {
        $update = [
//             'truename' => $data['truename'], // 真实姓名
//             'phone' => $data['phone'], // 手机号码
//             'company' =>  $data['company'], // 所在省份ID
//             'province_id' => '', // 所在省份ID
//             'city_id' => '', // 所在市ID
//             'district_id' => '', // 区域ID
//             'address' => $data['address'], // 企业详细地址
//             // 是否手机验证，1:未 2:已验证
//             'phone_activation' => self::PHONE_ACTIVATION_YES,
            'apply_status' => self::APPLY_STATUS_APPLYING
        ] ;
        
//         list ($update['province_id'], $update['city_id'], $update['district_id']) = explode(',', $data['regions']);
        return self::where('id', $uid)->update($update);
    }

    /**
     * 验证邮箱是否可以用于找回密码
     *
     * @param string $email
     * @return mixed
     * @throws ServiceException
     */
    public static function validForgetEmail($email)
    {
        $info = self::where('email', $email)->first([
            'email_activation',
            'id'
        ]);
        if (! $info) {
            throw new ServiceException('邮箱未注册！', \ErrorCode::VITAL_NOT_FOUND);
        }
        if (self::EMAIL_ACTIVATION_YES != $info->email_activation) {
            throw new ServiceException('邮箱未激活！', \ErrorCode::LOGIC_ERROR);
        }
        return $info->id;
    }

    public static function getDeveloperInfo($uid)
    {
        // CREATE TABLE `op_account` (
        // `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        // `account` varchar(80) NOT NULL DEFAULT '' COMMENT '账号',
        // `password` varchar(100) DEFAULT NULL COMMENT '密码',
        // `truename` varchar(80) DEFAULT NULL COMMENT '真实姓名',
        // `phone` varchar(15) DEFAULT NULL COMMENT '手机号码',
        // `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
        // `province_id` smallint(6) DEFAULT NULL COMMENT '所在省份ID',
        // `city_id` smallint(6) DEFAULT NULL COMMENT '所在市ID',
        // `district_id` smallint(6) DEFAULT NULL COMMENT '区域ID',
        // `address` varchar(50) DEFAULT NULL COMMENT '企业详细地址 ',
        // `email_activation` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否邮箱验证，1:未 2:已验证',
        // `phone_activation` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否手机验证，1:未 2:已验证',
        // `remember_token` varchar(62) DEFAULT NULL,
        // `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:正常 2:冻结',
        // `lastlogin_at` timestamp NULL DEFAULT NULL COMMENT '最后登录时间',
        // `prevlogin_at` timestamp NULL DEFAULT NULL COMMENT '上次登录时间',
        
        // `secret` char(16) DEFAULT NULL COMMENT '登录密钥',
        // `expired_at` timestamp NULL DEFAULT NULL COMMENT '登录过期时间',
        
        // `registered_at` timestamp NULL DEFAULT NULL COMMENT '注册时间',
        // `created_at` timestamp NULL DEFAULT NULL,
        // `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
        // PRIMARY KEY (`id`)
        // ) ENGINE=InnoDB AUTO_INCREMENT=320 DEFAULT CHARSET=utf8 COMMENT='企业账号表'
        self::select([
            'account.truename',
            'account.phone',
            'account.email',
            'account.province_id',
            'account.city_id',
            'account.district_id',
            'account.address',
            'account.prevlogin_at',
            'account.registered_at'
        ])->where('account.id', $uid)->first();
    }
    
    public static function setRegisterOverAndLogin($email){
        $user = Account::where('account',$email)->first();
        \Auth::login($user,false);
    }
    
}