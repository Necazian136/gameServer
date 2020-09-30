<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 30.09.2020
 * Time: 15:11
 */

namespace App\DTO;


class GameObject
{
    private $char;
    private $type;
    private $x;
    private $y;

    public function __construct($x, $y, $char, $type)
    {
        $this->char = $char;
        $this->type = $type;
        $this->x = $x;
        $this->y = $y;
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
     * @return GameObject
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
     * @return GameObject
     */
    public function setY($y)
    {
        $this->y = $y;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getChar()
    {
        return $this->char;
    }
}