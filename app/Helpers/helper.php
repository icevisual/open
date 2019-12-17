<?php

if (! function_exists('reverseScandir')) {

    function reverseScandir($basePath,$prefix = '',$opts = []){
        $DS = DIRECTORY_SEPARATOR;
        $basePath = trim($basePath,'/\\');
        $scan = scandir($basePath);
        $ret = [];
        $only = false;
        if(!empty($opts)){
            $only = isset($opts['only']) ? $opts['only'] : false;
            $only = array_flip($only);
        }
        for ($i = 2 ; $i < count($scan) ; $i ++){
            $filename = $basePath.$DS.$scan[$i];
            if(is_dir($filename)){
                if(false === $only){
                    $ret = array_merge($ret,reverseScandir($filename,$prefix.$scan[$i].$DS,$opts));
                }else{
                    $pf = $prefix.$scan[$i].$DS;
                    $key1 = str_replace(['\\','/'], '/', trim($pf,'\\/'));
                    $key2 = str_replace(['\\','/'], '\\', trim($pf,'\\/'));
                    if(isset($only[$key1]) || isset($only[$key2])){
                        $ret = array_merge($ret,reverseScandir($filename,$prefix.$scan[$i].$DS,$opts));
                    }
                }
            }else{
                $ret[] = $prefix.$scan[$i];
            }
        }
        return $ret;
    }
}

if (! function_exists('base32_encode')) {
    function base32_encode($str){
        $base32Map = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $len = strlen($str);
        $b32 = '';
        $rest = 0;
        $restLen = 0;
        for($i = 0 ; $i < $len ; $i ++ ){
            $chrCode = ord($str{$i});
            $thisByte = $rest << 8 | $chrCode;
            $thisByteLen = $restLen + 8;
            while($thisByteLen >= 5){
                $b32 .= $base32Map{$thisByte >> ($thisByteLen - 5)};
                $thisByteLen -= 5;
                $thisByte = $thisByte & (pow(2,$thisByteLen) - 1);
            }
            $rest = $thisByte;
            $restLen = $thisByteLen;
        }
        if($restLen > 0){
            $rest = $rest << ( 5 - $restLen);
            $b32 .= $base32Map{$rest};
        }
        return $b32;
    }
}


if (! function_exists('totp_secret_compare')) {

    function totp_secret_compare($secret,$digitSecret)
    {
        return \App\Services\Common\TOTPService::verify_key(base32_encode($secret), $digitSecret);
    }
}

if (! function_exists('totp_secret_encode')) {

    function totp_secret_encode($secret)
    {
        return \App\Services\Common\TOTPService::get_otp(base32_encode($secret));
    }
}

if (! function_exists('is_json')) {

    function is_json($string)
    {
        $json = json_decode($string, 1);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $json;
        }
        return false;
    }
}


if (! function_exists('now')) {

    function now($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }
}


if (! function_exists('error_400')) {

    function error_400($message = '')
    {
        return view('errors.400',['error' => $message]);
    }
}

if (! function_exists('error_404')) {

    function error_404($message = '')
    {
        return view('errors.404');
    }
}

if (! function_exists('getRequestUrl')) {

    /**
     * 获取请求的URL，带HTTP（S）,带参数
     *
     * @param string $path
     *            指定路由
     * @return string
     */
    function getRequestUrl($path = '')
    {
        $protocol = \Request::isSecure() ? 'https://' : 'http://';
        $host = \Request::getHttpHost();
        if ($path) {
            $RequestUri = $path;
        } else {
            $RequestUri = \Request::getRequestUri();
        }
        $redirect_url = $protocol . rtrim($host, "/") . '/' . ltrim($RequestUri, "/");
        return $redirect_url;
    }
}

if (! function_exists('aes')) {

    /**
     * 获取AES实例
     *
     * @return \App\Extensions\Common\AESTool
     */
    function aes()
    {
        static $AES = null;
        if (! $AES) {
            $AES = new \App\Extensions\Common\AESTool();
            $AES->setSecretKey(\Config::get('app.key', 'I am the AES key!'));
        }
        return $AES;
    }

    /**
     * AES加密
     *
     * @param unknown $str            
     * @return \App\Extensions\Common\str
     */
    function aes_encrypt($str)
    {
        return aes()->encrypt($str);
    }

    /**
     * AES解密
     *
     * @param unknown $str            
     * @return Ambigous <string, boolean>
     */
    function aes_decrypt($str)
    {
        return aes()->decrypt($str);
    }
}

if (! function_exists('groupConcatToArray')) {

    /**
     * 解析group_cancat 的字符串为数组
     *
     * @param unknown $value            
     * @param unknown $separator            
     * @return multitype:
     */
    function groupConcatToArray($value, $separator)
    {
        $payrollValueArray = explode($separator, str_replace($separator . ',' . $separator, $separator, $value));
        $payrollValueArray = array_slice($payrollValueArray, 1, count($payrollValueArray) - 2);
        return $payrollValueArray;
    }
}

if (! function_exists('detect_encoding')) {

    /**
     * Detect string encoding
     *
     * @param unknown $content            
     * @return string
     */
    function detect_encoding($content)
    {
        $encode = mb_detect_encoding($content, array(
            "ASCII",
            'UTF-8',
            "GB2312",
            "GBK",
            'BIG5'
        ));
        return $encode;
    }
}

if (! function_exists('setRequiredIfMissedEmpty')) {

    function setRequiredIfMissedEmpty($data, $rule)
    {
        foreach ($rule as $k => $v) {
            if (is_string($v)) {
                $r = explode('|', $v);
            } else {
                $r = $v;
            }
            foreach ($r as $rr) {
                if (strpos($rr, 'required_if') === 0) {
                    $ruleProperties = preg_split('/[,:]/', $rr);
                    if (
                    // !isset($data[$ruleProperties[1]]) ||
                    $data[$ruleProperties[1]] != $ruleProperties[2]) {
                        $data[$k] = null;
                    }
                }
            }
        }
        return $data;
    }
}

if (! function_exists('getChangedProperties')) {

    /**
     * 获取已变更数据
     *
     * @param unknown $newData            
     * @param unknown $oldData            
     * @param unknown $properties            
     * @return <pre> [
     *         'diffKey' => [oldvalue,newvalue]
     *         ]
     *         </pre>
     */
    function getChangedProperties($newData, $oldData, $properties)
    {
        $diffValues = [];
        foreach ($properties as $k => $v) {
            // if(isset($newData[$v]) && isset($oldData[$v])
            if (array_key_exists($v, $newData) && array_key_exists($v, $oldData) && $newData[$v] != $oldData[$v]) {
                $diffValues[$v] = [
                    $oldData[$v],
                    $newData[$v]
                ];
            }
        }
        return $diffValues;
    }
}
if (! function_exists('nameContainNumberAndSpecialChar')) {

    function chineseNameCheck($subject)
    {
        $pattern = '/^[\x{4E00}-\x{9FA5}]{2,5}(?:·[\x{4E00}-\x{9FA5}]{2,5})*$/u';
        return preg_match($pattern, $subject);
    }

    function englishNameCheck($subject)
    {
        $pattern = '/^[\w]+([ ·][\w]+)?$/';
        return preg_match($pattern, $subject);
    }

    function nameContainNumberAndSpecialChar($str)
    {
        // 1. GBK (GB2312/GB18030)
        // x00-xff GBK双字节编码范围
        // x20-x7f ASCII
        // xa1-xff 中文 gb2312
        // x80-xff 中文 gbk
        // 2. UTF-8 (Unicode)
        // u4e00-u9fa5 (中文)
        // x3130-x318F (韩文
        // xAC00-xD7A3 (韩文)
        // u0800-u4e00 (日文)
        $specialAsciiChars = <<<EOF
\~\!\@\#$\%\^\&\\*(\)\\_\+\=\-\[\]\{}\\|\\;\:\'\\"\,\?\/';
EOF;
        $regex = '/[\d\x{ff0c}\x{3002}\x{ff01}\x{ff1f}' . $specialAsciiChars . ']/u';
        if (preg_match_all($regex, $str, $matchs)) {
            return true;
        }
        return false;
    }
}

