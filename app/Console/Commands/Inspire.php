<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Models\User\DeveloperDevBind;

class Inspire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspire:mq';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';


    protected $var = <<<EOL
fffff ' "fff"
EOL;


    public function getv()
    {
        return "stt";
    }


    public function __toString()
    {
        // TODO: Implement __toString() method.
        return "Implement __toString() met";
    }


    public function fffffff()
    {
        // 输入拼写错误的单词
        $input = 'carrrot';

        // 要检查的单词数组
        $words = array('apple', 'pineapple', 'banana', 'orange',
            'radish', 'carrot', 'pea', 'bean', 'potato', 'error');

        // 目前没有找到最短距离
        $shortest = -1;

        // 遍历单词来找到最接近的
        foreach ($words as $word) {

            // 计算输入单词与当前单词的距离
            $lev = levenshtein($input, $word);
            echo $lev . PHP_EOL;
            // 检查完全的匹配
            if ($lev == 0) {

                // 最接近的单词是这个（完全匹配）
                $closest = $word;
                $shortest = 0;

                // 退出循环；我们已经找到一个完全的匹配
                break;
            }

            // 如果此次距离比上次找到的要短
            // 或者还没找到接近的单词
            if ($lev <= $shortest || $shortest < 0) {
                // 设置最接近的匹配以及它的最短距离
                $closest = $word;
                $shortest = $lev;
            }
        }

        echo "Input word: $input\n";
        if ($shortest == 0) {
            echo "Exact match found: $closest\n";
        } else {
            echo "Did you mean: $closest?\n";
        }
    }

    public $fff = "f";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = "first_name的交集，用回调函数比";
        for($i = 0 ; $i < strlen($data) ; $i ++)
            echo $data[$i].PHP_EOL;


        echo PHP_EOL;

        dd(strtoupper($data));

        $records = array(
            array(
                'id' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                ],
                'first_name' => 'John',
                'last_name' => 'Doe',
            ),
            array(
                'id' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                ],
                'first_name' => 'Sally',
                'last_name' => 'Smith',
            ),
            array(
                'id'=> [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                ],

                'last_name' => 'Jones',
            ),
            array(
                'id' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                ],
                'first_name' => 'Peter',
                'last_name' => 'Doe',
            )
        );
        dd(array_count_values(['a','a','b']));
        $var1 = 'Hello World';
        $var2 = '';
        $var3 =& $var1;
        $var2 =& $var1;

        debug_zval_dump($var1);
        debug_zval_dump($var2);

        exit;

        $array = array(
            "foo" => "bar",
            "bar" => "foo",
            100 => -100,
            -100 => 100,
        );
        var_dump($array);
        $array[] = 22;
        unset($array[100]);
        unset($array[101]);
        $array[] = 222;
        dd($array);


        $text = "The quick 打断字符串为指定数量的字串 brown fox jumped over the lazy dog.";
        $newtext = wordwrap($text, 10, "\n");

        dd($newtext);

        $trans = array("hello" => "hi", "hi" => "hello");
        $trans1 = array("he" => "hello", "hello" => "hi");
        dump(strtr("hi all, I said hello", $trans));
        dump(strtr("he all, I said hello", $trans1));


        exit;


        $string = "This is\tan example\nstring";
        /* 使用制表符和换行符作为分界符 */
        $tok = strtok($string, " \n\t");

        while ($tok !== false) {
            echo "Word=$tok" . PHP_EOL;
            $tok = strtok(" \n\t");
        }

        exit;

        $var = strspn("222 is the answer to the 128th question.", "1234567890");


        dd($var);

        $text = "Line 1\nLine 2\nLine 3";
        $last = substr(strrchr($text, chr(10)), 1);

        dd($last);

        $str = "first=value&arr[]=foo+bar&arr[]=baz";

