<?php


require_once  __DIR__ . '/../vendor/autoload.php';

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use App\SocketHandler;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new SocketHandler()
        )
    ),
    8080
);

$server->run();