if (! function_exists('getRangeWidthWords')) {

    function getStrWidth($component)
    {
        return $component['c'] * 2 + $component['e'];
    }

    function getStrComponent($str)
    {
        $desLen = strlen($str);
        $desNum = mb_strlen($str);
        $c_n = ($desLen - $desNum) / 2;
        $e_n = $desNum - $c_n;
        $len = $c_n * 2 + $e_n;
        return [
            'c' => $c_n,
            'e' => $e_n
        ];
    }

    /**
     * 获取 $min ~ $max 个英文宽度的字 （中文占3，英占1）
     *
     * @param unknown $str            
     * @param unknown $min            
     * @param unknown $max            
     */
    function getRangeWidthWords($str, $min, $max)
    {
        $target = preg_replace('/[\r\n\t]/', '', $str);
        $length = $max;
        $prev = $length;
        $i = 0;
        do {
            if ($prev == $length) {
                $length --;
            }
            $target = mb_substr($target, 0, $length);
            $component = getStrComponent($target);
            $width = getStrWidth($component);
            $prev = $length;
            if ($width <= 0) {
                return $target;
            }
            $length = $length * $max / $width;
            $i ++;
            if ($i > 100)
                break;
        } while ($width > $max);
        return $target;
    }
}

if (! function_exists('runCustomValidator')) {

    /**
     * Run Constom Validator
     *
     * @param array $input
     *            <pre>
     *            [
     *            'data', //数据
     *            'rules', //条件
     *            'messages', //错误信息
     *            'attributes',//属性名映射
     *            'valueNames',//属性值映射
     *            'config' =>
     *            <div style="margin:20px;">[
     *            <span style="margin:10px;">'ReturnOrException' => 0, // Return (0) Or Exception(1)</span>
     *            <span style="margin:10px;">'FirstOrAll' => 0 // First(0) Or All(1)</span>
     *            ],</div>
     *            ]
     *            </pre>
     * @throws \App\Exceptions\ServiceException
     * @return multitype:|boolean
     */
    function runCustomValidator(array $input)
    {
        $input = array_only($input, [
            'data',
            'rules',
            'messages',
            'attributes',
            'valueNames',
            'config'
        ]);
        
        $config = isset($input['config']) ? $input['config'] : [];
        // Return (0) Or Exception(1)
        // First(0) Or All(1)
        $defaultConfig = [
            'ReturnOrException' => 1,
            'FirstOrAll' => 0
        ];
        
        $config += $defaultConfig;
        $input = $input + [
            'messages' => [],
            'attributes' => [],
            'valueNames' => [],
            'config' => []
        ];
        $validate = \Validator::make($input['data'], $input['rules'], $input['messages'], $input['attributes']);
        
        if (isset($input['valueNames']) && ! empty($input['valueNames'])) {
            $validate->setValueNames($input['valueNames']);
        }
        
        if ($validate->fails()) {
            $message = $validate->getMessageBag();
            $message->setFormat([
                'key' => ':key',
                'message' => ':message'
            ]);
            $errorMsg = $config['FirstOrAll'] ? $message->all() : $message->first();
            
            if ($config['ReturnOrException']) {
                if ($config['FirstOrAll']) {
                    $errors = [];
                    foreach ($errorMsg as $v) {
                        $errors[$v['key']] = $v['message'];
                    }
                    throw new \App\Exceptions\ValidationException('error', \ErrorCode::VALIDATION_FAILED, $errors);
                } else {
                    throw new \App\Exceptions\ValidationException($errorMsg['message'], \ErrorCode::VALIDATION_FAILED);
                }
            } else {
                return $errorMsg;
            }
        }
        return true;
    }
}

if (! function_exists('runValidator')) {

    /**
     * 执行
     *
     * @param array $data            
     * @param array $rules            
     * @param array $messages            
     * @throws \App\Exceptions\ServiceException
     */
    function runValidator(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validate = \Validator::make($data, $rules, $messages, $customAttributes);
        if ($validate->fails()) {
            $message = $validate->getMessageBag()->first();
            throw new \App\Exceptions\ServiceException($message, 202);
        }
        return true;
    }
}

if (! function_exists('mobileCheck')) {

    /**
     * 检查手机号是否符合规则
     *
     * @param
     *            $mobile
     * @return bool
     */
    function mobileCheck($mobile)
    {
        // 手机号码的正则验证
        // return preg_match("/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/",$phone);
        return (! preg_match("/^(((13[0-9]{1})|(14[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1}))+\d{8})$/", $mobile)) ? false : true;
    }
}

if (! function_exists('createSerialNum')) {

    /*
     * 创建流水号
     */
    function createSerialNum($num = 18)
    {
        list ($usec, $sec) = explode(" ", microtime());
        
        $usec = (int) ($usec * 1000000);
        
        $str = $sec . $usec . mt_rand(100000, 999999);
        
        $str = substr($str, 0, $num);
        
        if (strlen($str) < $num) {
            $str = str_pad($str, $num, mt_rand(100000, 999999));
        }
        
        return $str;
    }
}

if (! function_exists('identityCardCheck')) {

    /**
     * 验证身份证号
     *
     * @param
     *            $vStr
     * @return bool
     */
    function identityCardCheck($vStr)
    {
        $vCity = array(
            '11',
            '12',
            '13',
            '14',
            '15',
            '21',
            '22',
            '23',
            '31',
            '32',
            '33',
            '34',
            '35',
            '36',
            '37',
            '41',
            '42',
            '43',
            '44',
            '45',
            '46',
            '50',
            '51',
            '52',
            '53',
            '54',
            '61',
            '62',
            '63',
            '64',
            '65',
            '71',
            '81',
            '82',
            '91'
        );
        
        if (! preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr))
            return false;
        
        if (! in_array(substr($vStr, 0, 2), $vCity))
            return false;
        
        $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
        $vLength = strlen($vStr);
        
        if ($vLength == 18) {
            $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
        } else {
            $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
        }
        
        if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday)
            return false;
        if ($vLength == 18) {
            $vSum = 0;
            
            for ($i = 17; $i >= 0; $i --) {
                $vSubStr = substr($vStr, 17 - $i, 1);
                $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr, 11));
            }
            
            if ($vSum % 11 != 1)
                return false;
        }
        
        return true;
    }
}

