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
        $player = new Player($x, $y, $conn);
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

    public function removePlayerByConnection(ConnectionInterface $conn)
    {
        /**
         * @var Player $player
         */
        foreach ($this->players as $player) {
            if ($conn === $player->getConn()) {
                $this->players->detach($player);
                break;
            }
        }
    }

    public function removePlayer(Player $player)
    {
        $this->players->detach($player);
    }
}