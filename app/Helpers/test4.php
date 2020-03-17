<?php

class server {

    protected $ip;
    protected $port;

    /**
     * @var \swoole_server
     */
    protected $server;

    public function __construct($ip, $port)
    {
        $this->ip = $ip;
        $this->port = $port;
    }

    public function run()
    {
        // 创建 swoole_server 实例
        $this->createSwooleServer();
        $this->server->set(array(
            'worker_num' => 5,
            'daemonize' => false,
        ));
        $this->server->on('Start', array($this, 'onStart'));
        $this->server->on('Connect', array($this, 'onConnect'));
        $this->server->on('Receive', array($this, 'onReceive'));
        $this->server->on('Close', array($this, 'onClose'));
        $this->server->start();
    }

    private function createSwooleServer()
    {
        $this->server = new \swoole_server($this->ip, $this->port);
    }
    // 服务启动时触发
    public function onStart( swoole_server $serv ) {
        echo "Server Start\n";
    }
    // 当有客户端连接时触发
    public function onConnect( swoole_server $serv, $fd, $from_id ) {
        echo "Client {$fd} connection\n";
    }
    // 监听数据接收事件
    public function onReceive( swoole_server $serv, $fd, $from_id, $data ) {
        echo "Get Message From Client {$fd}:{$data}\n";
        $serv->send($fd,"ffff".PHP_EOL);
        $serv->close($fd);
    }
    // 客户端关闭连接时触发
    public function onClose( swoole_server $serv, $fd, $from_id ) {
        echo "Client {$fd} close connection\n";
    }

}

$server = new server("0.0.0.0", 8899);
$server->run();