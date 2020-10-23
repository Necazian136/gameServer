<?php /** @noinspection PhpUndefinedFieldInspection */

/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 30.09.2020
 * Time: 14:30
 */

namespace App\Service;


use App\DTO\Game\PlayerObject;
use App\DTO\Server\RequestObject;
use App\Event\EventDispatcher;
use Ratchet\ConnectionInterface;

class PlayerService
{
    /**
     * @var PlayerObject[]
     */
    private $players;

    /**
     * @var \SplObjectStorage[]
     */
    private $visiblePlayers;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * PlayerService constructor.
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->players = [];
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param $x
     * @param $y
     * @param ConnectionInterface $conn
     * @return PlayerObject
     */
    public function createPlayer($x, $y, ConnectionInterface $conn)
    {
        $player = new PlayerObject($x, $y, (string)(count($this->players)), $conn);
        $this->players[$conn->resourceId] = ($player);
        $this->visiblePlayers[$player->getId()] = new \SplObjectStorage();
        $this->updateVisiblePlayers($player);
        return $player;
    }

    public function updateVisiblePlayers(PlayerObject $player)
    {
        $newVisiblePlayers = $this->getPlayersAroundPlayer($player);
        foreach ($this->visiblePlayers[$player->getId()] as $oldVisiblePlayer) {
            if (!$newVisiblePlayers->contains($oldVisiblePlayer)) {
                $this->visiblePlayers[$oldVisiblePlayer->getId()]->detach($player);
                $this->visiblePlayers[$player->getId()]->detach($oldVisiblePlayer);
                $this->eventDispatcher->dispatch(new RequestObject('removePlayer', $player, $oldVisiblePlayer->getConn()));
                $this->eventDispatcher->dispatch(new RequestObject('removePlayer', $oldVisiblePlayer, $player->getConn()));
            }
        }
        foreach ($newVisiblePlayers as $otherPlayer) {
            if ($player->getId() === $otherPlayer->getId()) {
                continue;
            }
            if (!$this->visiblePlayers[$otherPlayer->getId()]->contains($player)) {
                $this->visiblePlayers[$otherPlayer->getId()]->attach($player);
                $this->eventDispatcher->dispatch(new RequestObject('addPlayer', $player, $otherPlayer->getConn()));
            }
            if (!$this->visiblePlayers[$player->getId()]->contains($otherPlayer)) {
                $this->visiblePlayers[$player->getId()]->attach($otherPlayer);
                $this->eventDispatcher->dispatch(new RequestObject('addPlayer', $otherPlayer, $player->getConn()));
            }
        }
    }

    /**
     * @return PlayerObject[]
     */
    public function getPlayers()
    {
        return $this->players;
    }


    /**
     * @param PlayerObject $player
     * @return \SplObjectStorage|PlayerObject[]
     */
    public function getPlayersAroundPlayer(PlayerObject $player)
    {
        $vision = (int)($player->getVision() / 2);
        $players = new \SplObjectStorage();

        foreach ($this->players as $otherPlayer) {
            $x = $player->getX() - $otherPlayer->getX();
            if (abs($x) <= $vision) {
                $y = $player->getY() - $otherPlayer->getY();
                if (abs($y) <= $vision) {
                    $players->attach($otherPlayer);
                }
            }
        }
        return $players;
    }

    /**
     * @param ConnectionInterface $conn
     * @return PlayerObject
     */
    public function getPlayerByConnection(ConnectionInterface $conn)
    {
        return $this->players[$conn->resourceId];
    }

    public function removePlayerByConnection(ConnectionInterface $conn)
    {
        unset($this->players[$conn->resourceId]);
    }

    public function removePlayer(PlayerObject $player)
    {
        $players = $this->getPlayersAroundPlayer($player);
        unset($this->players[$player->getConn()->resourceId]);
        foreach ($players as $otherPlayer) {
            if ($player->getId() !== $otherPlayer->getId()) {
                $this->updateVisiblePlayers($otherPlayer);
            }
        }
    }

    public function movePlayer(PlayerObject $player, $direction, $map)
    {
        switch ($direction) {
            case 'Up':
                if ($map[$player->getY() - 1][$player->getX()]['object'] !== null) {
                    return;
                }
                $player->setY($player->getY() - 1);
                break;
            case 'Down':
                if ($map[$player->getY() + 1][$player->getX()]['object'] !== null) {
                    return;
                }
                $player->setY($player->getY() + 1);
                break;
            case 'Left':
                if ($map[$player->getY()][$player->getX() - 1]['object'] !== null) {
                    return;
                }
                $player->setX($player->getX() - 1);
                break;
            case 'Right':
                if ($map[$player->getY()][$player->getX() + 1]['object'] !== null) {
                    return;
                }
                $player->setX($player->getX() + 1);
                break;
        }
        $player->setAction(['move' => $direction]);
        $this->eventDispatcher->dispatch(new RequestObject('updatePlayer', $player, $player->getConn()));
        $this->updateVisiblePlayers($player);
        foreach ($this->visiblePlayers[$player->getId()] as $otherPlayer) {
            $this->eventDispatcher->dispatch(new RequestObject('updatePlayer', $player, $otherPlayer->getConn()));
        }
        $player->setAction(null);
    }
}