// 推荐用法
        parse_str($str, $output);
        dd($output);
        //
        dd(number_format(1222222));

        dd(soundex("way") == soundex("come"));
        $this->fffffff();
        exit;
        $num = 0;
        $location = "tree";
        $format = 'The %2$-\'a9s contains %1$+d monkeys.
           That\'s a nice %2$s full of %1$d monkeys.';
        echo ucfirst(sprintf($format, $num, $location));
        echo PHP_EOL;
        exit;


        foreach ([2, 22, 222, 2222, 22222, 222222, 2222222] as $v) {
            dump(sprintf("%g", $v), sprintf("%e", $v), sprintf("%f", $v));
        }

        dd(sprintf("%g", 222));
        dump(CRYPT_STD_DES, CRYPT_BLOWFISH);
        dd(password_hash("fff", PASSWORD_DEFAULT));
        dd(crc32("ffffffffffff"));
        //
        $data = file_get_contents(__FILE__);
        dump(strlen($data));
        //  dump(count_chars($data));
        dd(array_sum(count_chars($data)));
        dump(base64_encode($data));

        dd($new_string = chunk_split(base64_encode($data)));
        dd(ctype_print("asd \t"));
        $url = 'http://username:password@hostname/path?arg=value#anchor';

        print_r(parse_url($url));

        echo parse_url($url, PHP_URL_PATH);
        exit;
        $input = "plain [indent] deep [indent] deeper [/indent] deep [/indent] plain";
        $input = "plain <indent> deep <indent> deeper </indent> deep </indent> plain";
        $func = "";
        $parseTagsRecursive = function ($input) use (&$func) {
            /* 译注: 对此正则表达式分段分析
            * 首尾两个#是正则分隔符
            * \[indent] 匹配一个原文的[indent]
            * ((?:[^[]|\[(?!/?indent])|(?R))+)分析:
            *   (?:[^[]|\[(?!/?indent])分析:
            *  首先它是一个非捕获子组
            *   两个可选路径, 一个是非[字符, 另一个是[字符但后面紧跟着不是/indent或indent.
            *   (?R) 正则表达式递归
            *     \[/indent] 匹配结束的[/indent]
            **/

            $regex = '#\[indent]((?:[^[]|\[(?!/?indent])|(?R))+)\[/indent]#';
            $regex = '#\<[a-zA-Z]+>((?:[^<]|\[(?!/?[a-zA-Z]+>)|(?R))+)\</[a-zA-Z]+>#';
            dump($input);
            if (is_array($input)) {
                $input = '<div style="margin-left: 10px">' . $input[1] . '</div>';
            }

            $r = preg_replace_callback($regex, $func, $input);
            dump($r);
            return $r;
        };
        $func = $parseTagsRecursive;
        $output = $parseTagsRecursive($input);

        echo $output;
        echo PHP_EOL;
        exit;
        /* 一个unix样式的命令行过滤器，用于将段落开始部分的大写字母转换为小写。 */
        $fp = fopen("php://stdin", "wr") or die("can't read stdin");
        while (!feof($fp)) {
            $line = fgets($fp);
            fwrite($fp, "output");
            $line = preg_replace_callback(
                '|<p>\s*\w|',
                function ($matches) {
                    return strtolower($matches[0]);
                },
                $line
            );
            echo $line;
        }
        fclose($fp);

        exit;

        $subject = 'CAaaaaa Bbb';

        $r = preg_replace_callback_array(
            [
                '/(?Ui)[a]+/' => function ($match) {

                    echo strlen($match[0]), ' matches for "a" found ', $match[0], PHP_EOL;
                    return "[AL]";
                },
                '~[b]+~i' => function ($match) {
                    echo strlen($match[0]), ' matches for "b" found ', $match[0], PHP_EOL;
                    return "[BL]";
                }
            ],
            $subject
        );

        dump($subject);
        dump($r);
        exit;
        $str = '0';

        dump((bool)$str);

        var_dump(array(<<<EOD
foobar!
EOD
        ));
        $f0 = 8;
        $f1 = (.1 + .7) * 10;
        $st = "这并不适用于激";
        dump($st);
        dump(strlen($st));
        dump('\\\'');
        //echo "\f";
        echo <<<"FOOBAR"
