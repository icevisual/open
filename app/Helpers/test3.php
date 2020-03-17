<?php

var_dump(explode(' ', 'count acw cols coldepth'));
exit;
$serv = stream_socket_server("tcp://0.0.0.0:8888",$errno,$errstr) or die("failed create sever");

echo "Start Server".PHP_EOL;

while(1)
{
    $conn = stream_socket_accept($serv);
    if(pcntl_fork() == 0)
    {
        $request = fread($conn,1024);
        $resp = "hello world";
        echo "send resp".PHP_EOL;
        fwrite($conn, $resp);
        // fclose($conn);
        exit(0);
        echo "fin".PHP_EOL;
    }
}

