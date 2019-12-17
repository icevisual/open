<?php

namespace App\Models\Common;

use App\Exceptions\NeedRecordException;
use App\Models\BaseModel;

class Parameters extends BaseModel
{
    protected $table = 'request_params';
    
    public $timestamps = false;
    
    public $guarded = [];
    
    protected $createSql = "
DROP TABLE IF EXISTS `op_request_params`;
CREATE TABLE `op_request_params` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name_en` varchar(100) NOT NULL COMMENT '英文名',
  `name_zh` varchar(255) DEFAULT NULL COMMENT '中文名',
  `default` varchar(255) DEFAULT NULL COMMENT '默认值',
  `type` varchar(50) DEFAULT NULL COMMENT '类别',
  `sha1` varchar(255) DEFAULT NULL COMMENT 'SHA1',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='请求参数日志'


        ";
    
    
    public static function listAll(){
        $data = self::all([
            'name_en',
            'name_zh',
            'sha1',
        ]);
        $list = [];
        foreach ($data as $v){
            $list[$v['name_en']][$v['sha1']] = $v;
        }
        return $list;
    }
    
    
    public static function cacheParameters($parameters){
        $all = self::listAll();
        $now = date('Y-m-d H:i:s');
        \DB::beginTransaction();
        foreach ($parameters  as $name_en => $v){
            ksort($v);
            $sha1 = substr(sha1(serialize($v)), 0,6) ;
            if(!isset($all[$name_en][$sha1])){
                self::create([
                    'name_en' => $name_en,//英文名
                    'name_zh' => $v['name'],//中文名
                    'default' => array_get($v, 'default'),//默认值
                    'type' => $v['type'],//类别
                    'sha1' => $sha1,//SHA1
                    'created_at' => $now,//创建时间
                ]);
                $all[$name_en][$sha1] = 1;
            }
        }
        \DB::commit();
    } 
    
    public static function searchParameters($parameter){
        static $_cached = [];
        if(isset($_cached[$parameter])){
            return $_cached[$parameter];
        }
        $dbPrefix = \DB::getTablePrefix();
        $data = self::select([
            'name_zh',
            \DB::raw("COUNT(id) AS count")
        ])->where('name_en',$parameter)
        ->groupBy('name_zh')
        ->orderByRaw(('count(id) desc'))
        ->first();
        
        if($data && $data->toArray()){
            $_cached[$parameter] = $data->name_zh;
            return $_cached[$parameter];
        }
        return false;
    }
    
    
}