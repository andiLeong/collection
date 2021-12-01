<?php
namespace Andileong\Collection;

class TapProxy
{

    public function __construct(public $instance)
    {
        //
    }

    public function __call($method, $arguments)
    {
        $this->instance->$method(...$arguments);
        return $this->instance;
    }
}