<?php
namespace App\Models\User;

use App\Models\BaseModel;
use App\Exceptions\ServiceException;

class AccessKey extends BaseModel 
{
    /**
     * 使用状态，正在使用
     * 
     * @var int
     */
    const STATUS_USING = 1;

    /**
     * 使用状态，停止使用
     * 
     * @var int
     */
    const STATUS_DISABLE = 2;
    

    protected $table = 'developer_access_key';
    
    public $timestamps = false;
    
    public $guarded = [];
   
    protected $createTable = "
CREATE TABLE `op_developer_access_key` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL COMMENT '账号',
  `access` varchar(100) DEFAULT NULL COMMENT 'access key',
  `secret` varchar(100) DEFAULT NULL COMMENT 'secret key',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:使用中 2:停用',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '申请时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='开发者accessKey'
        ";

    /**
     * @param $uid
     * @param $accessKey
     * @return mixed
     * @throws ServiceException
     */
    public static function showSecretKey($uid,$accessKey){
        $info = self::select([
            'secret',
        ])->where('account_id',$uid)
        ->where('access',$accessKey)
        ->first();
        if(!$info){
            throw new ServiceException('未找到该秘钥',\ErrorCode::VITAL_NOT_FOUND);
        }
        return $info['secret'];
    }
    
    public static function listAccessKeyPair($uid){
        //         foreach ($list as $k => $v){
//             $list[$k]['secret'] = '******************************';
//         }
        return self::select([
            'access',
            'secret',
            'status',
            'created_at'
        ])->where('account_id',$uid)
        ->orderBy('created_at','asc')
        ->get()
        ->toArray();
    }
    
    public static function createNewAccessKeyIfNotExists($uid){
        $accessCount = self::where('account_id',$uid)->count();
        if(!$accessCount){
            self::createAccessKeyPair($uid);
        }
    }
    
    public static function canCreateAccess($uid){
        return self::where('account_id',$uid)->count() < 2;
    }

    /**
     * @param $uid
     * @param $accessKey
     * @return mixed
     * @throws ServiceException
     */
    public static function deleteAccess($uid,$accessKey){
        $query = self::where('account_id',$uid)->where('access',$accessKey);
        $info = $query->first();
        if(!$info){
            throw new ServiceException('未找到该秘钥',\ErrorCode::VITAL_NOT_FOUND);
        }
        if($info['status'] == self::STATUS_USING){
            throw new ServiceException('该秘钥正在使用',\ErrorCode::UNEXPECTED);
        }
        return $query->delete();
    }

    /**
     * @param $uid
     * @param $accessKey
     * @return int
     * @throws ServiceException
     */
    public static function disableAccessKey($uid,$accessKey){
        $query = self::where('account_id',$uid)->where('access',$accessKey);
        $info = $query->first();
        if(!$info){
            throw new ServiceException('未找到该秘钥',\ErrorCode::VITAL_NOT_FOUND);
        }
        if($info['status'] == self::STATUS_DISABLE){
            throw new ServiceException('该秘钥已停用',\ErrorCode::UNEXPECTED);
        }
        $accessCount = self::where('account_id',$uid)->where('status',self::STATUS_USING)->count();
        if(1 == $accessCount){
            throw new ServiceException('只有一个秘钥在使用中，不可停用',\ErrorCode::UNEXPECTED);
        }
        return $query->update([
            'status' => self::STATUS_DISABLE
        ]);
    }

    /**
     * @param $uid
     * @param $accessKey
     * @return int
     * @throws ServiceException
     */
    public static function enableAccessKey($uid,$accessKey){
        $query = self::where('account_id',$uid)->where('access',$accessKey);
        $info = $query->first();
        if(!$info){
            throw new ServiceException('未找到该秘钥',\ErrorCode::VITAL_NOT_FOUND);
        }
        if($info['status'] == self::STATUS_USING){
            throw new ServiceException('该秘钥已在使用',\ErrorCode::UNEXPECTED);
        }
        return $query->update([
            'status' => self::STATUS_USING
        ]);
    }
    
    public static function createAccessKeyPair($uid,$status = self::STATUS_USING){
        $data = [
            'account_id' => $uid,//账号
            'access' => str_random(20),//access key
            'secret' => str_random(20),//secret key
            'status' => $status,//1:使用中 2:停用
            'created_at' => date('Y-m-d H:i:s'),//申请时间
        ];
        return self::create($data);
    }

    /**
     *
     * @param string $accessKey
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null ['account_id','secret']
     */
    public static function getDeveloperIDByAccessKey($accessKey){
        return self::where('access',$accessKey)->first(['account_id','secret']);
    }
    
    
}