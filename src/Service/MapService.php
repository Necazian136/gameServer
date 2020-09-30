<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 30.09.2020
 * Time: 14:20
 */

namespace App\Service;


use App\DTO\GameObject;
use App\DTO\Player;

class MapService
{
    const PLAYER_VISION_LENGTH = 11;

    /**
     * @var \SplObjectStorage
     */
    private $objects;

    private $mapHeight;

    private $mapWidth;

    private $map;

    public function __construct($mapPath, ObjectMapperService $objectMapperService)
    {
        $mapString = file_get_contents($mapPath);
        $map = [];
        foreach (explode("\n", $mapString) as $row) {
            $map[] = str_split($row);
        }
        $this->objects = new \SplObjectStorage();
        $y = 0;
        $x = 0;
        $this->map = [];
        foreach ($map as $row) {
            $x = 0;
            $this->map[$y] = [];
            foreach ($row as $char) {
                $this->map[$y][$x] = $char;
                $object = $objectMapperService->createObject($x, $y, $char);
                if ($object) {
                    $this->objects->attach($object);
                }
                $x++;
            }
            $y++;
        }
        $this->mapWidth = $x;
        $this->mapHeight = $y;
    }

    /**
     * returns [x, y]
     * @return array
     */
    public function findRandomEmptyTile()
    {
        $emptyTiles = [];
        for ($y = 0; $y < $this->mapHeight; $y++) {
            for ($x = 0; $x < $this->mapWidth; $x++) {
                $emptyTiles[$x . '_' . $y] = null;
            }
        }

        /**
         * @var GameObject $object
         */
        foreach ($this->objects as $object) {
            $emptyTiles[$object->getX() . '_' . $object->getY()] = $object;
        }
        $emptyTiles = array_filter($emptyTiles, function ($item) {
            return $item === null;
        });
        return explode('_', array_rand($emptyTiles));
    }

    public function getMapForPlayer(Player $player)
    {
        $x = $player->getX() - (int)(self::PLAYER_VISION_LENGTH / 2);
        $y = $player->getY() - (int)(self::PLAYER_VISION_LENGTH / 2);
        $result = '';
        for ($j = $y; $j < $y + self::PLAYER_VISION_LENGTH; $j++) {
            for ($i = $x; $i < $x + self::PLAYER_VISION_LENGTH; $i++) {
                if (isset($this->map[$j][$i])) {
                    $result .= $this->map[$j][$i];
                } else {
                    $result .= '*';
                }
            }
            $result .= "\n";
        }
        return $result;
    }

    public function addPlayer(Player $player)
    {
        $this->map[$player->getY()][$player->getX()] = $player->getChar();
        $this->objects->attach($player);
    }
}