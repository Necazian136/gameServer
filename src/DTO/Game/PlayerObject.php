<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 30.09.2020
 * Time: 14:44
 */

namespace App\DTO\Game;


use App\Service\ObjectMapperService;
use Ratchet\ConnectionInterface;

class PlayerObject extends GameObject
{
    private $conn;

    private $vision;

    /**
     * Player constructor.
     * @param $x
     * @param $y
     * @param $char
     * @param ConnectionInterface $conn
     */
    public function __construct($x, $y, $char, ConnectionInterface $conn)
    {
        parent::__construct($x, $y, $char, ObjectMapperService::TYPE_PLAYER);
        $this->conn = $conn;
        $this->vision = 11;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConn()
    {
        return $this->conn;
    }

    /**
     * @return int
     */
    public function getVision()
    {
        return $this->vision;
    }
}