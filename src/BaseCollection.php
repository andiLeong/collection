<?php


namespace Andileong\Collection;


use ArrayAccess;
use ArrayIterator;
use Countable;
use Exception;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

abstract class BaseCollection implements Countable , ArrayAccess , IteratorAggregate , JsonSerializable
{


    public function jsonSerialize() :mixed
    {
        return $this->items;
    }



    public function offsetUnset($offset) :void
    {
        unset($this->items[$offset]);
    }

    public function offsetGet($offset) :mixed
    {
        return $this->items[$offset];
    }

    public function getIterator() :Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function offsetExists($offset) :bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetSet($offset, $value) :void
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function count() :int
    {
        return sizeof($this->items);
    }



}