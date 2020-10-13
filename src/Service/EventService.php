<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 13.10.2020
 * Time: 14:04
 */

namespace App\Service;


use App\DTO\Player;
use App\DTO\RequestObject;
use App\DTO\ResponseObject;

class EventService
{
    private $playerService;
    private $mapService;

    private $visiblePlayers;

    public function __construct(PlayerService $playerService, MapService $mapService)
    {
        $this->playerService = $playerService;
        $this->mapService = $mapService;
    }

    public function connectEvent(RequestObject $request)
    {
        list($x, $y) = $this->mapService->findRandomEmptyTile();
        $player = $this->playerService->createPlayer((int)$x, (int)$y, $request->conn);
        $request->conn->send(new ResponseObject(ResponseObject::MAP_KEY, $this->mapService->getMapForPlayer($player)));
        $request->conn->send(new ResponseObject(ResponseObject::GET_PLAYERS_KEY, $this->playerService->getPlayersAroundPlayer($player)));
    }

    public function disconnectEvent(RequestObject $request)
    {
        $player = $this->playerService->getPlayerByConnection($request->conn);
        if ($player) {
            $this->mapService->removeObject($player);
            $this->playerService->removePlayer($player);
        }
    }

    public function moveEvent(RequestObject $request)
    {
        $player = $this->playerService->getPlayerByConnection($request->conn);
        $this->movePlayer($player, $request->value);
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