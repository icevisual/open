<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Extensions\Mqtt\MqttUtil;

class TopicLogger extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'topic:logger {action=at} {batch=default} {filter=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $config = [
        'sourcePath' => ''
    ];
    
    /**
     * 过滤点
     * @var unknown
     */
    protected $filterSecond = 30000;
    
    public function setFilterSecond($s){
        $this->filterSecond = $s * 1000;
    }
    
    public function isHitFilter($interval){
        
        return $this->filterSecond && $interval > $this->filterSecond;
    }

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        defined('DS') or define('DS', DIRECTORY_SEPARATOR);
        $action = $this->argument('action');
        $funcName = strtolower($action) . 'Action';
        if (method_exists($this, $funcName)) {
            call_user_func([
                $this,
                $funcName
            ]);
        } else {
            $this->error(PHP_EOL . 'No Action Found');
        }
        $this->comment(PHP_EOL . '> DONE !');
    }

    protected function cycleTemplate($directory, $callback)
    {
        // $directory = public_path('topicLog'.DS.'logs'.DS.date('md'));
        $scandir = scandir($directory);
        $ret = [];
        $sequence = [];
        foreach ($scandir as $k => $v) {
            $filename = $directory . DS . $v;
            if (is_file($filename)) {
                $lineArray = file($filename);
                foreach ($lineArray as $line) {
                    $info = $this->lineAnalyze($line);
                    call_user_func_array($callback, [
                        $info,
                        &$ret,
                        &$sequence
                    ]);
                }
            }
        }
        return $ret;
    }
    
    protected function generateStockJson($directory,$filename){
        $t = strtotime(date('Y-m-d 08:00:00'));
        
        $callback = function ($info,&$ret,&$sequence) use($t) {
            $SEQUENCE_NUMBER = $info['header']['SEQUENCE_NUMBER'];
            if (isset($sequence[$SEQUENCE_NUMBER])) {
                $pMin = $this->convertTime2Seconds($sequence[$SEQUENCE_NUMBER] > $info['time'] ? $info['time'] : $sequence[$SEQUENCE_NUMBER]);
                $interval = $this->calTimeAbsInterval($sequence[$SEQUENCE_NUMBER], $info['time']);
                // 过滤 显著奇异点
                if($this->isHitFilter($interval)){
                    return;
                }
                
                $ret[] = [
                    ($t + $pMin) * 1000,
                    $interval
                ];
                unset($sequence[$SEQUENCE_NUMBER]);
            } else {
                $sequence[$SEQUENCE_NUMBER] = $info['time'];
            }
        };
        // 获取 请求时间间隔
        $ret = $this->cycleTemplate($directory, $callback);
        file_put_contents(public_path($filename), json_encode($ret));
    }
    
    /**
     * 生成 stock chart ，数据从 json 文件获取
     * @param array $tagsArray
     */
    protected function stockchart(array $tagsArray){
        sort($tagsArray);
        foreach ($tagsArray as $tag){
            // 生成 json 文件
            $directory = public_path('topicLog' . DS . 'logs' . DS . $tag);
            $filename = 'topicLog' . DS . 'json' . DS . $tag . '.json';
            $this->generateStockJson($directory, $filename);
        }
        $sha1 = sha1(implode(',', $tagsArray)) ;
        
        $dist = 'topicLog' . DS . 'output' . DS . $sha1 . '-stock.html';
        $renderData = [
            'names' => $tagsArray,
        ];
        $dfile = \View::make('localtest.stockchart')->with('data', $renderData)->render();
        file_put_contents(public_path($dist), $dfile);
        shell_exec('start chrome open.smell.com/' . $dist);
    }
    
    public function stockAction()
    {
        // 一个包的数据
        // 生成 延时 折线图
        // 生成 以小时为单位的 请求 和 无响应 、 超时 的 数据 条状图
    
        // 去除 过点
        $inBatch = $this->argument('batch');
        $batch = $inBatch == 'default' ? date('md') : $inBatch;
    
        $filter = $this->argument('filter');
        
        $this->setFilterSecond($filter);
        
        $batchArray = explode('.', $batch);
        
        return $this->stockchart($batchArray);
    }

    /**
     * 总请求、超时请求、丢失请求 三个维度组成的 column-stacked 
     * @param unknown $directory
     * @param unknown $dist
     * @param number $timeoutLimit
     *  超时时间（毫秒）
     * @param number $groupInterval
     *  分组间隔（秒）
     */
    protected function columnStacked3($directory, $dist,$timeoutLimit = 5000,$groupInterval = 3600)
    {
        // 一个包的数据
        // 生成 延时 折线图
        // 生成 以小时为单位的 请求 和 无响应 、 超时 的 数据 条状图
        
        // 去除 过点
        $t = strtotime(date('Y-m-d 08:00:00'));
        
        $sequence = [];
        // 每个小时的已响应的请求数量
        $answeredReqNum = [
            'all' => new IntervalCounter($groupInterval),
            'timeout' => new IntervalCounter($groupInterval)
        ];
        
        $callback = function ($info, &$ret, &$sequence)
            use($t, $groupInterval, &$answeredReqNum, &$timeoutLimit) {
//        use($t, $groupInterval, &$sequence, &$answeredReqNum, &$timeoutLimit) {
            $SEQUENCE_NUMBER = $info['header']['SEQUENCE_NUMBER'];
            if (isset($sequence[$SEQUENCE_NUMBER])) {
                
                $pMin = $this->convertTime2Seconds($sequence[$SEQUENCE_NUMBER] > $info['time'] ? $info['time'] : $sequence[$SEQUENCE_NUMBER]);
                
                $month = $answeredReqNum['all']->calculateIndex($pMin);
                
                $answeredReqNum['all']->increase($month);
                
                $interval = $this->calTimeAbsInterval($sequence[$SEQUENCE_NUMBER], $info['time']);
                
                if ($interval >= $timeoutLimit) {
                    $answeredReqNum['timeout']->increase($month);
                    $answeredReqNum['all']->decrease($month);
                }
                
                $ret[] = [
                    ($t + $pMin) * 1000,
                    $interval
                ];
                unset($sequence[$SEQUENCE_NUMBER]);
            } else {
                $sequence[$SEQUENCE_NUMBER] = $info['time'];
            }
        };
        // 获取 请求时间间隔
        $ret = $this->cycleTemplate($directory, $callback);
        
        $answeredReqNum['lost'] = new IntervalCounter($groupInterval);
        // 每个小时未响应的请求数量
        foreach ($sequence as $v) {
            $p2s = $this->convertTime2Seconds($v);
            $month = $answeredReqNum['lost']->calculateIndex($p2s);
            $answeredReqNum['lost']->increase($month);
        }
        $dt = IntervalCounter::trimZero(array_values($answeredReqNum));
        
        $renderData = [
            'series' => [
                [
                    'name' => 'SUCC',
                    'data' => $dt[0][0]
                ],
                [
                    'name' => 'TOUT',
                    'data' => $dt[0][1]
                ],
                [
                    'name' => 'LOST',
                    'data' => $dt[0][2]
                ]
            ],
            'categories' => range($dt[1], $dt[2])
        ];
        
        $dfile = \View::make('localtest.columnstacked')->with('data', $renderData)->render();
        
        file_put_contents(public_path($dist), $dfile);
        
        shell_exec('start chrome smell.open.com/' . $dist);
    }

    public function columnAction()
    {
        // 一个包的数据
        // 生成 延时 折线图
        // 生成 以小时为单位的 请求 和 无响应 、 超时 的 数据 条状图
        
        // 去除 过点
        $inBatch = $this->argument('batch');
        $batch = $inBatch == 'default' ? date('md') : $inBatch;
        
        $directory = public_path('topicLog' . DS . 'logs' . DS . $batch);
        $dist = 'topicLog' . DS . 'output' . DS . $batch . '-column-stacked.html';
        
        return $this->columnStacked3($directory, $dist);
    }

    public function chartAction()
    {
        $inBatch = $this->argument('batch');
        $batch = $inBatch == 'default' ? date('md') : $inBatch;
        
        $directory = public_path('topicLog' . DS . 'logs' . DS . $batch);
        $dist = 'topicLog' . DS . 'output' . DS . $batch . '.html';
        
        $ret = $this->cycleTemplate($directory, function ($info, &$ret, &$sequence) {
            $SEQUENCE_NUMBER = $info['header']['SEQUENCE_NUMBER'];
            if (isset($sequence[$SEQUENCE_NUMBER])) {
                $p1s = $this->convertTime2Seconds($sequence[$SEQUENCE_NUMBER]);
                $p2s = $this->convertTime2Seconds($info['time']);
                $pMin = min([
                    $p1s,
                    $p2s
                ]);
                $ret[$pMin . ''] = $this->calTimeAbsInterval($sequence[$SEQUENCE_NUMBER], $info['time']);
            } else {
                $sequence[$SEQUENCE_NUMBER] = $info['time'];
            }
        });
        ksort($ret);
        $keys = array_map(function ($v) {
            return $v + 0;
        }, array_keys($ret));
        $chart = $this->chart([
            'title' => [
                'text' => '一天的相对时间（秒）'
            ],
            'categories' => $keys
        ], [
            [
                'name' => 'time',
                'data' => array_values($ret)
            ]
        ], '请求与响应时间间隔表', '', '请求和响应时间间隔（毫秒）');
        
        file_put_contents(public_path($dist), $chart);
        
        shell_exec('start chrome open.smell.com/' . $dist);
    }

    public function mergeAction()
    {
        $this->mergeTopicLog();
    }

    /**
     * 合并 topic log
     *
     * @return boolean
     */
    public function mergeTopicLog()
    {
        $inBatch = $this->argument('batch');
        $batch = $inBatch == 'default' ? date('md') : $inBatch;
        
        $directory = public_path('topicLog' . DS . 'logs' . DS . $batch);
        $dist = 'topicLog' . DS . 'merge' . DS . $batch . '.log';
        
        $ret = $this->cycleTemplate($directory, function ($info, &$ret, &$sequence) {
            $ret[] = array_only($info, [
                'time',
                'client',
                'topic',
                'header'
            ]);
        });
        usort($ret, function ($a, $b) {
            return $a['time'] < $b['time'];
        });
        $fp = fopen(public_path($dist), 'w');
        foreach ($ret as $l) {
            $ll = "{$l['time']} {$l['client']} PUBLISH TO {$l['topic']} C/S = {$l['header']['COMMAND_ID']} / {$l['header']['SEQUENCE_NUMBER']}";
            fwrite($fp, $ll . PHP_EOL);
        }
        fclose($fp);
    }

    /**
     * 分析一行数据
     *
     * @param unknown $line            
     * @return multitype:
     */
    protected function lineAnalyze($line)
    {
        // array:7 [
        // 0 => "16:44:19.105"
        // 1 => "[info]"
        // 2 => "NF3DyoBL8bjT6sjkM9a5#1151/NF3DyoBL8bjT6sjkM9a5"
        // 3 => "PUBLISH"
        // 4 => "to"
        // 5 => "/aaaaaaaaaa:"
        // 6 => "<<254,1,0,17,39,20,88,174,238,161,42,15,10,9,48,48,48,48,48,48,48,48,51,16,3,24,5>>"
        // ]
        $info = [];
        list ($info['time'], $info['level'], $info['client'], $info['action'], , $info['topic'], $info['message']) = explode(' ', $line);
        $message = trim($info['message'], "<>\n");
        $message = explode(',', $message);
        $message = array_slice($message, 0, 10);
        $msg = '';
        foreach ($message as $m) {
            $msg .= chr($m);
        }
        $header = MqttUtil::analyzeHeader($msg);
        $info['header'] = $header;
        $info['client'] = explode('/', $info['client'])[0];
        return $info;
    }

    /**
     * highcharts
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
    protected function chart(array $xAxis, array $series, $title = 'title', $subtitle = 'subtitle', $yAxis_title = 'yAxis_title')
    {
        $chartData = [
            'title' => $title,
            'subtitle' => $subtitle,
            'xAxis' => json_encode($xAxis),
            'yAxis_title' => $yAxis_title,
            'series' => json_encode($series)
        ];
        return \View::make('localtest.chart')->with('chartData', $chartData)->render();
    }

    /**
     * 将 hh:mm:ss.mmm 格式的时间转换成 带小数的秒
     *
     * @param unknown $p1            
     * @return number
     */
    protected function convertTime2Seconds($p1)
    {
        $p1s = preg_split('/[\:\.]/', $p1);
        return $p1s[2] + $p1s[1] * 60 + $p1s[0] * 3600 + $p1s[3] / 1000;
    }

    /**
     * 计算 一天内 两个时间点的时间差（毫秒）
     *
     * @param unknown $p1            
     * @param unknown $p2            
     * @return number
     */
    protected function calTimeAbsInterval($p1, $p2)
    {
        $p1s = $this->convertTime2Seconds($p1);
        $p2s = $this->convertTime2Seconds($p2);
        return trim(bcsub($p1s, $p2s, 4) . '', '-') * 1000;
    }
}

