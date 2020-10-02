<?php /** @noinspection PhpUndefinedFieldInspection */

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
     * @var Player[]
     */
    private $players;

    /**
     * PlayerService constructor.
     */
    public function __construct()
    {
        $this->players = [];
    }

    public function createPlayer($x, $y, ConnectionInterface $conn)
    {
        $player = new Player($x, $y, (string)(count($this->players)), $conn);
        $this->players[$conn->resourceId] = ($player);
        return $player;
    }


    /**
     * @return Player[]
     */
    public function getPlayers()
    {
        return $this->players;
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
    }
}