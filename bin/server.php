<?php


require_once  __DIR__ . '/../vendor/autoload.php';
require_once  __DIR__ . '/../config/credentials.php';

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
    SERVER_PORT, SERVER_IP
);

echo 'Running on ' . SERVER_IP . ':' . SERVER_PORT . PHP_EOL;

$server->run();