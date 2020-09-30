<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 30.09.2020
 * Time: 14:30
 */

namespace App\Service;


use App\DTO\Player;
use Ratchet\ConnectionInterface;

class PlayerService
{
    /**
     * @var \SplObjectStorage
     */
    private $players;

    /**
     * PlayerService constructor.
     */
    public function __construct()
    {
        $this->players = new \SplObjectStorage();
    }

    public function createPlayer($x, $y, ConnectionInterface $conn)
    {
        $player = new Player($x, $y, (string)(count($this->players)), $conn);
        $this->players->attach($player);
        return $player;
    }

    /**
     * @return \SplObjectStorage
     */
    public function getPlayers()
    {
        return $this->players;
    }

    public function getPlayerByConnection(ConnectionInterface $conn)
    {
        foreach ($this->players as $player) {
            if ($conn === $player->getConn()) {
                return $player;
            }
        }
        return null;
    }

    public function removePlayerByConnection(ConnectionInterface $conn)
    {
        $player = $this->getPlayerByConnection($conn);
        if ($player) {
            $this->players->detach($player);
        }
    }

    public function removePlayer(Player $player)
    {
        $this->players->detach($player);
    }
}