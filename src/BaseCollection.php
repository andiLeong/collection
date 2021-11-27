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


    public function jsonSerialize()
    {
        return $this->items;
    }



    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function count()
    {
        return sizeof($this->items);
    }



}