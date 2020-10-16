<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 13.10.2020
 * Time: 14:04
 */

namespace App\Service;

use App\DTO\Game\PlayerObject;
use App\DTO\Server\RequestObject;
use App\DTO\Server\ResponseObject;

class EventService
{
    private $playerService;
    private $mapService;

    public function __construct(PlayerService $playerService, MapService $mapService)
    {
        $this->playerService = $playerService;
        $this->mapService = $mapService;
    }

    public function connectEvent(RequestObject $request)
    {
        list($x, $y) = $this->mapService->findRandomEmptyTile();
        $player = $this->playerService->createPlayer((int)$x, (int)$y, $request->conn);
        $request->conn->send(new ResponseObject(ResponseObject::MAP_KEY, $this->mapService->getMap()));
        $request->conn->send(new ResponseObject(ResponseObject::GET_MY_PLAYER_KEY, $player));
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
        $this->playerService->movePlayer($player, $request->value);
    }

    public function addPlayerEvent(RequestObject $request)
    {
        /**
         * @var PlayerObject $player
         */
        $player = $request->value;
        $request->conn->send(new ResponseObject(ResponseObject::ADD_PLAYERS_KEY, $player));
    }

    public function removePlayerEvent(RequestObject $request)
    {
        /**
         * @var PlayerObject $player
         */
        $player = $request->value;
        $request->conn->send(new ResponseObject(ResponseObject::REMOVE_PLAYERS_KEY, $player));
    }

    public function updatePlayerEvent(RequestObject $request)
    {
        /**
         * @var PlayerObject $player
         */
        $player = $request->value;
        $request->conn->send(new ResponseObject(ResponseObject::UPDATE_PLAYER_KEY, $player));
    }
}