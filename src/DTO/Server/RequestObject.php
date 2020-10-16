<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 02.10.2020
 * Time: 13:58
 */

namespace App\DTO\Server;


use Ratchet\ConnectionInterface;

class RequestObject
{
    public $event;
    public $value;
    /**
     * @var ConnectionInterface
     */
    public $conn;

    public function __construct($request, $value = null, $conn = null)
    {
        if ($value === null) {
            $request = json_decode($request, true);
            $this->event = array_keys($request)[0];
            $this->value = $request[$this->event];
        } else {
            $this->event = $request;
            $this->value = $value;
        }
        if ($conn) {
            $this->conn = $conn;
        }
    }

    /**
     * @param ConnectionInterface $conn
     * @return $this
     */
    public function setConnection(ConnectionInterface $conn)
    {
        $this->conn = $conn;
        return $this;
    }
}