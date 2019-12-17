<?php
namespace App\Http\Controllers\Common;

use Route;
use View;
use App\Http\Controllers\Controller;

class RouteController extends Controller
{

    /**
     * 分割action
     * 
     * @param unknown $action_name            
     * @return multitype:|boolean
     */
    public function compileAction($action_name)
    {
        if ('Closure' != $action_name) {
            if (strpos($action_name, '@')) {
                $action = explode('@', $action_name);
                return $action;
            }
        }
        return false;
    }

    /**
     * 获取action指向方法的所需参数
     * 
     * @param unknown $action            
     * @return multitype:boolean
     */
    public function getInputParams($action)
    {
        $codes = getFunctionDeclaration($action);
        return $this->filterParams($codes);
    }
    

    /**
     * 判别所需参数，现以Input::get()判定
     * 
     * @param unknown $codes            
     * @return multitype:boolean
     */
    public function filterParams($codes)
    {
        $params = array();
        if (! is_array($codes))
            return false;
        array_walk($codes, function ($v, $k) use(&$params) {
            
            $regs = [
                '/(?:Input::get|\$request->input)\s*\(\s*[\'\"]([\w\d_]*)[\'\"]\s*(?:\s*,\s*[\'\"]?([\s\w_\-]*)[\'\"]?\s*)?\)/',
                '/\$_(?:POST|GET)\s*\[[\'\"]([\w\d_]*)[\'\"]\]/'
            ];
            
            foreach ($regs as $regex) {
                $r = preg_match($regex, $v, $matchs);
                if ($r) {
                    $params[$matchs[1]] = true;
                    if (isset($matchs[2])) {
                        $params[$matchs[1]] = $matchs[2];
                    }
                    break;
                }
            }
        });
        // dump($params);
        return $params;
    }

    /**
     * 获取filter内用到的参数
     * 
     * @param unknown $filter            
     * @return multitype:boolean
     */
    public function getFilterParams($filter)
    {
        $codes = $this->getFilterCode($filter);
        return $this->filterParams($codes);
    }

    /**
     * 获取filter内用到的参数
     * 
     * @param unknown $filter            
     * @return multitype:boolean
     */
    public function getMiddlewareParams($Middleware)
    {
        $ignoreArray = [
            'web',
            'api'
        ];
        
        if (in_array($Middleware, $ignoreArray)) {
            return [];
        }
        $MiddlewareMap = \Route::getMiddleware();
        $MiddlewareClass = $MiddlewareMap[$Middleware];
        
        $codes = getFunctionDeclaration([
            $MiddlewareClass,
            'handle'
        ]);
        return $this->filterParams($codes);
    }

    /**
     * 获取filter的代码
     * 
     * @param unknown $filter            
     * @return Ambigous <boolean, multitype:Ambigous >
     */
    public function getFilterCode($filter)
    {
        // $app = app();
        // $filterClosure = $app['events']->getListeners('r.filter: '.$filter);
        // $code = getFunctionDeclaration($filterClosure[0]);
        $filters = array(
            'redpacket_switch' => 'Redpacket\RedpacketController@get_redpacket_status',
            'uid_token' => 'Redpacket\RedpacketController@verifyUserToken',
            'crm_auth' => 'Lend\LendController@crm_auth'
        );
        if (! isset($filters[$filter]))
            return false;
        $action = $this->compileAction($filters[$filter]);
        $codes = getFunctionDeclaration($action);
        return $codes;
    }

    public function phoneAttribution($phone)
    {
        $api = 'http://v.showji.com/Locating/showji.com20150416273007.aspx?output=json&m=' . $phone;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $User_Agen = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36';
        curl_setopt($ch, CURLOPT_USERAGENT, $User_Agen);
        $result = curl_exec($ch);
        
        $result = json_decode($result, true);
        
        if (json_last_error() == JSON_ERROR_NONE && isset($result['QueryResult']) && $result['QueryResult'] == 'True') {
            $data = array(
                'province' => $result['Province'],
                'city' => $result['City'],
                'areacode' => $result['AreaCode'],
                'zip' => $result['PostCode'],
                'company' => $result['TO'],
                'card' => $result['Card']
            );
            return $data;
        }
    }

    public function index()
    {
        $ignoreRoutes = [
            '/',
            'web/route2',
            'web/test',
            'web/route',
            'web/routeApiData'
        ];
        
        // 获取接口调用频度
        $todayReq = [];
        
        $routes = Route::getRoutes();
        
        $baseUrls = array(
            'Localhost' => 'http://' . $_SERVER['HTTP_HOST'],
            'Test Api' => 'http://test.open.qiweiwangguo.com'
        );
        $todayReqF = [];
        $routes_select = array();
        $all_params = array();
        foreach ($routes as $v) {
            $data = array();
            $method = array();
            $methods = $v->getMethods();
            $uri = $v->getPath();
            $action = $v->getActionName();
            
            if (in_array($uri, $ignoreRoutes)) {
                continue;
            }
            
            $actionData = $v->getAction();
            // 分割action
            $action = $this->compileAction($action);
            
            if (! method_exists($action[0], $action[1])) {
                continue;
            }
            in_array('POST', $methods) and $method[] = 'POST';
            in_array('GET', $methods) and $method[] = 'GET';
            // 生成method和uri
            ! empty($method) and $data = array(
                'method' => '[' . implode('/', $method) . ']',
                'doMethod' => $method[0],
                'uri' => '/' . ltrim($uri, '/')
            );
            // 获取action指向的方法内的参数
            $data and $action and $data['params'] = $this->getInputParams($action);
            // 获取filter内部所需参数
            isset($data['params']) && is_array($data['params']) && $all_params += $data['params'];
            $data && ($routes_select[] = $data) && isset($todayReq[$data['uri']]) && $todayReqF[$data['uri']] = count($routes_select) - 1;
        }
        
        // 高频度置前
        $res = [];
        foreach ($todayReqF as $k => $v) {
            $add = $routes_select[$v];
            array_unshift($res, $add);
            unset($routes_select[$v]);
        }
        foreach ($routes_select as $k => $v) {
            $res[] = $v;
        }
        unset($routes_select);
        return View::make('localtest.index')->with('route', $res)
            ->with('baseUrls', $baseUrls)
            ->with('all_params', $all_params);
    }

    public function index2(){
        $ApidocAnnGener = new  \App\Services\Adag\ApidocAnnGener();
        $ret = $ApidocAnnGener->run();
        dump($ret);
    }
}















