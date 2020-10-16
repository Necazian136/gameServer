<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 08.09.2020
 * Time: 12:04
 */

namespace App;

use App\DTO\Server\RequestObject;
use App\Event\EventDispatcher;
use App\Event\EventHandler;
use App\Service\EventService;
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
        $eventDispatcher = new EventDispatcher();
        $playerService = new PlayerService($eventDispatcher);
        $mapService = new MapService('map.txt', 'tile_map.txt', new ObjectMapperService());
        $eventService = new EventService($playerService, $mapService);

        $eventDispatcher->registerObjectEvents($eventService);

        $this->eventHandler = new EventHandler($eventDispatcher);
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->eventHandler->handleEvent($event = new RequestObject('connect', true, $conn));
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $this->eventHandler->handleEvent((new RequestObject($msg, null, $from)));
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->eventHandler->handleEvent(new RequestObject('disconnect', true, $conn));
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}