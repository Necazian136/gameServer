<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 30.09.2020
 * Time: 15:16
 */

namespace App\Service;


use App\DTO\GameObject;

class ObjectMapperService
{
    const TYPE_PLAYER = 'Player';
    const TYPE_TREE = 'Tree';

    public function createObject($x, $y, $char)
    {
        switch ($char) {
            case '0':
            case '1':
            case '2':
            case '3':
            case '4':
            case '5':
            case '6':
            case '7':
                return new GameObject($x, $y, $char, self::TYPE_PLAYER);
            case 'T':
                return new GameObject($x, $y, $char, self::TYPE_TREE);
                break;
        }
        return null;
    }
}