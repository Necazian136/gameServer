<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 02.10.2020
 * Time: 13:58
 */

namespace App\DTO;


class RequestObject
{
    public $action;

    public function __construct($request)
    {
        $request = json_decode($request, true);
        $this->action = array_keys($request)[0];
        $this->vaule = $request[$this->action];
    }
}