if (! function_exists('randStr')) {

    /**
     * 生成随机字符串
     *
     * @param unknown_type $len
     *            长度
     * @param unknown_type $format
     *            内容类别，ALL,CHAR,NUMBER
     */
    function randStr($len = 6, $format = 'NUMBER')
    {
        switch ($format) {
            case 'ALL':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@~';
                break;
            case 'CHAR':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-@~';
                break;
            case 'NUMBER':
                $chars = '0123456789';
                break;
            default:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
        }
        // mt_srand ( ( double ) microtime () * 1000000 * getmypid () );
        $password = "";
        while (strlen($password) < $len)
            $password .= substr($chars, (mt_rand() % strlen($chars)), 1);
        return $password;
    }
}

if (! function_exists('toFix')) {

    /**
     * 生成随机字符串
     *
     * @param unknown_type $len
     *            长度
     * @param unknown_type $format
     *            内容类别，ALL,CHAR,NUMBER
     */
    function toFix($val, $precision = 2)
    {
        return sprintf('%.' . $precision . 'f', $val); // round($val,$precision);
    }
}

if (! function_exists('groupInsert')) {

    /**
     * Insert A Group Of Values
     *
     * @param unknown $k            
     * @param unknown $v            
     */
    function fireInsert($k, $v)
    {
        $fields = implode('`,`', array_keys($v[0]));
        $sql = 'insert into `' . $k . '`(`' . $fields . '`)values';
        foreach ($v as $obj) {
            $values[] = '(\'' . implode('\',\'', array_values($obj)) . '\')';
        }
        $sql .= implode(',', $values);
        \DB::insert($sql);
    }

    /**
     * Group Insert
     *
     * @param string $tbname            
     * @param unknown $inputDate            
     */
    function groupInsert($tbname = '', $inputDate = [])
    {
        static $_queue = [];
        if ($tbname == '[fire]') {
            fireInsert($k, $v);
            $_queue = [];
        } else 
            if (substr_replace($tbname, '', 1, strlen($tbname) - 2) == '[]') {
                $tbname = substr($tbname, 1, strlen($tbname) - 2);
                fireInsert($tbname, $_queue[$tbname]);
                unset($_queue[$tbname]);
            } else {
                $_queue[$tbname][] = $inputDate;
                if (count($_queue[$tbname]) > 8000) {
                    groupInsert('[' . $tbname . ']');
                }
            }
    }
}

if (! function_exists('mt_mark')) {

    /**
     * Calculates the Memory & Time difference between two marked points.
     *
     * @param unknown $point1            
     * @param string $point2            
     * @param number $decimals            
     * @return string|multitype:NULL
     */
    function mt_mark($point1 = '', $point2 = '', $unit = 'KB', $decimals = 4)
    {
        static $marker = [];
        
        $units = [
            'B' => 1,
            'KB' => 1024,
            'MB' => 1048576,
            'GB' => 1073741824
        ];
        $unit = isset($units[$unit]) ? $unit : 'KB';
        if ($point2 && $point1) {
            // 取件间隔
            if (! isset($marker[$point1]))
                return false;
            if (! isset($marker[$point2])) {
                $marker[$point2] = [
                    'm' => memory_get_usage(),
                    't' => microtime()
                ];
            }
            
            list ($sm, $ss) = explode(' ', $marker[$point1]['t']);
            list ($em, $es) = explode(' ', $marker[$point2]['t']);
            
            return [
                't' => number_format(($em + $es) - ($sm + $ss), $decimals),
                'm' => number_format(($marker[$point2]['m'] - $marker[$point1]['m']) / $units[$unit], $decimals)
            ];
        } else 
            if ($point1) {
                // 设记录点
                if ($point1 == '[clear]') {
                    $marker = [];
                } else {
                    $marker[$point1] = [
                        'm' => memory_get_usage(),
                        't' => microtime()
                    ];
                }
            } else {
                // 返回所有
                return $marker;
            }
    }
}

if (! function_exists('funcCache')) {

    /**
     * Cache function result and reflush every 5 second
     * Send a commend and unset the selected key
     *
     * @param unknown $func            
     * @param unknown $params            
     * @return mixed
     */
    function funcCache($func, $params = [], $expire = 5)
    {
        // TODO : Release expired keys
        static $_cached = [];
        $key = sha1(json_encode([
            $func,
            $params
        ]));
        if ($expire == '[clear]') {
            unset($_cached[$key]);
            return;
        }
        $time = time();
        if (isset($_cached[$key]) && $_cached[$key]['expire'] > $time) {
            $result = $_cached[$key]['result'];
        } else {
            $result = call_user_func_array($func, $params);
            $_cached[$key] = [
                'result' => $result,
                'expire' => $time + $expire
            ];
        }
        return $result;
    }
}

if (! function_exists('__fsocket')) {

    function __async_curl($url, array $data = [], $host = '', $method = 'GET')
    {
        $host = $host ? $host : $_SERVER['HTTP_HOST'];
        $ch = curl_init();
        $url = $data ? $host . $url . '?' . http_build_query($data) : $host . $url;
        $curl_opt = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 1
        );
        curl_setopt_array($ch, $curl_opt);
        $res = curl_exec($ch);
        curl_close($ch);
        return true;
    }

    function __fsocket_get($url, array $param = [], $host = '')
    {
        return __fsocket($url, $param, $host, 'GET');
    }

    function __fsocket_post($url, array $param = [], $host = '')
    {
        return __fsocket($url, $param, $host, 'POST');
    }

    function __fsocket($url, array $param = [], $host = '', $method = 'POST')
    {
        $host = $host ? $host : $_SERVER['HTTP_HOST'];
        $fp = fsockopen($host, '80', $errno, $errstr, 30);
        $data = http_build_query($param);
        if ($method == 'POST') {
            $out = "POST ${url} HTTP/1.1\r\n";
            $out .= "Host:${host}\r\n";
            $out .= "Content-type:application/x-www-form-urlencoded\r\n";
            $out .= "Content-length:" . strlen($data) . "\r\n";
            $out .= "Connection:close\r\n\r\n";
            $out .= "${data}";
        } else {
            $url = $data ? $url . '?' . $data : $url;
            $out = "GET $url HTTP/1.1\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Connection: Close\r\n\r\n";
        }
        stream_set_blocking($fp, 0); // 开启了手册上说的非阻塞模式
                                     // stream_set_timeout($fsp,1);//设置超时
        fwrite($fp, $out);
        // $row = fread($fp, 4096);
        
        // while (!feof($fp)) {
        // echo fgets($fp, 128);
        // }
        
        usleep(1000);
        fclose($fp);
    }
}

