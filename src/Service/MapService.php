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
    /**
     * @var \SplObjectStorage
     */
    private $objects;

    private $mapHeight;

    private $mapWidth;

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
        foreach ($map as $row) {
            $x = 0;
            foreach ($row as $char) {
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
        $map = array_fill(0, $player->getVision(), array_fill(0, $player->getVision(), ' '));

        $vision = (int)($player->getVision() / 2);
        /**
         * @var GameObject $object
         */
        foreach ($this->objects as $object) {
            $x = $player->getX() - $object->getX();
            if (abs($x) <= $vision) {
                $y = $player->getY() - $object->getY();
                if (abs($y) <= $vision) {
                    $map[$vision - $y][$vision - $x] = $object->getChar();
                }
            }
        }
        foreach ($map as $key => $row) {
            $map[$key] = implode($row);
        }
        return implode("\n", $map);
    }

    public function addPlayer(Player $player)
    {
        $this->objects->attach($player);
    }

    public function removeObject(GameObject $object)
    {
        $this->objects->detach($object);
    }
}