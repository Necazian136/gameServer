<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 08.09.2020
 * Time: 12:04
 */

namespace App;

use App\Event\EventHandler;
use App\Service\MapService;
use App\Service\ObjectMapperService;
use App\Service\PlayerService;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class SocketHandler implements MessageComponentInterface
{
    protected $eventHandler;

    public function __construct()
    {
        $playerService = new PlayerService();
        $objectMapper = new ObjectMapperService();
        $mapService = new MapService('map.txt', $objectMapper);
        $this->eventHandler = new EventHandler($playerService, $mapService);
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->eventHandler->handleConnect($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $this->eventHandler->handleEvent($from, $msg);
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->eventHandler->handleDisconnect($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}