if (! function_exists('chineseWord')) {

    function firstName()
    {
        $str = '赵	钱	孙	李	周	吴	郑	王	冯	陈	褚	卫	蒋	沈	韩	杨	朱	秦	尤	许
何	吕	施	张	孔	曹	严	华	金	魏	陶	姜	戚	谢	邹	喻	柏	水	窦	章
云	苏	潘	葛	奚	范	彭	郎	鲁	韦	昌	马	苗	凤	花	方	俞	任	袁	柳
酆	鲍	史	唐	费	廉	岑	薛	雷	贺	倪	汤	滕	殷	罗	毕	郝	邬	安	常
乐	于	时	傅	皮	卞	齐	康	伍	余	元	卜	顾	孟	平	黄	和	穆	萧	尹
姚	邵	湛	汪	祁	毛	禹	狄	米	贝	明	臧	计	伏	成	戴	谈	宋	茅	庞
熊	纪	舒	屈	项	祝	董	粱	杜	阮	蓝	闵	席	季	麻	强	贾	路	娄	危
江	童	颜	郭	梅	盛	林	刁	钟	徐	邱	骆	高	夏	蔡	田	樊	胡	凌	霍
虞	万	支	柯	昝	管	卢	莫	经	房	裘	缪	干	解	应	宗	丁	宣	贲	邓
郁	单	杭	洪	包	诸	左	石	崔	吉	钮	龚	程	嵇	邢	滑	裴	陆	荣	翁
荀	羊	於	惠	甄	麴	家	封	芮	羿	储	靳	汲	邴	糜	松	井	段	富	巫
乌	焦	巴	弓	牧	隗	山	谷	车	侯	宓	蓬	全	郗	班	仰	秋	仲	伊	宫
宁	仇	栾	暴	甘	钭	厉	戎	祖	武	符	刘	景	詹	束	龙	叶	幸	司	韶
郜	黎	蓟	薄	印	宿	白	怀	蒲	邰	从	鄂	索	咸	籍	赖	卓	蔺	屠	蒙
池	乔	阴	欎	胥	能	苍	双	闻	莘	党	翟	谭	贡	劳	逄	姬	申	扶	堵
冉	宰	郦	雍	舄	璩	桑	桂	濮	牛	寿	通	边	扈	燕	冀	郏	浦	尚	农
温	别	庄	晏	柴	瞿	阎	充	慕	连	茹	习	宦	艾	鱼	容	向	古	易	慎
戈	廖	庾	终	暨	居	衡	步	都	耿	满	弘	匡	国	文	寇	广	禄	阙	东
殴	殳	沃	利	蔚	越	夔	隆	师	巩	厍	聂	晁	勾	敖	融	冷	訾	辛	阚
那	简	饶	空	曾	毋	沙	乜	养	鞠	须	丰	巢	关	蒯	相	查	後	荆	红
游	竺	权	逯	盖	益	桓	公	万俟	司马	上官	欧阳	夏侯	诸葛
闻人	东方	赫连	皇甫	尉迟	公羊	澹台	公冶	宗政	濮阳
淳于	单于	太叔	申屠	公孙	仲孙	轩辕	令狐	钟离	宇文
长孙	慕容	鲜于	闾丘	司徒	司空	亓官	司寇	仉	督	子车
颛孙	端木	巫马	公西	漆雕	乐正	壤驷	公良	拓跋	夹谷
宰父	谷梁	晋	楚	闫	法	汝	鄢	涂	钦	段干	百里	东郭	南门
呼延	归	海	羊舌	微生	岳	帅	缑	亢	况	后	有	琴	梁丘	左丘
东门	西门	商	牟	佘	佴	伯	赏	南宫	墨	哈	谯	笪	年	爱	阳	佟
第五	言	福	';
        return preg_split('/\s+/', $str);
    }

    /**
     * Get chinese word resource
     *
     * @return multitype:string
     */
    function chineseWord()
    {
        $str = '苗疆素来以蛊毒瘴气闻名多鬼狐精怪之事而其核心地十万大山更是神秘无比人迹罕至处古树高耸老藤如龙岳巍峨河流壮阔一派蛮荒的风貌深座脚下此时阵异歌声飘荡出王叫我巡喽完南北吆仿佛踏行道上现了个獐头鼠脑干瘦少年欢快唱着那谣眼珠子滴溜旋转给种极度明感觉令骇然他并非徒步胯有纯黑色皮毛豹副蔫样垂丧驮赶路许这扰兴致止住恶狠俯瞰身你懒散货前方便最后要查寨刻钟看到门否则会禀报想烧烤只幽灵墨趣呢两字似乎某魔力原本进浑忍不颤抖眸顿显惶恐态形纵已化作残影消失在留连串咒骂回虚空畜生慢点家铁柱爷掉足遍布盆内四平八稳端坐整理皱巴衣衫才倨傲喝葛些滚早等候伴随嘎吱打开中鱼贯群为首乃袍肥胖者毫犹豫率领众跪伏讨好九天青羽主拜见使祝敌岁咧嘴笑莫废话月们诸帮提供贡品可曾准备充切妥当请放小意您务必纳恭敬拿物双手奉睛亮脸露满伸取将入怀安得伙果亏待定面说几句言诚模很激动真太客数直混乱寇飞贼都啊若统实施仁慈政策何能走劳酬没间马屁腿夹再速踪确所尽闭塞始仅活也盘踞强盗却因发改变自幼被养育屠戮带妹侥幸逃凭借与狡诈辣性格女孩加日过刀肉般虽堪称胎食牛穷久展计做成立雄略断扩张杀股匪死五囊知晓奇术妖管什么邪竟奈震撼情鼎凶响彻近千碧祖二暴虐代表瑶善良她劝皆祥和需隔腹片澄净清澈湖泊畔壁株松苍翠条虬绿草茵尊香巨型铜三耳符文膝富正屈指弹缕火焰尖涌汇聚于悸热浪严丝合缝掩盖依旧扑鼻让孔舒通体服远站滔息望威猛霸别赫沉吟摆谱音刚落视跑谄媚潮汹念经传颂功德采对崇江水绵绝穿挪硕骚包抹额滋润分颜悦交办吩咐敢阿谀揖答案错起观烹煮美味吧七炉药金狮期嘿希突破瓶颈饕餮吞噬段引轩波存横法惊沦愿偿口又欲仙爽坏哥应今陪儿去玉菇怎崖就脆寒恨淡紫织锦腰束盈握肢乌秀编俏辫插枚桃花簪雪巧虹鞋皓白腕挂银圈尴尬乖顺像羊缩脖弱根据猜测关重底哼琼饶郁闷差哭哀怨怕宠溺噗嗤轻吐兰啦次算例听痛涕暖鸟由轰隆雷蒸腾霞光瑞彩澎湃紧跟氤氲赤雾冲同黄铸慑鬓耀爆炸冰冷宛祇降怒吼哈象丹袖掀璀璨燃挥抓摄呈终炼制浓汁淌简骨酥麻浩瀚粹量升脱换蜕越诞伦元相己枷锁谁隐晦闪烁陷暗锅倒扣振聋聩霆蜿蜒际电籍记载劫还罚呼瘫软茫渺沧海粟玩';
        $chineseWord = [];
        for ($i = 0; $i < mb_strlen($str); $i ++) {
            $sub = mb_substr($str, $i, 1);
            $chineseWord[] = $sub;
        }
        return $chineseWord;
    }

    /**
     * Generate random chinese name
     *
     * @return string
     */
    function randomChineseName($n = 3)
    {
        $firstname = firstName();
        $word = chineseWord();
        $count = count($word);
        $str = '';
        while (-- $n) {
            $str .= $word[random_int(0, $count - 1)];
        }
        return $firstname[array_rand($firstname)] . $str;
    }

    /**
     * Generate random phone number
     *
     * @return string
     */
    function randomPhone()
    {
        // /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}
        $data = [
            '13',
            '15',
            '17',
            '18',
            '14'
        ];
        
        $h = $data[random_int(0, 4)];
        $t = random_int(100000000, 999999999);
        return $h . $t;
    }

    /**
     * Get Chinese words in a given string
     *
     * @param unknown $str            
     * @return multitype:
     */
    function chineseWordGenerate($str)
    {
        $punctuation = [
            '，',
            '”',
            '.',
            '！',
            '。'
        ];
        $chineseWord = [];
        for ($i = 0; $i < mb_strlen($str); $i ++) {
            $sub = mb_substr($str, $i, 1);
            if (preg_match("/[\x7f-\xff]/", $sub) && ! in_array($sub, $punctuation)) {
                $chineseWord[] = $sub;
            }
        }
        $chineseWord = array_unique($chineseWord);
        return $chineseWord;
    }
}

