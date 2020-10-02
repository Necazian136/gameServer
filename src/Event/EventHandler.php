<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 30.09.2020
 * Time: 14:28
 */

namespace App\Event;


use App\DTO\Player;
use App\DTO\RequestObject;
use App\DTO\ResponseObject;
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
        $playerMap = $this->mapService->getMapForPlayer($player);
        $this->movePlayer($player);
    }

    /**
     * @param ConnectionInterface $conn
     * @param RequestObject $request
     */
    public function handleEvent(ConnectionInterface $conn, RequestObject $request)
    {
        $player = $this->playerService->getPlayerByConnection($conn);
        switch ($request->action) {
            case 'move':
                $this->movePlayer($player, $request->vaule);

        }
    }

    public function handleDisconnect(ConnectionInterface $conn)
    {
        $player = $this->playerService->getPlayerByConnection($conn);
        if ($player) {
            $this->mapService->removeObject($player);
            $this->playerService->removePlayer($player);
        }
    }

    protected function movePlayer(Player $player, $direction = null)
    {
        $this->playerService->movePlayer($player, $direction);
        $playerMap = $this->mapService->getMapForPlayer($player);
        $player->getConn()->send(new ResponseObject($playerMap));
        /**
         * @var Player[] $otherPlayers
         */
        $otherPlayers = $this->mapService->getPlayersAroundPlayer($player);
        foreach ($otherPlayers as $otherPlayer) {
            $otherPlayerMap = $this->mapService->getMapForPlayer($otherPlayer);
            $otherPlayer->getConn()->send(new ResponseObject($otherPlayerMap));
        }
    }
}