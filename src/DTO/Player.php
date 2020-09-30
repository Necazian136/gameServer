<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 30.09.2020
 * Time: 14:44
 */

namespace App\DTO;


use App\Service\ObjectMapperService;
use Ratchet\ConnectionInterface;

class Player extends GameObject
{
    private $conn;

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
    }

    /**
     * @return ConnectionInterface
     */
    public function getConn()
    {
        return $this->conn;
    }
}