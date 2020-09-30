<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 30.09.2020
 * Time: 14:28
 */

namespace App\Event;


use App\Service\MapService;
use App\Service\PlayerService;
use Ratchet\ConnectionInterface;

class EventHandler
{
    protected $clients;
    protected $playerService;
    protected $mapService;

    public function __construct(PlayerService $playerService, MapService $mapService)
    {
        $this->playerService = $playerService;
        $this->mapService = $mapService;
        $this->clients = new \SplObjectStorage();
    }

    public function handleConnect(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    public function handleEvent(ConnectionInterface $conn, $message)
    {
        foreach ($this->clients as $client) {
            $client->send($message);
        }
        $this->clients->attach($conn);
    }

    public function handleDisconnect(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }
}