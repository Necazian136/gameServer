<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 02.10.2020
 * Time: 13:58
 */

namespace App\DTO\Server;


class ResponseObject
{
    const MAP_KEY = 'map';
    const OBJECTS_KEY = 'objects';
    const ADD_PLAYERS_KEY = 'add_player';
    const REMOVE_PLAYERS_KEY = 'remove_player';
    const UPDATE_PLAYER_KEY = 'update_player';
    const GET_MY_PLAYER_KEY = 'get_my_player';

    private $event;
    private $data;

    public function __construct($event, $data)
    {
        $this->event = $event;
        $this->data = $data;
    }

    public function __toString()
    {
        /**
         * @var string $result
         */
        $result = json_encode(['event' => $this->event, 'data' => $this->data]);
        return $result;
    }
}