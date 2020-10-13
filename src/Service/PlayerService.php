<?php /** @noinspection PhpUndefinedFieldInspection */

/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 30.09.2020
 * Time: 14:30
 */

namespace App\Service;


use App\DTO\Player;
use App\Event\EventDispatcher;
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
        return $player;
    }


    /**
     * @return Player[]
     */
    public function getPlayers()
    {
        return $this->players;
    }


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
    }
}