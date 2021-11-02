<?php
namespace Andileong\Collection;


class Collection extends BaseCollection
{

    /**
     * AndiCollection constructor.
     * @param array $items
     */
    public function __construct(protected array $items)
    {
    }

    public static function make(array $items)
    {
        return new static($items);
    }

    public function map(callable $fn)
    {
        return new static(array_map($fn,$this->items));
    }

    public function filter(callable $fn , $mode = 0)
    {
        return new static(array_filter($this->items,$fn,$mode));
    }

    public function unique($flags = SORT_STRING)
    {
        return new static(array_unique($this->items, $flags ));
    }

    public function remove($key)
    {
        if( $this->exist($key) ) {
            unset($this->items[$key]);
            return new static($this->items);
        }
        return new static($this->items);
    }

    public function sum($key = null)
    {
        return is_null($key)
            ? array_sum($this->items)
            : array_sum(
                $this->pluck($key)->all()
            );
    }

    public function average($key = null)
    {
        return  round($this->sum($key) / $this->count() , 0);
    }

    public function only(array|string $keys)
    {
        $keys = array_flip( is_string($keys) ? [$keys] : $keys);
        return new static(array_intersect_key($this->items,$keys));
    }

    /**
     * return the shared key and value from the collection and passed array key and values
     * @param array $item
     * @return $this
     */
    public function shared(array $item)
    {
        return new static(array_intersect_assoc($this->items,$item));
    }

    /**
     * return the shared value from the collection and passed array values
     * @param array $item
     * @return $this
     */
    public function sharedValues(array $item)
    {
        return new static(array_intersect($this->items,$item));
    }

    public function random()
    {
        return array_rand(array_flip($this->items));
    }

    public function reverse()
    {
        return new static(array_reverse($this->items));
    }

    protected function flat($items)
    {
        $result = [];
        foreach ($items as $item) {
            if (! is_array($item)) {
                $result[] = $item;
            } else {
                $values = $this->flat($item);
                foreach ($values as $value) {
                    $result[] = $value;
                }
            }
        }
        return $result;
    }

    public function flatten()
    {
        return new static($this->flat($this->items));
    }

    public function sort($direction = 'des', $flags = SORT_REGULAR)
    {
        if( $direction == 'des'){
            rsort($this->items, $flags);
        }else{
            sort($this->items, $flags);
        }
        return new static($this->items);
    }

    public function sortBy($key,$direction = SORT_ASC , $flag = SORT_REGULAR )
    {
        array_multisort( $this->pluck($key)->all(), $direction, $flag, $this->items );
        return new static($this->items);
    }

    public function limit($limit)
    {
        return $this->slice(0,$limit);
    }

    public function slice($offset, $length = null)
    {
        return new static( array_slice($this->items , $offset , $length ) );
    }

    public function pluck($key,$index = null)
    {
        return new static(array_column($this->items,$key,$index));
    }

    public function isEmpty()
    {
        return sizeof($this->items) == 0;
    }

    public function isNotEmpty()
    {
        return !$this->isEmpty();
    }

    public function exist($key)
    {
        return array_key_exists($key,$this->items);
    }

    public function has($key)
    {
        return isset($this->items[$key]) && !empty($this->items[$key]);
    }

    public function merge(Array $array)
    {
        return new static(array_merge($array,$this->items));
    }

    public function values()
    {
        return new static(array_values($this->all()));
    }

    public function first()
    {
        return array_shift($this->items);
    }

    public function second()
    {
        return $this->slice(1,1)->first();
    }

    public function last()
    {
        return array_pop($this->items);
    }

    public function push(...$values)
    {
        foreach ($values as $value) {
            $this->items[] = $value;
        }
        return $this;
    }

    public function all()
    {
        return $this->items;
    }

    public function count()
    {
        return count($this->items);
    }

}