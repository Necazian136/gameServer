<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 08.09.2020
 * Time: 12:04
 */

namespace App;

use App\DTO\RequestObject;
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
        $playerService = new PlayerService();
        $mapService = new MapService('map.txt', new ObjectMapperService());
        $eventService = new EventService($playerService, $mapService);

        $eventDispatcher->registerObjectEvents($eventService);

        $this->eventHandler = new EventHandler($eventDispatcher);
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $event = new RequestObject('connect', true);
        $event->setConnection($conn);
        $this->eventHandler->handleEvent($event);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $this->eventHandler->handleEvent((new RequestObject($msg))->setConnection($from));
    }

    public function onClose(ConnectionInterface $conn)
    {
        $event = new RequestObject('disconnect', true);
        $event->setConnection($conn);
        $this->eventHandler->handleEvent($event);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}