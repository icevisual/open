<?php
namespace App\Services\Open;

use App\Exceptions\ServiceException;

class MqttServices 
{
    /**
     * 发起 EMQ API 请求
     * @param unknown $url
     *  API 地址
     * @param unknown $params
     *  参数
     * @param unknown $uname
     *  授权用户名
     * @param unknown $upass
     *  授权用户密码
     */
    public static function emqApi($url,$params,$uname,$upass){
        $Authorization = 'Basic '.base64_encode($uname.':'.$upass);
        $opts = [
            CURLOPT_HTTPHEADER => [
                'Authorization:' .$Authorization
            ]
        ];
        $res =  curl_get($url,$params,true,$opts);
        return $res;
    }
    
    /**
     * 查看 EMQ 客户端是否在线
     * @param string|Array $client_keys
     *  支持单个和多个
     * @return boolean
     */
    public static function emqClientsWhetherOnline($client_keys){
        $config = [
            'url' => 'http://192.168.5.61:18083/',
            'uname' => 'admin',
            'upass' => 'public',
        ];
        $config = \Config::get('services.emq');
        $apis = [
            'api/clients',
            'api/subscriptions'
        ];
        $api = 'api/clients';
//         curr_page=1&page_size=100&client_key=Key
        $api_url = rtrim($config['url'],'/') .'/'.ltrim($api,'/');
//         array:5 [
//             "currentPage" => 1
//             "pageSize" => 100
//             "totalNum" => 2
//             "totalPage" => 1
//             "result" => array:2 [
//                 0 => array:8 [
//                     "clientId" => "aaaaaaaaaaaaaaaaaaaa"
//                     "username" => "aaaaaaaaaaaaaaaaaaaa"
//                     "ipaddress" => "192.168.5.13"
//                     "port" => 61770
//                     "clean_sess" => true
//                     "proto_ver" => 4
//                     "keepalive" => 60
//                     "connected_at" => "2016-12-01 14:32:25"
//                 ]
//             ]
//         ]
        $param = [];
        if(is_array($client_keys) ){
            if(count($client_keys) ==  1){
                $param['client_key'] = $client_keys[0];
            }else{
                $param['curr_page'] = 1;
                $param['page_size'] = 100;
            }
        }else{
            $param['client_key'] = $client_keys;
        }
        
        if(isset($param['client_key'])){
            // 查询单个
            try {
                $ret = self::emqApi($api_url, $param, $config['uname'], $config['upass']);
                
                foreach ($ret['result'] as $v){
                    if($param['client_key'] == $v['clientId']){
                        return [$param['client_key'] => true];
                    }
                }
                
//                 if(1 == array_get($ret, 'totalNum')){
//                     return [$param['client_key'] => true];
//                 }
            }catch(\Exception $e){
                throw $e;
            }
            return [$param['client_key'] => false];
        }else{
            // 查询多个
            try {
                $retArray = array_flip($client_keys);
                $searchCount = count($retArray);
                $isLastPage = false;
                $maxAttempts = 100;
                do{
                    $ret = self::emqApi($api_url, $param, $config['uname'], $config['upass']);
                    foreach ($ret['result'] as $v){
                        if(isset($retArray[$v['clientId']])){
                            $retArray[$v['clientId']] = true;
                            $searchCount -- ;
                        }
                    }
                    $totalPage = array_get($ret, 'totalPage');
                    $isLastPage = $param['curr_page'] >= $totalPage;
                    $param['curr_page'] ++;
                    $maxAttempts -- ;
                }while(!($searchCount <= 0 || $isLastPage || !$maxAttempts));// 全部记录已遍历  | 所有结果已得到 
                return $retArray;
            }catch(\Exception $e){
                throw $e;
            }
            return false;
        }
    }
}


