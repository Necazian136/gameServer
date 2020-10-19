<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 30.09.2020
 * Time: 15:11
 */

namespace App\DTO\Game;


class GameObject implements \JsonSerializable
{
    static protected $idNumber;
    static public $allObjects = [];
    private $id;
    protected $char;
    protected $type;
    protected $x;
    protected $y;
    protected $action;

    public function __construct($x, $y, $char, $type)
    {
        $this->id = ++self::$idNumber;
        $this->char = $char;
        $this->type = $type;
        $this->x = $x;
        $this->y = $y;
        $this->action = null;
        self::$allObjects[$this->id] = $this;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     * @return GameObject
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @param $id
     * @return GameObject
     */
    public static function getObjectById($id)
    {
        return self::$allObjects[$id];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'char' => $this->char,
            'type' => $this->type,
            'x' => $this->x,
            'y' => $this->y,
            'action' => $this->action,
        ];
    }
}