Hello World!
FOOBAR;

        $arr = ['or' => 'fff', 'a w' => 'ffffff'];

        $stt = "[this is stt]";
        $func = function ($f) {
            return "stt" . $f;
        };

        echo "this fff {$this->getv()}   is a $arr[or] value {$arr["a w"]}  \n";


        $str = "abc";
        //error_reporting(E_ERROR);
        dump($str);
        $str['6'] = 'f';
        dump($str);
        set_time_limit(0);
        ini_set('memory_limit', '912M');
        // 1585446912
        //  803213312

        $foo = "4"; // string
        $bar = true;   // boolean

        $r = settype($foo, "integer"); // $foo 现在是 5   (integer)
        dump($foo);
        dump($r);

        dump(strval(4e2));
        echo $this . PHP_EOL;

        $string = 'The quick brown fox jumps over the lazy dog.';
        $patterns = array();
        $patterns[0] = '/quick/';
        $patterns[1] = '/brown/';
        $patterns[2] = '/fox/';
        $replacements = array();
        $replacements[2] = '[brown]';
        $replacements[1] = 'black';
        $replacements[0] = 'slow';
        echo preg_replace($patterns, $replacements, $string);
        echo PHP_EOL;


        $subject = array('1', 'a', '2', 'b', '3', 'A', 'B', '4');
        $pattern = array('/\d/', '/[a-z]/', '/(A:)?[1a]/');
        $replace = array('A:$0', 'B:$0', 'C:$0');

        echo "preg_filter returns\n";
        print_r(preg_filter($pattern, $replace, $subject));

        echo "preg_replace returns\n";
        print_r(preg_replace($pattern, $replace, $subject));


        $str = <<<EOL
START WITH new line
STD other line
EOL;

        preg_match_all("/^ST[a-zA-Z ]+e$/m", $str, $matches);

        dump($matches);
        preg_match_all("/ST[a-zA-Z ]+e$/", $str, $matches);
        dump($matches);
        preg_match_all("/ST[a-zA-Z ]+e$/D", $str, $matches);
        dump($matches);

        $array = [
            "12", "12.4", "fff", ".2", "3333"
        ];
        $fl_array = preg_grep("/^(\d+)?\.\d+$/", $array, true);
        dump($fl_array);

        preg_match_all("|<[^>]+>(.*)</[^>]+>|U",
            "<b>example: </b><div align=left>this is a test</div>",
            $out, PREG_PATTERN_ORDER);
//        echo $out[0][0] . ", " . $out[0][1] . "\n";
//        echo $out[1][0] . ", " . $out[1][1] . "\n";
        dump($out);

        preg_match_all(
            '/(?J)(?<match>foo)|(?<match>bar)/',
            'foo bar',
            $matches,
            PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE
        );
        dump($matches);


        $textbody = "This book is *very* difficult to find.";
        $word = "*very*";
        dump(preg_quote($word, '/'));
        $textbody = preg_replace("/" . preg_quote($word, '/') . "/",
            "<i>" . $word . "</i>",
            $textbody);

        dump($textbody);

        exit;
        dump(resolve(\App\Extensions\Lock\Locker::class));
        \Storage::disk('public')->put('file.txt', 'Contents');
        dump(asset('storage/file.txt'));
//
//        $result = \DB::select("SELECT GET_LOCK('key',10) AS MyLOCK ");
//
//        dump($result[0]->MyLOCK);
        array_flatten([]);
        exit;

        $opts = [
            CURLOPT_HTTPHEADER => [
                'Authorization:' . "Basic YWRtaW46cHVibGlj"
            ]
        ];
        $res = curl_get('http://121.41.33.141:18083/api/clients', [], true, $opts);
        dd($res);
//         url : 'http://121.41.33.141:18083/api/clients',
//         headers: {
//             Authorization: "Basic YWRtaW46cHVibGlj"
//         $phone = '18767135775';
//         $code = '987654';
//         $res = \App\Services\Sms\SmsServices::sendBigFish($phone,[
//             'code' => $code,
//             'n' => '10'
//         ]);


//         333


        $dd = DeveloperDevBind::listUserBindedDevices(333);
        dd($dd);//         edump($res);
        ;
        $r = \App\Models\Open\Device::all();

        edump($r->toArray());


        edump((request()->isSecure() ? 'https://' : 'http://') . request()->getHost());

        $EmailSender = new \App\Services\Email\EmailSender();
        // 1012149817
        $param = [
            'username' => '779662959@qq.com',
            'link' => 'asdad',
        ];


//         $param = [
//             'email' => '779662959@qq.com',
//             'validateUrl' => 'asdad',
//         ];

//             $ret = $EmailSender->sendEmail('779662959@qq.com',  \App\Services\Email\EmailSender::EMAIL_REGISTER, $param);

        $ret = $EmailSender->sendEmail('779662959@qq.com', \App\Services\Email\EmailSender::EMAIL_PASSWRD_RESET, $param);
        dump($ret);


        $this->comment(PHP_EOL . Inspiring::quote() . PHP_EOL);
    }
}
