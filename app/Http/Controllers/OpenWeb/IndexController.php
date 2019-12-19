<?php
namespace App\Http\Controllers\OpenWeb;

use App\Http\Controllers\Controller;
use App\Services\Open\LoginServices;
use App\Http\Controllers\Open\IndexController as ApiIndexController;
use App\Models\User\Account;
use App\Models\User\AccessKey;
use App\Services\Open\OpenServices;

class IndexController extends Controller
{
    
    
    /**
     * @var \App\Services\Open\OpenServices
     */
    protected $openServices;
    
    public function __construct(){
        parent::__construct();
        $this->openServices = new OpenServices();
    }
    
    
    public function error_404(){
        return view('errors.404');
    }
    
    public function error_400($error = '' ){
        return view('errors.400')->with([
            'error' => $error
        ]);
    }
   
    public function index(){
        return view('open.index.index');
    }

    public function device(){
        return view('open.index.device');
    }
    
    public function developer(){
        return view('open.index.developer')->with([
            'isLogin' => \Auth::guest() ? 0 : 1
        ]);
    }

    public function register($step = 0){
        $data = [
            'registeringEmail' => $this->openServices->getRegistingEmail()
        ];
        if($step && !$data['registeringEmail']){
            return redirect(route('developer'));
        }
        if(! $step && $data['registeringEmail']){
            $data['registeringEmail'] = null;
        }
        if('step3' === $step){
            
            $this->openServices->clearSessionUid();
            $this->openServices->clearRegistingEmail();
            return view('open.index.registerStep3')->with($data);
        }
        if('step2' === $step){
            
            $user = Account::where('account',$data['registeringEmail'])->first();
            if($user->email_activation == Account::EMAIL_ACTIVATION_YES){
                // 多浏览器操作，session无法同步，认要求刷新页面跳转
                $this->openServices->clearSessionUid();
                $this->openServices->clearRegistingEmail();
                \Auth::login($user,false);
                return redirect(route('developer'));
            }
            
            return view('open.index.registerStep2')->with($data);
        }
        return view('open.index.register')->with($data);
    }
    
    public function search(){
        return view('open.index.search');
    }
    
    public function wiki(){
        $user = \Auth::getUser();
        $lastFailReason = $user->last_fail_reason ? $user->last_fail_reason : '填写信息不正确';
        return view('open.index.wiki')->with([
            'applyStatus' => $user->apply_status,
            'lastFailReason' => $lastFailReason,
            'documentation_host' => \Config::get('app.documentation_host'),
        ]);
    }

    public function forget(){
        return view('open.index.forget');
    }
    
    public function reset(){
        $token = $this->openServices->getResetingToken();
        if(!$token){
            return redirect(route('developer'));
        }
        return view('open.index.reset')->with([
            'token' => $token
        ]);
    }
    
    public function password(){
        return view('open.index.password');
    }
    
    public function secrect(){
        $user =  \Auth::getUser();
        $applyStatus = $user->apply_status;
        
        switch ($applyStatus){
            case  Account::APPLY_STATUS_APPLYING:
                return redirect(route('wiki'));
            case  Account::APPLY_STATUS_NO:;
            case  Account::APPLY_STATUS_FAIL:
                return redirect(route('apply'));
            case  Account::APPLY_STATUS_PASS:
                break;
            default:
                return $this->error_400('开发者状态错误！');
        }
        
        AccessKey::createNewAccessKeyIfNotExists($user->id);
        $list = AccessKey::listAccessKeyPair($user->id);
        $usingCount = 0;
        foreach ($list as $v){
            if($v['status'] == AccessKey::STATUS_USING){
                $usingCount ++;
            }
        }
        return view('open.index.secrect')->with([
            'list' => $list,
            'usingCount' => $usingCount,
            'count' => count($list)
        ]);
    }
    
    public function apply(){
        $applyStatus = \Auth::getUser()->apply_status;
        if($applyStatus == Account::APPLY_STATUS_APPLYING
            || $applyStatus == Account::APPLY_STATUS_PASS ){
            return redirect(route('wiki'));
        }
        return view('open.index.apply');
    }
    
    
    
}


