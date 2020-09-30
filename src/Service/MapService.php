<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 30.09.2020
 * Time: 14:20
 */

namespace App\Service;


class MapService
{
    private $mapPath;

    public function __construct($mapPath)
    {
        $this->mapPath = $mapPath;
    }
}