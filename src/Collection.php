<?php
namespace Andileong\Collection;


use Closure;
use InvalidArgumentException;
use ReflectionFunction;

class Collection extends BaseCollection
{
    use Aggregatable;

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
        return new static(array_map($fn,$this->items, array_keys($this->items ) ));
    }

    public function filter(callable $fn = null, $mode = ARRAY_FILTER_USE_BOTH)
    {
        return new static(array_filter($this->items,$fn,$mode));
    }

    public function unique($flags = SORT_STRING)
    {
        return new static(array_unique($this->items, $flags ));
    }

    public function keys()
    {
        return new static(array_keys($this->items ));
    }

    public function keyBy($key)
    {
        $result = $this->reduce( function($carry,$item) use($key){

            $value = collection($item)->get($key, fn($key) =>
                throw new InvalidArgumentException("Key {$key} isn't exist")
            );
            $carry[$value] = $item;
            return $carry;
        }, []);

        return new static($result);
    }

    public function zip(array $array)
    {
        return $this->map(fn($item, $key) => [$item, collection($array)->get($key) ]);
    }

    public function eachCons(int $length)
    {
        return $this->map(
                    fn($item,$key) => [$item] +  array_slice($this->items , $key , $length)
                )->filter( fn($item) => count($item) == $length)
                ->values();
    }

    public function when(bool $boolean,callable $callback)
    {
        if($boolean){
            return $callback($this);
        }
        return $this;
    }

    public function ifEmpty(callable $callback)
    {
        if($this->isEmpty()){
            $callback($this);
        }
        return $this;
    }

    public function ifNotEmpty(callable $callback)
    {
        if($this->isNotEmpty()){
            $callback($this);
        }
        return $this;
    }

    public function get(string $key , $default = null )
    {
        if($this->has($key)){
            return $this->items[$key];
        }

        return $this->value($default,$key);
    }


    private function value($value, ...$args)
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }


    public function remove($key)
    {
        if(!$this->exist($key) ) {
            throw new InvalidArgumentException("Key {$key} isn't exist");
        }
        unset($this->items[$key]);
        return new static($this->items);
    }

    public function unshift(mixed ...$values)
    {
        array_unshift($this->items,implode($values) );
        return new static($this->items);
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
    public function shared(array $item, $default = null)
    {
        return $this->getDefault(array_intersect_assoc($this->items,$item), $default);
    }

    /**
     * return the shared value from the collection and passed array values
     * @param array $item
     * @return $this
     */
    public function sharedValues(array $item, $default = null)
    {
        return $this->getDefault(array_intersect($this->items,$item), $default);
    }

    public function diffValues(array $item, $default = null)
    {
        return $this->getDefault(array_diff($this->items,$item), $default);
    }

    public function diff(array $item, $default = null)
    {
        return $this->getDefault($this->arrayDiffAssoc($this->items,$item), $default);
    }

    public function random(int $length = 1)
    {
        if( $length == 1){
            return $this->items[array_rand($this->items)];
        }

        $count = $this->count();
        if ($length > $count) {
            throw new InvalidArgumentException(
                "You requested {$length} items, but there are only {$count} items available."
            );
        }

        $keys = array_rand($this->items,$length);
        return $this->only($keys)->values();
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

    public function pluck($key, $index = null)
    {
        return new static(array_column($this->items,$key, $index));
    }

    private function getDefault($result,$default)
    {
        return count($result) > 0 ? new static($result) : $default;
    }

    public static function parse(string ...$values)
    {
        return new static($values);
    }

    public function pop($amount = 1)
    {
        if($amount == 1){
            return array_pop($this->items);
        }

        if($this->count() < $amount){
            throw new InvalidArgumentException("The Items you are trying to pop is bigger than the collection count!");
        }

        return $this->slice(-$amount, $amount);
    }

    public function prepend(...$value)
    {
        array_unshift($this->items, ...$value);
        return $this;
    }

    public function hasDuplicates()
    {
        $unique = array_unique( $this->items  );
        return count($unique) !== $this->count();
    }

    public function duplicates($key = null)
    {
        if(!$key){
            return $this->diff(array_unique($this->items))->values();
        }

        $res = $this->arrayDiffAssoc(array_column($this->items,$key),array_unique(array_column($this->items,$key)));
        return self::make($res)->values();
    }

    protected function arrayDiffAssoc(array $array,array $array2)
    {
        return array_diff_assoc($array, $array2);
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
        return Arr::exists($this->items,$key);
    }

    public function has($key)
    {
        return isset($this->items[$key]) && !empty($this->items[$key]);
    }

    public function contains(callable|string $value)
    {
        if(is_callable($value)){
            return (boolean) $this->first($value);
        }
        return in_array( $value , array_values($this->all()) )  ;
    }

    public function merge(Array $array)
    {
        return new static(array_merge($array,$this->items));
    }

    public function values()
    {
        return new static(array_values($this->all()));
    }

    /**
     * @param callable|null $callback
     * @param null $default
     * @return mixed|null
     */
    public function first(callable $callback = null , $default = null)
    {
        return Arr::first($this->items,$callback,$default);
    }

    public function second()
    {
        return $this->slice(1,1)->first();
    }

    public function last()
    {
        return end($this->items);
    }

    public function push(...$values)
    {
        foreach ($values as $value) {
            $this->items[] = $value;
        }
        return $this;
    }

    public function reduce(callable $callable , mixed $initial = null)
    {
        return array_reduce( $this->items, $callable ,$initial);
    }

    public function implode(string $seperator)
    {
        return implode( $seperator, $this->items);
    }

    public static function explode(string $separator , string $string)
    {
        return new static(explode($separator,$string));
    }

    public function each(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            $callback($item, $key);
        }
        return $this;
    }

    public function transpose()
    {
        $items = array_map(fn(...$items) => $items, ...$this->values());
        return new static($items);
    }

    public function all()
    {
        return $this->items;
    }

    public function count()
    {
        return count($this->items);
    }

    public function toArray()
    {
        return $this->items;
    }

    public function toJson()
    {
        return json_encode($this,JSON_UNESCAPED_UNICODE );
    }

}