<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 02.10.2020
 * Time: 13:58
 */

namespace App\DTO;


class ResponseObject
{
    const MAP_KEY = 'map';
    const OBJECTS_KEY = 'objects';
    const GET_PLAYERS_KEY = 'get_players';
    const ADD_PLAYERS_KEY = 'add_players';
    const REMOVE_PLAYERS_KEY = 'remove_players';
    const UPDATE_PLAYER_KEY = 'update_player';
    const GET_PLAYER_KEY = 'get_player';

    private $key;
    private $value;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function __toString()
    {
        /**
         * @var string $result
         */
        $result = json_encode([$this->key => $this->value]);
        return $result;
    }
}