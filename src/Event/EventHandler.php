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
        list($x, $y) = $this->mapService->findRandomEmptyTile();
        $player = $this->playerService->createPlayer((int)$x, (int)$y, $conn);
        $this->mapService->addPlayer($player);
    }

    public function handleEvent(ConnectionInterface $conn, $message)
    {
        $playerMap = $this->mapService->getMapForPlayer($player);
        foreach ($this->clients as $client) {
            $player = $this->playerService->getPlayerByConnection($client);
            $client->send($message);
        }
        $this->clients->attach($conn);
    }

    public function handleDisconnect(ConnectionInterface $conn)
    {
        $this->playerService->removePlayerByConnection($conn);
    }
}