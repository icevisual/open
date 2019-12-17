<?php
namespace App\Models\Common;

use App\Exceptions\NeedRecordException;
use App\Models\BaseModel;

class Region extends BaseModel
{

    protected $table = 'region';

    public $timestamps = false;

    public $guarded = [];

    
    public static function getRegionName()
    {
        $ids = func_get_args();
        $data = self::getAllRegionsCache();
        $list = $data;
        $name = '';
        foreach ($ids as $id){
            if(!isset($list[$id]) || !isset($list[$id]['name'])){
                break;
            }
            $name .= $list[$id]['name'];
        }
        return $name;
    }
    
    
    
    public static function listSubitems($pid)
    {
//         return self::select([
//             'cid',
//             'name'
//         ])->where('cup', $pid)->get();
        $data = self::getAllRegionsCache();
        $childrens = isset($data[$pid]['children']) ? $data[$pid]['children'] : [];
        $ret = [];
        foreach ($childrens as $v){
            $ret[] = array_only($v, ['cid','name']);
        }
        return $ret;
    }
    
    public static function getAllRegionsCache(){
        $key = 'open:region:cache';
        if (\Cache::has($key)) {
            $data = \Cache::get($key);
        } else {
            $data = self::getAllRegions();
            \Cache::put($key, $data, 1440);
        }
        return $data;
    }

    public static function getAllRegions()
    {
        $data = self::select([
            'cid',
            'cup',
            'name'
        ])->get()->toArray();
        $ret = [];
        foreach ($data as $v) {
            if (isset($ret[$v['cid']])) {
                $ret[$v['cid']] += $v;
            } else {
                $ret[$v['cid']] = $v;
            }
            $ret[$v['cup']]['children'][] = &$ret[$v['cid']];
        }
        return $ret;
    }
}
