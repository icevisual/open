<?php
namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Exceptions\ServiceException;

class TestController extends Controller
{

    public function testJsonp(){
        $filename = \Input::get('filename');
        $callback = \Input::get('callback');
        $file = public_path('test/'.$filename);
        if($filename && file_exists($file)){
            $data = file_get_contents($file);
            $data = json_decode($data);
            return response()->jsonp($callback, $data);
        }
    }
    
    /**
     * Aliyun Iot Server
     */
    public function server(){
        $message = \Input::get('data');
        $messageJsonArray = json_decode($message,1);
        if(!$messageJsonArray || !isset($messageJsonArray['sign'])){
            return 'DROP';
        }
        $data = [
            'message' => array_get($messageJsonArray, 'message'),//message
            'topic' => array_get($messageJsonArray, 'topic'),//topic
            'sign' => array_get($messageJsonArray, 'sign'),//sign= md5_32(productKey+(message)+productSecret)
            'messageId' => array_get($messageJsonArray, 'messageId'),//messageId
            'appKey' => array_get($messageJsonArray, 'appKey'),//appKey
            'deviceId' => array_get($messageJsonArray, 'deviceId'),//deviceId
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $sql = createInsertSql('op_topic_msg', $data);
        \DB::insert($sql);
    }
    
    
    public function test()
    {
        
        $string = 'safasdf ad json';

        
        edump(substr($string, -4));
            
            
        phpinfo();
        
        exit;
        
        dump( \App\Models\Common\Region::getRegionName(6,192,218));
        exit;
        $file = public_path('a.txt');
        
        $str = file_get_contents($file);
        $str = trim($str,' ,');
        $str = strtolower($str);
        $name = [];
        $name = explode(', ', $str);
        sort($name);
        $data = implode('#', $name);
        $de_ = base64_encode(gzdeflate($data,9));
        dump($de_);;
        dump($data);
        dump(gzinflate(base64_decode($de_)));
        
//         $data = \App\Models\Common\Region::getAllRegions();
//         dump($data[6]['children'][0]);
        
        exit;
        \App\Extensions\Common\ErrorCode::detectError();
        exit;
        \App\Models\Common\Region::throwExc();
        
        $this->assddddd();
//         \App\Models\Common\RequestLog::upgradeSha1();
        return '';
        exit;
        \App\Services\Adag\ApidocAnnParser::autoGeneration('parse','',1);
        exit;
//         (?=X) X, via zero-width positive lookahead
//         (?!X) X, via zero-width negative lookahead
//         (?<=X) X, via zero-width positive lookbehind
//         (?<!X) X, via zero-width negative lookbehind
        
        $str = 'abcdEfgh';
        
        preg_match_all('/(.)(?=[A-Z])/u', $str,$matchs);
        
        edump($matchs);
        
        
//         edump(\Illuminate\Support\Str::snake('asdAsdArrrr'));
        
        \App\Services\Adag\ApidocAnnParser::autoGeneration();
        
        exit;
        
        edump(\Carbon\Carbon::now()->__toString());
        
        $data = \App\Models\Common\RequestLog::where('id',">",'0')->limit(2)->get();
        return $this->__json($data);
    }
}

// class LCS {
//     var $str1;
//     var $str2;
//     var $c = array();
//     /*返回串一和串二的最长公共子序列
//      */
//     function getLCS($str1, $str2, $len1 = 0, $len2 = 0) {
//         $this->str1 = $str1;
//         $this->str2 = $str2;
//         if ($len1 == 0) $len1 = strlen($str1);
//         if ($len2 == 0) $len2 = strlen($str2);
//         $this->initC($len1, $len2);
//         return $this->printLCS($this->c, $len1 - 1, $len2 - 1);
//     }
//     /*返回两个串的相似度
//      */
//     function getSimilar($str1, $str2) {
//         $len1 = strlen($str1);
//         $len2 = strlen($str2);
//         $len = strlen($this->getLCS($str1, $str2, $len1, $len2));
//         return $len * 2 / ($len1 + $len2);
//     }
//     function initC($len1, $len2) {
//         for ($i = 0; $i < $len1; $i++) $this->c[$i][0] = 0;
//         for ($j = 0; $j < $len2; $j++) $this->c[0][$j] = 0;
//         for ($i = 1; $i < $len1; $i++) {
//             for ($j = 1; $j < $len2; $j++) {
//                 if ($this->str1[$i] == $this->str2[$j]) {
//                     $this->c[$i][$j] = $this->c[$i - 1][$j - 1] + 1;
//                 } else if ($this->c[$i - 1][$j] >= $this->c[$i][$j - 1]) {
//                     $this->c[$i][$j] = $this->c[$i - 1][$j];
//                 } else {
//                     $this->c[$i][$j] = $this->c[$i][$j - 1];
//                 }
//             }
//         }
//     }
//     function printLCS($c, $i, $j) {
//         if ($i == 0 || $j == 0) {
//             if ($this->str1[$i] == $this->str2[$j]) return $this->str2[$j];
//             else return "";
//         }
//         if ($this->str1[$i] == $this->str2[$j]) {
//             return $this->printLCS($this->c, $i - 1, $j - 1).$this->str2[$j];
//         } else if ($this->c[$i - 1][$j] >= $this->c[$i][$j - 1]) {
//             return $this->printLCS($this->c, $i - 1, $j);
//         } else {
//             return $this->printLCS($this->c, $i, $j - 1);
//         }
//     }
// }

// $lcs = new LCS();
// //返回最长公共子序列
// $lcs->getLCS("hello word","hello china");
// //返回相似度
// echo $lcs->getSimilar("吉林禽业公司火灾已致112人遇难","吉林宝源丰禽业公司火灾已致112人遇难");



