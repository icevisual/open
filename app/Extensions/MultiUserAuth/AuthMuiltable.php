<?php
namespace App\Extensions\MultiUserAuth;

use Illuminate\Support\Str;
use App\Models\Enterprise\Company;
use App\Models\Authority\Operator;

trait AuthMuiltable
{
    
    
    protected $messages = [
        'singleTokenCheck' => '只允许单人的登录!',
        'companyLoginAuthorityCheck' => '企业已取消合作!',
        'operatorDisabledCheck' => '该账号已被禁用!',
    ];
    

    protected $denyRules = [
        'singleTokenCheck',
        'companyLoginAuthorityCheck',
        'operatorDisabledCheck'
    ];

    protected $loginDenyRules = [
        'companyLoginAuthorityCheck',
        'operatorDisabledCheck'
    ];
    
    public function getErrorMessage($rule){
        return isset($this->messages[$rule])?$this->messages[$rule]:'';
    }

    /**
     * 检测操作员是否被禁用
     *
     * @return boolean
     */
    public function operatorDisabledCheck()
    {
        return $this->status == Operator::STATUS_USING;
    }

    /**
     * 检测Session缓存的singleToken和数据库的是否一样
     *
     * @return boolean
     */
    public function singleTokenCheck()
    {
        if (session(md5($this->getSingleTokenName())) != $this->{$this->getSingleTokenName()}) {
            return false;
        }
        return true;
    }

    /**
     * 除了用户名密码验证之外的登录验证
     * 
     * @return boolean
     */
    public function validateBesidesCredentials()
    {
        foreach ($this->loginDenyRules as $v) {
            if (method_exists($this, $v)) {
                if (! $this->$v()) {
                    
                    $message = $this->getErrorMessage($v);
                    
                    session(['besidesLoginError' => $message]);
                    
                    \Log::info(__FUNCTION__, [
                        $v . ' failed'
                    ]);
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 登录后的验证，业务需要
     *
     * @return boolean
     */
    public function validateAfterLogin()
    {
        foreach ($this->denyRules as $v) {
            if (method_exists($this, $v)) {
                if (! $this->$v()) {
                    
                    $message = $this->getErrorMessage($v);
                    
                    session(['afterLoginError' => $message]);
                    
                    \Log::info(__FUNCTION__, [
                        $v . ' failed'
                    ]);
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 此登录系统，需引入第三个实体-企业，企业账户和企业操作员账户都是企业的一个代表
     *
     * @return boolean
     */
    public function companyLoginAuthorityCheck()
    {
        $company = $this->getLoginCompany();
        
        return $company && $company->status == Company::STATUS_COOPERATION;
    }

    public function getLoginUserName()
    {
        return $this->{$this->getUserNameColumn()};
    }

    public function userAuthorityCheck($action)
    {
        if (isset($action['group'])) {
            $authority = $this->getUserAuthority();
            if (! ($authority & $action['group'])) {
                return false;
            }
        }
        return true;
    }

    public function getUserAuthority()
    {
        $companyInfo = $this->getLoginCompany();
        
        // 无账户企业 独立账户系统的区别 111111
        $authority = isset($this->{'authority'}) ? bindec($this->{'authority'}) : '';
        $authority == '' && $authority = bindec('1111111111111111111111');
        
        return $authority;
    }

    public function getSingleToken()
    {
        return $this->{$this->getSingleTokenName()};
    }

    public function setSingleToken($value)
    {
        $this->{$this->getSingleTokenName()} = $value;
    }

    /**
     * 获取单次登录的令牌
     *
     * @return string
     */
    public function getSingleTokenName()
    {
        return 'single_token';
    }

    /**
     * 不同的登录用户Model对应企业的查询关系不同
     *
     * @return string
     */
    public function getCompanyRetrieveCondition()
    {
        // Setting In Array Or Function
        return 'account_id';
    }

    /**
     * 获取登录的企业信息
     */
    public function getLoginCompany()
    {
        if (isset($this->{'company'})) {
            return $this->company;
        }
        
        $keyValue = $this->{$this->getCompanyRetrieveCondition()};
        
        $value = is_array($keyValue) ? $keyValue['value'] : $keyValue;
        
        $this->company = Company::where($this->getCompanyRetrieveCondition(), $value)->first();
        
        return $this->company;
    }

    /**
     * 设置一个账号在同一时间只能一人登录
     */
    public function singleTokenSet()
    {
        $now = time();
        
        $identifier = $this->getKey()['value'];
        
        $singleToken = sha1(\Hash::make(time() . $this->getKeyName() . '-' . $identifier));
        
        $update = [
            'lastlogin' => date('Y-m-d H:i:s', $now),
            'prevlogin' => $this->lastlogin,
            $this->getSingleTokenName() => $singleToken
        ];
        $this->where($this->getKeyName(), $identifier)
            ->update($update);
        
        session([
            md5($this->getSingleTokenName()) => $singleToken
        ]);
    }

    /**
     * 通过登录的输入信息获取用户实体
     *
     * @param array $credentials            
     * @return unknown
     */
    public function retrieveByCredentials(array $credentials)
    {
        $query = $this->newQuery();
        foreach ($credentials as $key => $value) {
            if (! Str::contains($key, 'password')) {
                $query->where($key, $value);
            }
        }
        $result = $query->first();
        
        // 多个Model做登录用户，在\Auth::guest() 检测时，
        // 会通过$id 以及 UserProvider的retrieveById方法 获取 登录用户
        // 以检测 已登录的Session信息，在不改框架源码的情况下，通过扩展Model的主键的值来实现不同Model类的分辨
        // 结合 重写相应的retrieveById方法动态选择Model类
        $result && $result[$this->getKeyName()] = [
            'class' => __CLASS__,
            'value' => $result[$this->getKeyName()]
        ];
        return $result;
    }
}