if (! function_exists('invokeMethod')) {

    function getInvokeMethodArray($class, $method)
    {
        $ReflectionMethod = new ReflectionMethod($class, $method);
        if ($ReflectionMethod->isStatic()) {
            return [
                $class,
                $method
            ];
        }
        return [
            new $class(),
            $method
        ];
    }

    function invokeMethod($class, $method, array $param_arr = [])
    {
        $callback = getInvokeMethodArray($class, $method);
        return call_user_func_array($callback, $param_arr);
    }
}

if (! function_exists('divide_equally')) {

    function divide_equally($price, $period)
    {
        $each = bcmul($price / $period, 1, 2);
        $result = array_fill(0, $period, floatval($each));
        if ($period > 1) {
            $result[$period - 1] = $price - $each * ($period - 1);
        }
        return $result;
    }
}

if (! function_exists('echoArray')) {

    function echoArray(array $arr)
    {
        echo '[';
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                echo ',';
                echoArray($v);
            } else {
                if ($k > 0) {
                    echo ',';
                }
                echo $v;
            }
        }
        echo ']';
    }

    /**
     *
     * @param array $arr            
     */
    function echoArrayKV(array $arr, $lv = 1, $paddingLeft = "\t")
    {
        echo '[' . PHP_EOL;
        $padding = str_pad('', $lv, $paddingLeft);
        $padding1 = str_pad('', $lv - 1, $paddingLeft);
        foreach ($arr as $k => $v) {
            echo "$padding'$k' => ";
            if (is_array($v)) {
                echoArrayKV($v, $lv + 1);
            } else {
                echo "'$v'," . PHP_EOL;
            }
        }
        if ($lv == 1) {
            echo $padding1 . '];' . PHP_EOL;
        } else {
            echo $padding1 . '],' . PHP_EOL;
        }
    }

    /**
     * 输出php语法的数组
     *
     * @param array $arr            
     */
    function preArrayKV(array $arr, $lv = 1, $paddingLeft = "\t")
    {
        echo '<pre>';
        echoArrayKV($arr, $lv, $paddingLeft);
        echo '</pre>';
    }

    function getOnlineIp()
    {
        $OnlineIp = \LRedis::GET('OnlineIp');
        if (! $OnlineIp) {
            $url = 'http://city.ip138.com/ip2city.asp';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 8);
            $send_result = curl_exec($ch);
            if ($send_result === false) {
                throw new \Exception("REQ[$url]" . curl_error($ch), curl_errno($ch) + 60000);
            }
            preg_match('/\[(.*)\]/', $send_result, $ip);
            $OnlineIp = $ip[1];
            \LRedis::SETEX('OnlineIp', 6000, $OnlineIp);
        }
        return $OnlineIp;
    }
}

/**
 * $str 原始中文字符串
 * $encoding 原始字符串的编码，默认GBK
 * $prefix 编码后的前缀，默认"&#"
 * $postfix 编码后的后缀，默认";"
 */
function unicode_encode($str, $encoding = 'GBK', $prefix = '&#', $postfix = ';')
{
    $str = iconv($encoding, 'UCS-2', $str);
    $arrstr = str_split($str, 2);
    $unistr = '';
    for ($i = 0, $len = count($arrstr); $i < $len; $i ++) {
        $dec = hexdec(bin2hex($arrstr[$i]));
        $unistr .= $prefix . $dec . $postfix;
    }
    return $unistr;
}

/**
 * $str Unicode编码后的字符串
 * $decoding 原始字符串的编码，默认GBK
 * $prefix 编码字符串的前缀，默认"&#"
 * $postfix 编码字符串的后缀，默认";"
 */
function unicode_decode($unistr, $encoding = 'GBK', $prefix = '&#', $postfix = ';')
{
    $arruni = explode($prefix, $unistr);
    $unistr = '';
    for ($i = 1, $len = count($arruni); $i < $len; $i ++) {
        if (strlen($postfix) > 0) {
            $arruni[$i] = substr($arruni[$i], 0, strlen($arruni[$i]) - strlen($postfix));
        }
        $temp = intval($arruni[$i]);
        $unistr .= ($temp < 256) ? chr(0) . chr($temp) : chr($temp / 256) . chr($temp % 256);
    }
    return iconv('UCS-2', $encoding, $unistr);
}

if (! function_exists('getReturnInLogFile')) {

    /**
     * Applies the callback to the elements of the given arrays
     *
     * @link http://www.php.net/manual/en/function.array-map.php
     * @param
     *            callback callable <p>
     *            Callback function to run for each element in each array.
     *            </p>
     * @param
     *            _ array[optional]
     * @return array an array containing all the elements of array1
     *         after applying the callback function to each one.
     */
    function array_map_recursive($callback, array $array1)
    {
        return array_map(function ($v) use($callback) {
            if (is_array($v)) {
                return array_map_recursive($callback, $v);
            } else {
                return call_user_func_array($callback, array(
                    $v
                ));
            }
        }, $array1);
    }

    /**
     * 减除过长连续数组
     *
     * @param array $array1            
     * @return multitype:|multitype:Ambigous <> Ambigous <Ambigous <>>
     */
    function array_clear(array $array1, $limit = 5)
    {
        return array_map(function ($v) use($limit) {
            if (is_array($v)) {
                if (count($v) > $limit) {
                    $keyys = array_keys($v);
                    if (isset($keyys[$limit]) && $keyys[$limit] == $limit) {
                        $v = [
                            $v[0],
                            $v[1]
                        ];
                    }
                }
                // $v = array_filter($v);
                return array_clear($v);
            } else {
                return $v; // call_user_func_array($callback, array($v));
            }
        }, $array1);
    }

    /**
     * Decode Json String recursively
     */
    function json_decode_recursive($ret)
    {
        return array_map_recursive(function ($rt) {
            if (strpos($rt, '[object]') === 0) {
                preg_match('/\{.*\}/', $rt, $mt);
                if ($mt) {
                    $mtr = json_decode(($mt[0]), true);
                    if (json_last_error() == JSON_ERROR_NONE) {
                        return json_decode_recursive($mtr);
                    }
                }
            }
            $len = strlen($rt);
            if ($len && $rt{0} == '{' && $rt{$len - 1} == '}') {
                $mt = json_decode($rt, true);
                if (json_last_error() == JSON_ERROR_NONE) {
                    return json_decode_recursive($mt);
                }
            }
            return $rt;
        }, $ret);
    }
}

