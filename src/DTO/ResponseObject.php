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
    private $map;

    public function __construct($map)
    {
        $this->map = $map;
    }

    public function __toString()
    {
        /**
         * @var string $result
         */
        $result = json_encode(['map' => $this->map]);
        return $result;
    }
}