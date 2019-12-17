<?php

namespace App\Models\Open;

use App\Models\BaseModel;
use App\Exceptions\ServiceException;

class Device extends BaseModel
{
    protected $table = 'smell_device';
    
    protected $connection = 'smell_oa';
    
    public $timestamps = false;
    
    public $guarded = ['*'];
    
    public static function searchByAccessKey($accessKey){
        return self::select([
            'id AS account_id',
            'access_key AS access',
            'secret_key AS secret',
        ])->where('access_key',$accessKey)
        ->whereNull('deleted_at')
        ->first();
    }
    
    
    /**
     * 通过 设备名称 和secret key 验证设备，并返回 access_key
     * @param string $deviceName
     * @param string $secretKey
     * @throws ServiceException
     * @return string access_key
     */
    public static function authorityWithDevNameAndSecret($deviceName ,$secretKey){
        $ret = self::select([
            'alias',
            'access_key',
            'secret_key',
        ])->where('alias',$deviceName)
        ->whereNull('deleted_at')->first();
        if($ret){
            if(!$ret['access_key'] || !$ret['secret_key']){
                throw new ServiceException('设备 AK\\SK 信息缺失',\ErrorCode::VITAL_NOT_FOUND);
            }
            if(totp_secret_compare($ret['secret_key'],$secretKey)){
                return $ret['access_key'];
            }else{
                throw new ServiceException('设备密码错误',\ErrorCode::VALIDATION_FAILED);
            }
        }else{
            throw new ServiceException('未找到该设备',\ErrorCode::VITAL_NOT_FOUND);
        }
        
    }
    
    public static function getDeviceAccessByName ($deviceName){
        $ret = self::where('alias',$deviceName)
        ->whereNull('deleted_at')->first([
            'access_key',
        ]);
        if($ret){
             return $ret['access_key'];
        }
        return false;
    }
    
}