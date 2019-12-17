<?php
namespace App\Models\User;

use App\Models\BaseModel;
use App\Exceptions\ServiceException;

class DeveloperApply extends BaseModel 
{
    /**
     * 审核状态，未审核
     * 
     * @var unknown
     */
    const STATUS_UNPROCESS = 1;

    /**
     * 审核状态，通过
     * 
     * @var unknown
     */
    const STATUS_PASS = 2;
    
    /**
     * 审核状态，失败
     *
     * @var unknown
     */
    const STATUS_FAIL = 3;

    protected $table = 'developer_apply';
    
    public $timestamps = false;
    
    public $guarded = [];

   
    protected $createTable = "
CREATE TABLE `op_developer_apply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL COMMENT '账号',
  `truename` varchar(80) DEFAULT NULL COMMENT '真实姓名',
  `phone` varchar(15) DEFAULT NULL COMMENT '手机号码',
  `province_id` smallint(6) DEFAULT NULL COMMENT '所在省份ID',
  `city_id` smallint(6) DEFAULT NULL COMMENT '所在市ID',
  `district_id` smallint(6) DEFAULT NULL COMMENT '区域ID',
  `address` varchar(50) DEFAULT NULL COMMENT '企业详细地址 ',
  `company` varchar(100) DEFAULT NULL COMMENT '公司',
  `reason` varchar(100) DEFAULT NULL COMMENT '失败理由',
  `note` varchar(100) DEFAULT NULL COMMENT '备注',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:未处理 2:通过 3:未通过',
  `operator` varchar(100) NOT NULL DEFAULT '1' COMMENT '审核操作人',
  `reviewed_at` timestamp NULL DEFAULT NULL COMMENT '审核时间',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '申请时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='申请开发者审核表'
        ";
    
    public static function createNewApply($data){
        $data = [
            'account_id' => $data['account_id'],//账号
            'truename' => $data['truename'],//真实姓名
            'phone' => $data['phone'],//手机号码
            'province_id' => $data['province_id'],//所在省份ID
            'city_id' => $data['city_id'],//所在市ID
            'district_id' => $data['district_id'],//区域ID
            'address' => $data['address'],//企业详细地址 
            'company' => $data['company'],//公司
            'created_at' => date('Y-m-d H:i:s'),//申请时间
        ];
        return self::create($data);
    }
    
    /**
     * 设置审核通过
     * @param unknown $accountID
     * @param unknown $applyID
     * @param string $note
     */
    protected static function setApplyPass($accountID,$applyID,$note = ''){
        \DB::beginTransaction();
        $updateData = [
            'status' => self::STATUS_PASS,
            'note' => $note,
            'reviewed_at' => date('Y-m-d H:i:s'),
        ];
        self::where('id',$applyID)->update($updateData);
        $updateData = [
            'apply_status' => Account::APPLY_STATUS_PASS
        ];
        Account::where('id',$accountID)->update($updateData);
        \DB::commit();
    }
    
    /**
     * 设置审核失败
     * @param unknown $accountID
     * @param unknown $applyID
     * @param string $note
     */
    protected static function setApplyFail($accountID,$applyID,$reason = '',$note = ''){
        \DB::beginTransaction();
        $updateData = [
            'status' => self::STATUS_FAIL,
            'note' => $note,
            'reason' => $reason,
            'reviewed_at' => date('Y-m-d H:i:s'),
        ];
        self::where('id',$applyID)->update($updateData);
        $updateData = [
            'apply_status' => Account::APPLY_STATUS_FAIL,
            'last_fail_reason' => $reason
        ];
        Account::where('id',$accountID)->update($updateData);
        \DB::commit();
    }
    
    public static function reviewDeveloperApply($applyID,$toStatus,$reason = '',$note = ''){
        $info = self::select([
            'account.id',
            'account.account',
            'account.apply_status',
            'developer_apply.status',
        ])->join('account','account.id','=','developer_apply.account_id')
        ->where('developer_apply.id',$applyID)
        ->first();
        if(!$info){
            throw new ServiceException('未找到申请信息',\ErrorCode::VITAL_NOT_FOUND);
        }
        if($info['status'] != self::STATUS_UNPROCESS){
            throw new ServiceException('该申请已被处理',\ErrorCode::LOGIC_ERROR);
        }
        if($info['apply_status'] != Account::APPLY_STATUS_APPLYING){
            throw new ServiceException('账户申请状态错误',\ErrorCode::UNEXPECTED);
        }
        if($toStatus == self::STATUS_PASS){
            self::setApplyPass($info['id'], $applyID,$note);
        }else if($toStatus == self::STATUS_FAIL){
            self::setApplyFail($info['id'], $applyID,$reason,$note);
        }else{
            throw new ServiceException('处理状态错误',\ErrorCode::UNEXPECTED);
        }
        return true;
    }
    
    public static function listApply($search,$page,$pageSize){
        $prefix = \DB::getTablePrefix();
        
        $handler = self::select([
            'developer_apply.id AS apply_id',
            'account.account',
            'account.apply_status',
            'developer_apply.account_id' ,//账号
            'developer_apply.truename',//真实姓名
            'developer_apply.phone',//手机号码
            'developer_apply.province_id',//所在省份ID
            'developer_apply.city_id',//所在市ID
            'developer_apply.district_id',//区域ID
            'developer_apply.address',//企业详细地址 
            'developer_apply.company',//公司
        ])->join('account','account.id','=','developer_apply.account_id')
        ->where('developer_apply.status',self::STATUS_UNPROCESS)
        ->orderBy('developer_apply.created_at','desc');
        $paginate = $handler->paginate($pageSize, [
            '*'
        ], 'page', $page);
        $list = $paginate->toArray();
        
        foreach ($list['data'] as  $k => $v){
            $list['data'][$k]['region'] = \App\Models\Common\Region::getRegionName($v['province_id'],$v['city_id'],$v['district_id']);
            unset($list['data'][$k]['province_id']);
            unset($list['data'][$k]['city_id']);
            unset($list['data'][$k]['district_id']);
        }
        
        $data = [
            'total' => $list['total'],
            'current_page' => $list['current_page'],
            'last_page' => $list['last_page'],
            'per_page' => $list['per_page'],
            'list' => $list['data']
        ];
        return $data;
    }
    
    
}