if (! function_exists('mark')) {

    /**
     * Calculates the time difference between two marked points.
     *
     * @param unknown $point1            
     * @param string $point2            
     * @param number $decimals            
     * @return string|multitype:NULL
     */
    function mark($point1, $point2 = '', $decimals = 4)
    {
        static $marker = [];
        
        if ($point2 && $point1) {
            if (! isset($marker[$point1]))
                return false;
            if (! isset($marker[$point2])) {
                $marker[$point2] = microtime();
            }
            
            list ($sm, $ss) = explode(' ', $marker[$point1]);
            list ($em, $es) = explode(' ', $marker[$point2]);
            
            return number_format(($em + $es) - ($sm + $ss), $decimals);
        } else 
            if ($point1) {
                if ($point1 == '[clear]') {
                    $marker = [];
                } else {
                    $marker[$point1] = microtime();
                }
            } else {
                return $marker;
            }
    }

    /**
     * Calculates the Memory difference between two marked points.
     *
     * @param unknown $point1            
     * @param string $point2            
     * @param number $decimals            
     * @return string|multitype:NULL
     */
    function memory_mark($point1 = '', $point2 = '', $unit = 'KB', $decimals = 2)
    {
        static $marker = [];
        
        $units = [
            'B' => 1,
            'KB' => 1024,
            'MB' => 1048576,
            'GB' => 1073741824
        ];
        $unit = isset($units[$unit]) ? $unit : 'KB';
        if ($point2 && $point1) {
            // 取件间隔
            if (! isset($marker[$point1]))
                return false;
            if (! isset($marker[$point2])) {
                $marker[$point2] = memory_get_usage();
            }
            
            return number_format(($marker[$point2] - $marker[$point1]) / $units[$unit], $decimals); // .' '.$unit;
        } else 
            if ($point1) {
                // 设记录点
                if ($point1 == '[clear]') {
                    $marker = [];
                } else {
                    $marker[$point1] = memory_get_usage();
                }
            } else {
                // 返回所有
                return $marker;
            }
    }
    
    if (! function_exists('mt_mark')) {

        /**
         * Calculates the Memory & Time difference between two marked points.
         *
         * @param unknown $point1            
         * @param string $point2            
         * @param number $decimals            
         * @return string|multitype:NULL
         */
        function mt_mark($point1 = '', $point2 = '', $unit = 'KB', $decimals = 4)
        {
            static $marker = [];
            
            $units = [
                'B' => 1,
                'KB' => 1024,
                'MB' => 1048576,
                'GB' => 1073741824
            ];
            $unit = isset($units[$unit]) ? $unit : 'KB';
            if ($point2 && $point1) {
                // 取件间隔
                if (! isset($marker[$point1]))
                    return false;
                if (! isset($marker[$point2])) {
                    $marker[$point2] = [
                        'm' => memory_get_usage(),
                        't' => microtime()
                    ];
                }
                
                list ($sm, $ss) = explode(' ', $marker[$point1]['t']);
                list ($em, $es) = explode(' ', $marker[$point2]['t']);
                
                return [
                    't' => number_format(($em + $es) - ($sm + $ss), $decimals),
                    'm' => number_format(($marker[$point2]['m'] - $marker[$point1]['m']) / $units[$unit], $decimals)
                ];
            } else 
                if ($point1) {
                    // 设记录点
                    if ($point1 == '[clear]') {
                        $marker = [];
                    } else {
                        $marker[$point1] = [
                            'm' => memory_get_usage(),
                            't' => microtime()
                        ];
                    }
                } else {
                    // 返回所有
                    return $marker;
                }
        }
    }

    function dmt_mark($point1 = '', $point2 = '', $unit = 'MB', $decimals = 4)
    {
        redline($point1 . ' - ' . $point2);
        $res = mt_mark($point1, $point2, $unit, $decimals);
        dump($res);
    }

    /**
     *
     * @param array $xAxis
     *            ['categories' => range(1,20,1)]
     * @param array $series
     *            ['name' => '','data' =>[]]
     * @param string $yAxis_title            
     * @param string $title            
     * @param string $subtitle            
     * @return \Illuminate\View\$this
     */
    function chart(array $xAxis, array $series, $title = 'title', $subtitle = 'subtitle', $yAxis_title = 'yAxis_title')
    {
        $chartData = [
            'title' => $title,
            'subtitle' => $subtitle,
            'xAxis' => json_encode($xAxis),
            'yAxis_title' => $yAxis_title,
            'series' => json_encode($series)
        ];
        return \View::make('localtest.chart')->with('chartData', $chartData);
    }

    function statisticsExecTime($func, array $params, $xAxis)
    {
        set_time_limit(170);
        $func_name = '';
        if (is_array($func)) {
            if (! method_exists($func[0], $func[1])) {
                return false;
            }
            $func_name = object_name($func[0]) . '->' . $func[1];
        } else 
            if (is_string($func)) {
                if (! function_exists($func)) {
                    return false;
                }
                $func_name = $func;
            } else 
                if (is_callable($func)) {
                    // if(! function_exists($func)){
                    // return false;
                    // }
                    $func_name = 'Closure';
                } else {
                    return false;
                }
        
        $mem = [];
        $time = [];
        foreach ($params as $v) {
            mark('start');
            
            $result = call_user_func_array($func, (array) $v);
            
            $time[] = floatval(mark('start', 'end'));
            $memory = memory_mark();
            if (isset($memory['start']) && isset($memory['end'])) {
                $mem[] = floatval(memory_mark('start', 'end'));
            }
            mark('[clear]');
            memory_mark('[clear]');
        }
        $data = [
            [
                'name' => 'Exec Time',
                'data' => $time
            ]
        ];
        $mem && $data[] = [
            'name' => 'Exec Memory',
            'data' => $mem
        ];
        $xAxis = [
            'categories' => $xAxis
        ];
        return chart($xAxis, $data, 'Function [' . htmlentities($func_name) . '] Execute Time Statistics', 'At ' . date('Y-m-d H:i:s'), 'Number');
    }
}

