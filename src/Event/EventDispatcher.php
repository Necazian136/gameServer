<?php
/**
 * Created by PhpStorm.
 * User: iumymrin
 * Date: 13.10.2020
 * Time: 13:47
 */

namespace App\Event;



use App\DTO\Server\RequestObject;

class EventDispatcher
{
    const EVENT_NAME_POSTFIX = 'Event';

    private $events;

    /**
     * @param RequestObject $requestObject
     * @return mixed
     */
    public function dispatch(RequestObject $requestObject)
    {
        return $this->events[$requestObject->event]($requestObject);
    }

    /**
     * @param $object
     */
    public function registerObjectEvents($object)
    {
        foreach (get_class_methods($object) as $method) {
            if (($pos = strpos($method, self::EVENT_NAME_POSTFIX)) === strlen($method) - strlen(self::EVENT_NAME_POSTFIX)) {
                $this->events[mb_substr($method, 0, $pos)] = static function (RequestObject $requestObject) use ($object, $method) {
                    $object->{$method}($requestObject);
                };
            }
        }
    }
}