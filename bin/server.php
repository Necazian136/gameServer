<?php


require_once  __DIR__ . '/../vendor/autoload.php';

use Ratchet\Server\IoServer;
use App\SocketHandler;

$server = IoServer::factory(
    new SocketHandler(),
    8080
);

$server->run();