if (! function_exists('curl')) {

    function httpDownloadSha1($url, $filePath = "Download", $timeout = 60)
    {
        $pathinfo = pathinfo($url);
        $originFilename = $pathinfo['filename'];
        $originExtension = $pathinfo['extension'];
        $extension = '';
        $acceptExtensions = [
            'png',
            'jpg',
            'gif',
            'jpeg'
        ];
        $acceptExtensions = array_flip($acceptExtensions);
        if (! isset($acceptExtensions[$extension = $originExtension])) {
            if (! isset($acceptExtensions[$extension = substr($originExtension, 0, 3)])) {
                if (! isset($acceptExtensions[$extension = substr($originExtension, 0, 4)])) {
                    throw new \Exception('不支持扩展名');
                    return false;
                }
            }
        }
        
        ! is_dir($filePath) && @mkdir($filePath, 0755, true);
        $url = str_replace(" ", "%20", $url);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $User_Agen = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36';
        curl_setopt($ch, CURLOPT_USERAGENT, $User_Agen); // 用户访问代理 User-Agent
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 跟踪301
        $temp = curl_exec($ch);
        if (! curl_error($ch)) {
            if ($temp{0} == '<') {
                return false;
                throw new \Exception('返回HTML');
            }
            curl_close($ch);
            $sha1 = sha1(base64_encode($temp));
            $fileName = $filePath . '/' . $sha1 . '.' . $extension;
            if (! is_file($fileName) && @file_put_contents($fileName, $temp)) {
                return $fileName;
            } else {
                throw new \Exception('文件写入失败');
                return false;
            }
        } else {
            edump(curl_errno($ch));
            curl_close($ch);
            return false;
        }
    }

    function httpcopy($url, $file = "", $timeout = 60)
    {
        $file = empty($file) ? pathinfo($url, PATHINFO_BASENAME) : $file;
        $dir = pathinfo($file, PATHINFO_DIRNAME);
        ! is_dir($dir) && @mkdir($dir, 0755, true);
        $url = str_replace(" ", "%20", $url);
        
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            $User_Agen = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36';
            curl_setopt($ch, CURLOPT_USERAGENT, $User_Agen); // 用户访问代理 User-Agent
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 跟踪301
            $temp = curl_exec($ch);
            
            if (! curl_error($ch)) {
                if ($temp{0} == '<') {
                    return false;
                }
                if (@file_put_contents($file, $temp)) {
                    return $file;
                } else {
                    return false;
                }
            }
        } else {
            $opts = array(
                "http" => array(
                    "method" => "GET",
                    "header" => "",
                    "timeout" => $timeout
                )
            );
            $context = stream_context_create($opts);
            if (@copy($url, $file, $context)) {
                // $http_response_header
                return $file;
            } else {
                return false;
            }
        }
    }

    
    /**
     * 
     * @param unknown $url
     * @param array $data
     * @param string $json
     * @param array $config
     */
    function curl_get($url, array $data = [], $json = true, array $config = [])
    {
        // $api = 'http://v.showji.com/Locating/showji.com20150416273007.aspx?output=json&m='.$phone;
        $ch = curl_init();
        if (! empty($data)) {
            $url = $url . '?' . http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $User_Agen = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36';
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 设置超时
                                              // curl_setopt($ch, CURLOPT_USERAGENT, $User_Agen); //用户访问代理 User-Agent
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 跟踪301
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 返回结果
        $config && curl_setopt_array($ch, $config);
        $result = curl_exec($ch);
        if(false === $result){
            throw new \Exception(curl_error($ch),curl_errno($ch));
        }
        curl_close($ch);
        if($json && false !== ($ret = is_json($result))){
            return $ret;
        }
        return $result;
    }

    function curl_post($url, array $data, $json = true)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); // url
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $User_Agen = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36';
        curl_setopt($ch, CURLOPT_USERAGENT, $User_Agen);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // 数据
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $info = curl_exec($ch);
        curl_close($ch);
        return $json ? json_decode($info, 1) : $info;
    }

    function curl_multi_request($query_arr, $data, $method = 'POST')
    {
        $ch = curl_multi_init();
        $count = count($query_arr);
        $ch_arr = array();
        for ($i = 0; $i < $count; $i ++) {
            $query_string = $query_arr[$i];
            $ch_arr[$i] = curl_init($query_string);
            curl_setopt($ch_arr[$i], CURLOPT_RETURNTRANSFER, true);
            
            curl_setopt($ch_arr[$i], CURLOPT_POST, 1);
            curl_setopt($ch_arr[$i], CURLOPT_POSTFIELDS, $data); // post 提交方式
            
            curl_multi_add_handle($ch, $ch_arr[$i]);
        }
        $running = null;
        do {
            curl_multi_exec($ch, $running);
        } while ($running > 0);
        for ($i = 0; $i < $count; $i ++) {
            $results[$i] = curl_multi_getcontent($ch_arr[$i]);
            curl_multi_remove_handle($ch, $ch_arr[$i]);
        }
        curl_multi_close($ch);
        return $results;
    }
}

if (! function_exists('randStr')) {

    function randStr($len = 6, $format = 'NUMBER')
    {
        switch ($format) {
            case 'ALL':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~';
                break;
            case 'CHAR':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-@#~';
                break;
            case 'NUMBER':
                $chars = '0123456789';
                break;
            default:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
        }
        // mt_srand ( ( double ) microtime () * 1000000 * getmypid () );
        $password = "";
        while (strlen($password) < $len)
            $password .= substr($chars, (mt_rand() % strlen($chars)), 1);
        return $password;
    }
}
if (! function_exists('edump')) {

    /**
     * Dump And Exit
     *
     * @param mix $var            
     * @param string $echo            
     * @param string $label            
     * @param string $strict            
     */
    function edump()
    {
        call_user_func_array('dump', func_get_args());
        exit();
    }

    function edumpLastSql()
    {
        edump(lastSql());
    }

    function dumpLastSql()
    {
        dump(lastSql());
    }
}

if (! function_exists('sql')) {

    /**
     * Echo An Sql Statment Friendly
     *
     * @param string $subject
     *            Sql Statment
     * @param array $binds
     *            The Bind Params
     * @return unknown
     */
    function sql($subject, array $binds = [], $spe = '<br/>')
    {
        $pattern = '/(select\s+|from\s+|where\s+|and\s+|or\s+|\s+limit|,|(?:left|right|inner)\s+join)/i';
        
        $var = preg_replace($pattern, $spe . '\\1', $subject);
        
        $i = 0;
        
        $binds && $var = preg_replace_callback('/\?/', function ($matchs) use(&$i, $binds) {
            return '\'' . $binds[$i ++] . '\'';
        }, $var);
        
        echo $var . $spe;
    }

    /**
     * Echo Last Sql
     */
    function sqlLastSql()
    {
        $query = lastSql();
        sql($query['query'], $query['bindings']);
    }

    /**
     * Echo Last Sql And Exit
     */
    function esqlLastSql($spe = '<br/>')
    {
        $query = lastSql();
        sql($query['query'], $query['bindings'], $spe);
        exit();
    }
}

