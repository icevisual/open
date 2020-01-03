<?php


if ('1e3' == '1000') echo 'LOL';
echo  PHP_EOL;
$a = "aabbzz"; $a++; echo $a.PHP_EOL;


$data = ['a','b','c'];

foreach($data as $k=>$v){

    $v = &$data[$k];

}

print_r($data);


echo true;

$val1= 5;
$val2 = 10;
function foo(&$my_val){
    global $val1;
    $val1+=2;//7
    $val2 =4;//4
    $my_val +=3;//8
    return $val2;//4
}
$my_val = 5;
echo foo($my_val)."\n";//4
echo $my_val;//8
echo $val1."\n".$val2."\n";//7   10
$bar = 'foo';
$my_val =10;
echo $bar($my_val)."\n";//4s
echo '\n';




