<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 30.09.2020
 * Time: 14:44
 */

namespace App\DTO;


use Ratchet\ConnectionInterface;

class Player
{
    private $x;
    private $y;
    private $conn;

    /**
     * Player constructor.
     * @param $x
     * @param $y
     * @param ConnectionInterface $conn
     */
    public function __construct($x, $y, ConnectionInterface $conn)
    {
        $this->x = $x;
        $this->y = $y;
        $this->conn = $conn;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConn()
    {
        return $this->conn;
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     * @return Player
     */
    public function setX($x)
    {
        $this->x = $x;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     * @return Player
     */
    public function setY($y)
    {
        $this->y = $y;
        return $this;
    }

}