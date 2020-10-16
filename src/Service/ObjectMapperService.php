<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 30.09.2020
 * Time: 15:16
 */

namespace App\Service;



use App\DTO\Game\GameObject;

class ObjectMapperService
{
    const TYPE_PLAYER = 'Player';
    const TYPE_BUSH = 'Bush';

    public function createObject($x, $y, $char)
    {
        switch ($char) {
            case 'B':
                return new GameObject($x, $y, $char, self::TYPE_BUSH);
                break;
        }
        return null;
    }
}