if (! function_exists('object_name')) {

    /**
     * 获取对象的类名
     *
     * @param unknown $name            
     */
    function object_name($name)
    {
        return (new \ReflectionObject($name))->getFileName();
    }

    /**
     * Dump The Class Name Of An Given Object
     *
     * @param String $obj
     *            The Given Object
     */
    function dump_object_name($obj)
    {
        dump(object_name($obj));
    }

    function edump_object_name($obj)
    {
        edump(object_name($obj));
    }

    /**
     * 获取文件指定行的内容
     *
     * @param string $filename
     *            文件名
     * @param integer $start
     *            开始行>=1
     * @param integer $offset
     *            偏移量
     * @return array 所请求行的数组
     */
    function getRows($filename, $start, $offset = 0)
    {
        $rows = file($filename);
        $rowsNum = count($rows);
        if ($offset == 0 || (($start + $offset) > $rowsNum)) {
            $offset = $rowsNum - $start;
        }
        $fileList = array();
        for ($i = $start; $max = $start + $offset, $i < $max; $i ++) {
            $fileList[] = $rows[$i]; // substr($rows[$i], 0, - 1);// 为了去掉\r\n
        }
        return $fileList;
    }

    /**
     * Get The Anntation Array Of Given Function
     *
     * @param unknown $function            
     * @return boolean|multitype:multitype:multitype:string <pre>
     *         $data = [
     *         '@return' => [
     *              'name' => '',
     *              'type' => '',
     *              'note' => ''
     *         ],
     *         '@param' => [
     *              'name' => '',
     *              'type' => '',
     *              'note' => ''
     *         ],
     *         'function' => [
     *         'note' => ''
     *         ],
     *         ];
     *         </pre>
     */
    function getAnnotation($function)
    {
        $reflect = getFunctionReflection($function);
        if ($reflect === false)
            return false;
        $start = $reflect->getStartLine() - 1;
        $end = $reflect->getEndLine();
        $file = $reflect->getFileName();
        $offset = $end - $start;
        $rows = file($file);
        $rowsNum = count($rows);
        $annotation = [];
        $i = $start - 1;
        
        while (($ann = trim($rows[$i --])) && (strpos($ann, '//') === 0 || strpos($ann, '*') === 0 || strpos($ann, '/*') === 0)) {
            ($ann = trim($ann, "/* \t")) && $annotation[] = $ann;
        }
        $annData = [];
        $tmp = [];
        foreach ($annotation as $value) {
            if (stripos($value, '@') === 0) {
                // TODO::Process @Return
                $exp = explode(' ', $value);
                $count = count($exp);
                $attr = [];
                if ($count == 2) {
                    $attr = [
                        'type' => $exp[1]
                    ];
                } else 
                    if ($count >= 3) {
                        $attr = [
                            'type' => $exp[1],
                            'name' => $exp[2]
                        ];
                        for ($i = 3; $i < $count; $i ++) {
                            $tmp[] = $exp[$i];
                        }
                    } else {
                        continue;
                    }
                if ($tmp) {
                    $tmp = array_reverse($tmp);
                    $tmp = implode(' ', $tmp);
                    $attr['note'] = $tmp;
                }
                $annData[$exp[0]][] = $attr;
                $tmp = [];
            } else {
                $tmp[] = $value;
            }
        }
        if ($tmp) {
            $tmp = array_reverse($tmp);
            $tmp = implode(' ', $tmp);
            $annData['function'] = [
                'note' => $tmp
            ];
        }
        return $annData;
    }

    /**
     * Get The Paramaters Of Given Function
     *
     * @param unknown $function            
     * @return boolean|multitype:NULL
     */
    function getFunctionParamaters($function)
    {
        $reflect = getFunctionReflection($function);
        if ($reflect === false)
            return false;
        $parameters = $reflect->getParameters();
        $params = array();
        foreach ($parameters as $value) {
            $params[] = $value->getName();
        }
        return $params;
    }

    /**
     * 获取方法的反射
     *
     * @param string|array $function
     *            方法名
     * @return boolean|ReflectionFunction
     */
    function getFunctionReflection($name)
    {
        if (is_array($name)) {
            if (method_exists($name[0], $name[1])) {
                $reflect = new ReflectionMethod($name[0], $name[1]);
            } else {
                return false;
            }
        } else {
            try {
                $reflect = new ReflectionFunction($name);
            } catch (\Exception $e) {
                return false;
            }
        }
        return $reflect;
    }

    /**
     * 获取方法的代码
     *
     * @param unknown $name            
     * @return boolean|multitype:Ambigous
     */
    function getFunctionDeclaration($name, $show = false)
    {
        $reflect = getFunctionReflection($name);
        if ($reflect === false)
            return false;
        $start = $reflect->getStartLine();
        $end = $reflect->getEndLine();
        $file = $reflect->getFileName();
        if ($show) {
            dump($file . ":$start - $end");
        }
        $res = getRows($file, $start - 1, $end - $start + 1);
        return $res;
    }
}

if (! function_exists('lode')) {

    /**
     * 分割数组或字符串处理
     *
     * @param string $type
     *            : , | @
     * @param type $data
     *            : array|string
     * @internal string $type ->a=array ->explode || $type ->s=string ->implode
     * @return array string
     */
    function lode($type, $data)
    {
        if (is_string($data)) {
            return explode($type, $data);
        } elseif (is_array($data)) {
            return implode($type, $data);
        }
    }
}

if (! function_exists('createInsertSql')) {

    /**
     * Create An Insert Sql Statement
     *
     * @param string $tbname            
     * @param array $data            
     * @return string
     */
    function createInsertSql($tbname, array $data)
    {
        $fields = implode('`,`', array_keys($data));
        $values = implode('\',\'', array_values($data));
        $sql = 'insert into `' . $tbname . '`(`' . $fields . '`)values(\'' . $values . '\')';
        return $sql;
    }

    function insertGroupSql($tbname, array $data, $max = 100)
    {
        $res = [];
        foreach (array_chunk($data, $max) as $v) {
            $sql = creategroupInsertSql($tbname, $v);
            $res[] = \DB::insert($sql);
        }
        return $res;
    }

    function creategroupInsertSql($tbname, array $data)
    {
        $fields = implode('`,`', array_keys($data[0]));
        $values = implode('\',\'', array_values($data[0]));
        $sql = 'insert into `' . $tbname . '`(`' . $fields . '`)values(\'' . $values . '\')';
        array_shift($data);
        foreach ($data as $k => $v) {
            $values = implode('\',\'', array_values($v));
            $sql .= ',(\'' . $values . '\')';
        }
        return $sql;
    }

    /**
     * Create An Insert Sql Statement With Param Placeholder
     *
     * @param string $tbname            
     * @param array $data            
     * @return multitype:string multitype:
     */
    function createInsertSqlBind($tbname, array $data)
    {
        $keys = array_keys($data);
        $values = array_values($data);
        $fields = implode('`,`', $keys);
        $places = array_fill(0, count($keys), '?');
        $places = implode(',', $places);
        $sql = 'insert into `' . $tbname . '`(`' . $fields . '`)values(' . $places . ')';
        return [
            'sql' => $sql,
            'data' => $values
        ];
    }
}

if (! function_exists('createUpdateSql')) {

    /**
     * Create A Update Sql Statement
     *
     * @param string $tbname            
     * @param array $data            
     * @param string $where            
     * @return string
     */
    function createUpdateSql($tbname, array $data, $where = '')
    {
        $set = '';
        $wh = '';
        foreach ($data as $k => $v) {
            $set .= ',`' . $k . '` = \'' . $v . '\'';
        }
        if (is_array($where)) {
            foreach ($where as $k => $v) {
                $wh .= ' and `' . $k . '` = \'' . $v . '\'';
            }
            $wh = substr($wh, 4);
        } else {
            $wh = $where;
        }
        $wh = empty($wh) ? $wh : ' WHERE ' . $wh;
        $set = substr($set, 1);
        $sql = 'UPDATE `' . $tbname . '` SET ' . $set . $wh;
        return $sql;
    }
}

if (! function_exists('old')) {

    /**
     * Get Previous Form Field Data
     *
     * @param string $key            
     * @param string $default            
     */
    function old($key = null, $default = null)
    {
        return app('request')->old($key, $default);
    }
}

if (! function_exists('insert')) {

    /**
     * Execute Insert Sql Statment
     *
     * @param unknown $table            
     * @param array $data            
     */
    function insert($table, array $data)
    {
        $result = createInsertSqlBind($table, $data);
        return DB::insert($result['sql'], $result['data']);
    }
}

if (! function_exists('update')) {

    /**
     * Execute Update Sql Statment
     *
     * @param unknown $table            
     * @param array $data            
     * @param unknown $where            
     */
    function update($table, array $data, $where)
    {
        $sql = createUpdateSql($table, $data, $where);
        return DB::update($sql);
    }
}
if (! function_exists('lastInsertId')) {

    /**
     * Get Last Insert Id
     */
    function lastInsertId()
    {
        return DB::getPdo()->lastInsertId();
    }
}
if (! function_exists('lastSql')) {

    /**
     * Get Last Query
     *
     * @return mixed
     */
    function lastSql()
    {
        $sql = DB::getQueryLog();
        $query = end($sql);
        return $query;
    }

    function dumpQuserys()
    {
        $sqls = DB::getQueryLog();
        dump($sqls);
    }

    function edumpQuserys()
    {
        $sqls = DB::getQueryLog();
        dump($sqls);
        exit();
    }
}


