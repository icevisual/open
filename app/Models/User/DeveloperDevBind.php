<?php
namespace App\Models\User;

use App\Models\BaseModel;
use App\Exceptions\ServiceException;
use phpDocumentor\Reflection\Types\Static_;

class DeveloperDevBind extends BaseModel 
{

    protected $table = 'developer_device_blind';
    
    public $timestamps = false;
    
    public $guarded = [];
   
    protected $createTable = "
DROP TABLE IF EXISTS `op_developer_device_blind`;
CREATE TABLE `op_developer_device_blind` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `developer_id` int(11) NOT NULL COMMENT '开发者ID',
  `device_access_key` varchar(100) NOT NULL COMMENT '设备access_key',
  `bind_at` timestamp NULL DEFAULT NULL COMMENT '绑定时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='开发者和设备绑定表';
        ";

    public static function isBindedByID ($developer_id,$access_key,$unique_token = ''){
        $where = [
            'developer_id' => $developer_id,
            'device_access_key' => $access_key,
        ];
        $unique_token && $where['unique_token'] = $unique_token;
        return self::where($where)->count();
    }
    
    
    public static function unbindDevice($developer_id,$access_key,$unique_token = ''){
        $where = [
            'developer_id' => $developer_id,
            'device_access_key' => $access_key,
        ];
        $unique_token && $where['unique_token'] = $unique_token;
        return $developer_id && $access_key && self::where($where)->delete();
    }
    
    
    public static function isBindedByAccess ($developer_access,$deveice_access,$unique_token = ''){
        $where = [
            'developer_access_key.access' => $developer_access,
            'developer_device_blind.device_access_key' => $deveice_access,
        ];
        $unique_token && $where['developer_device_blind.unique_token'] = $unique_token;
        return self::join('developer_access_key','developer_access_key.account_id','=','developer_device_blind.developer_id')
                    ->where($where)->count();
    }
    
    public static function bindDevice ($developer_id,$access_key,$unique_token = '',$blind_at = ''){
        if(!self::isBindedByID($developer_id, $access_key,$unique_token)){
            $blind_at = $blind_at ? $blind_at : date('Y-m-d H:i:s');
            $data = [
                'developer_id' => $developer_id,
                'device_access_key' => $access_key,
                'bind_at' => $blind_at,
            ];
            if($unique_token){
                $data['unique_token'] = $unique_token;
            }
            return self::create($data);
        }
        return true;
    }
    
    public static function listUserBindedDevices($developerID,$uniqueToken = ''){
        $dev = new \App\Models\Open\Device();
        $_this = new Static;
        $dbHost_dev = $dev->getConnection()->getConfig('host');
        $dbHost_this = $_this->getConnection()->getConfig('host');
        $binded_dev_list = [];
        if($dbHost_dev == $dbHost_this){
            $prefix_dev = $dev->getConnection()->getConfig('prefix');
            $dbName_dev = $dev->getConnection()->getConfig('database');
            $tablename_dev = $dev->getTable();
            $prefix_this = $_this->getConnection()->getConfig('prefix');
            $dbName_this = $_this->getConnection()->getConfig('database');
            $tablename_this = $_this->getTable();
            $sql = "
SELECT
	b.alias AS device_name,
    b.access_key AS device_access
FROM
	{$dbName_this}.{$prefix_this}{$tablename_this} AS a
JOIN {$dbName_dev}.{$prefix_dev}{$tablename_dev} AS b ON a.device_access_key = b.access_key
WHERE 
  b.deleted_at is null AND 
	a.developer_id = ?              
                ";
	        $params = [$developerID];
        	if($uniqueToken){
        	    $sql .= ' and a.unique_token = ? ';
        	    $params[] = $uniqueToken;
        	}
        	$sql .= ' group by  a.device_access_key ';
	        $binded_dev_list =  \DB::select($sql,$params);
	        $binded_dev_list = json_encode($binded_dev_list);
	        $binded_dev_list = json_decode($binded_dev_list,1);
        }else{
            $device_access_keys_where = [
                'developer_id' => $developerID
            ];
            if($uniqueToken){
                $device_access_keys_where['unique_token'] = $uniqueToken;
            }
            $device_access_keys = self::where($device_access_keys_where)->get(['device_access_key']);
            $device_access_keys_array = [];
            foreach ($device_access_keys as $v){
                $device_access_keys_array[] = $v['device_access_key'];
            }
            $binded_dev_list = $dev::select([
                'alias AS device_name','access_key AS device_access'
            ])->whereIn('access_key',$device_access_keys_array)
            ->whereNull('deleted_at')
            ->get();
            $binded_dev_list && $binded_dev_list = $binded_dev_list->toArray();
        }
        return $binded_dev_list;
    }
    
    
    
}