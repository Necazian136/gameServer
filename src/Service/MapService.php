<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 30.09.2020
 * Time: 14:20
 */

namespace App\Service;


use App\DTO\Game\GameObject;
use App\DTO\Game\PlayerObject;

class MapService
{
    /**
     * @var \SplObjectStorage
     */
    private $objects;

    private $mapHeight;

    private $map;

    private $mapWidth;

    public function __construct($mapPath, $tileMap, ObjectMapperService $objectMapperService)
    {
        $objectsString = file_get_contents($mapPath);
        $tilesString = file_get_contents($tileMap);
        $objects = [];
        $tiles = [];
        foreach (explode("\n", $objectsString) as $row) {
            $objects[] = str_split($row);
        }
        foreach (explode("\n", $tilesString) as $row) {
            $tiles[] = str_split($row);
        }
        $this->objects = new \SplObjectStorage();
        $y = 0;
        $x = 0;
        foreach ($tiles as $row) {
            $x = 0;
            $this->map[$y] = [];
            foreach ($row as $tileChar) {
                $object = null;
                if (isset($objects[$y][$x])) {
                    $objectChar = $objects[$y][$x];
                    $object = $objectMapperService->createObject($x, $y, $objectChar);
                    if ($object) {
                        $this->objects->attach($object);
                    }
                }
                $this->map[$y][$x] = ['tile' => ['x' => $x, 'y' => $y, 'char' => $tileChar], 'object' => $object];
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

    public function getMap()
    {
        return $this->map;
    }

    public function addPlayer(PlayerObject $player)
    {
        $this->objects->attach($player);
    }

    public function removeObject(GameObject $object)
    {
        $this->objects->detach($object);
    }
}