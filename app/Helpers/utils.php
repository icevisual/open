<?php

if (!function_exists("echoRunTime")) {

    function echoRunTime(callable $func)
    {
        list($start_ms, $start) = explode(" ", microtime());
        $m_start = memory_get_usage();
        $func();
        list($end_ms, $end) = explode(" ", microtime());
        $m_end = memory_get_usage();
        echo (intval($end) + floatval($end_ms)) - (intval($start) + floatval($start_ms)), " ", $m_end - $m_start, PHP_EOL;
    }
}

if (!function_exists("printHeap")) {


    function printHeap(array $data)
    {
        $len = count($data);
        // 层数
        $floorMax = ceil(\log($len + 1, 2));
        // 堆满后的最大数量
        $maxCount = pow(2, $floorMax) - 1;
        // 填充 0
        $appendCount = $maxCount - $len;

        while ($appendCount-- > 0)
            $data[] = 0;

        // 计算每个数字的位宽
        $max = max($data);
        $minGap = 1;
        $maxNumLen = 1;
        while ($max > 9) {
            $max = floor($max / 10);
            $maxNumLen++;
        }

        $outArray = [];
        // 存储上一层数字的前距，前面有几个字符
        $idx0 = [];
        for ($j = 0; $j < $floorMax; $j++) {
            $line = "";
            // 存储这层数字的前距，前面有几个字符
            $idx1 = [];
            // 每层数字数量
            $floorCount = pow(2, $floorMax - $j - 1);
            $startIndex = pow(2, $floorMax - $j - 1) - 1;
            // echo "\$floorCount = $floorCount\n";
            for ($i = 0; $i < $floorCount; $i++) {
                if (count($idx0) > 0) {
                    $preLeft = array_shift($idx0);
                    $preRight = array_shift($idx0);
                    $prefixLen = ceil(($preLeft + $preRight) / 2);
                    $idx1[] = $prefixLen;
                    while (strlen($line) < $prefixLen) $line .= " ";

                    $placeHolder = $data[$startIndex + $i] . "";
                    $padString = "_";
                    if ($data[$startIndex + $i] == 0) {
                        $placeHolder = " ";
                        $padString = " ";
                    }
                    $line = $line . str_pad($placeHolder, $maxNumLen, $padString, STR_PAD_BOTH);

                } else {
                    // 最后一层

                    // 存储渲染时的开始位置
                    $idx1[] = strlen($line);

                    $placeHolder = $data[$startIndex + $i] . "";
                    $padString = "_";
                    if ($data[$startIndex + $i] == 0) {
                        $placeHolder = " ";
                        $padString = " ";
                    }
                    $line = $line . str_pad($placeHolder, $maxNumLen, $padString, STR_PAD_BOTH) . " ";
                }
            }
            $idx0 = $idx1;
            $outArray[] = $line;
        }
        $outArray = array_reverse($outArray);
        foreach ($outArray as $v)
            echo $v . PHP_EOL;
    }
}

if (!function_exists("printArray")) {
    function printArray($data)
    {
        echo '[' . implode(", ", $data) . ']'.PHP_EOL;
    }
}




