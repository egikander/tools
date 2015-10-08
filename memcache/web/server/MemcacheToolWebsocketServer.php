<?php

    use Ratchet\Server\IoServer;
    use Ratchet\Http\HttpServer;
    use Ratchet\WebSocket\WsServer;
    use MemcacheTool\Websocket\MemcacheInfo;

    require __DIR__ . "/../vendor/autoload.php";
    require __DIR__ . "/MemcacheToolWebsocketInfo.php";

    error_reporting(E_ERROR);
    set_time_limit(0);

    $port = (isset($argv[1]) && intval($argv[1]) > 0) ? intval($argv[1]) : 8003;
    $memcacheAddress = (isset($argv[2])) ? $argv[2] : 'localhost';
    $memcachePort = (isset($argv[3]) && intval($argv[3]) > 0) ? intval($argv[3]) : 11211;

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new MemcacheInfo($memcacheAddress, $memcachePort)
            )
        ),
        $port
    );

    $server->run();