class IntervalCounter
{

    protected $interval = 1;

    protected $maxScale = 0;

    protected $maxIndex = - 1;

    protected $minIndex = 0;

    protected $totalNumber = 86400;

    protected $counterArray = [];

    public function __construct($interval)
    {
        $this->interval = $interval;
        
        $this->maxScale = ceil($this->totalNumber / $interval);
        
        $this->counterArray = array_fill(0, $this->maxScale, 0);
        
        $this->minIndex = $this->maxScale - 1;
    }

    /**
     * 以 $interval 秒 为间隔，将一天的时间分为 n 份 ，获取 $value 在这 n 份中的下标，从 0 开始
     * @param unknown $value
     * @return number
     */
    public function calculateIndex($value)
    {
        return floor(intval($value) / $this->interval);
    }

    public function getCounter($offset = 0, $length = NULL)
    {
        return array_slice($this->counterArray, $offset, $length);
    }

    public function increase($index, $step = 1)
    {
        if ($index > $this->maxIndex) {
            $this->maxIndex = $index;
        }
        if ($index < $this->minIndex) {
            $this->minIndex = $index;
        }
        $this->counterArray[$index] += $step;
    }

    public function decrease($index, $step = 1)
    {
        $this->counterArray[$index] -= $step;
    }

    public function getMaxIndex()
    {
        return $this->maxIndex;
    }

    public function getMinIndex()
    {
        return $this->minIndex;
    }

    public static function trimZero($counterArray)
    {
        $max = 0;
        $min = 9999;
        foreach ($counterArray as $k => $v) {
            $max = max([
                $max,
                $v->getMaxIndex()
            ]);
            $min = min([
                $min,
                $v->getMinIndex()
            ]);
        }
        foreach ($counterArray as $k => $v) {
            $counterArray[$k] = $v->getCounter($min, $max - $min + 1);
        }
        return [
            $counterArray,
            $min,
            $max
        ];
    }
}



