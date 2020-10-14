<?php /** @noinspection PhpUndefinedFieldInspection */

/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 30.09.2020
 * Time: 14:30
 */

namespace App\Service;


use App\DTO\Player;
use App\DTO\RequestObject;
use App\Event\EventDispatcher;
use Ratchet\ConnectionInterface;

class PlayerService
{
    /**
     * @var Player[]
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
     * @return Player
     */
    public function createPlayer($x, $y, ConnectionInterface $conn)
    {
        $player = new Player($x, $y, (string)(count($this->players)), $conn);
        $this->players[$conn->resourceId] = ($player);
        $this->visiblePlayers[$player->getId()] = new \SplObjectStorage();
        $this->updateVisiblePlayers($player);
        return $player;
    }

    public function updateVisiblePlayers(Player $player)
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
     * @return Player[]
     */
    public function getPlayers()
    {
        return $this->players;
    }


    /**
     * @param Player $player
     * @return \SplObjectStorage|Player[]
     */
    public function getPlayersAroundPlayer(Player $player)
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
     * @return Player
     */
    public function getPlayerByConnection(ConnectionInterface $conn)
    {
        return $this->players[$conn->resourceId];
    }

    public function removePlayerByConnection(ConnectionInterface $conn)
    {
        unset($this->players[$conn->resourceId]);
    }

    public function removePlayer(Player $player)
    {
        unset($this->players[$player->getConn()->resourceId]);
    }

    public function movePlayer(Player $player, $direction)
    {
        switch ($direction) {
            case 'Up':
                $player->setY($player->getY() - 1);
                break;
            case 'Down':
                $player->setY($player->getY() + 1);
                break;
            case 'Left':
                $player->setX($player->getX() - 1);
                break;
            case 'Right':
                $player->setX($player->getX() + 1);
                break;
        }
        $this->eventDispatcher->dispatch(new RequestObject('updatePlayer', $player, $player->getConn()));
        $this->updateVisiblePlayers($player);
        foreach ($this->visiblePlayers[$player->getId()] as $otherPlayer) {
            $this->eventDispatcher->dispatch(new RequestObject('updatePlayer', $player, $otherPlayer->getConn()));
        }
    }
}