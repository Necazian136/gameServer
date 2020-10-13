<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 30.09.2020
 * Time: 14:28
 */

namespace App\Event;


use App\DTO\RequestObject;

class EventHandler
{
    protected $clients;
    protected $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->clients = new \SplObjectStorage();
    }
    /**
     * @param RequestObject $request
     */
    public function handleEvent(RequestObject $request)
    {
        $this->eventDispatcher->dispatch($request);
    }
}