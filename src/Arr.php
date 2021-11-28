<?php

namespace Andileong\Collection;


use ArrayAccess;

class Arr
{

    public static function multiples(array $array, $multiple): array
    {
        return array_map(fn($item) => $item * $multiple,$array);
    }

    public static function exists(array $array, $key)
    {
        return array_key_exists($key,$array);
    }


    public static function first(iterable $array, callable $callback = null , $default = null)
    {
        if(is_null($callback)){
            foreach ($array as $item) {
                return $item;
            }
        }

        foreach ($array as $index => $value){
            if ($callback($value, $index)) {
                return $value;
            }
        }
        return $default;
    }

    public static function get(iterable|ArrayAccess $array,$key,$default = null)
    {

        if( isset($array[$key])  ){
            return $array[$key];
        }

        if(!str_contains($key,'.')){
            return $default;
        }

        return array_reduce(explode('.',$key),function($carry,$item) use($default){
            if ( isset($carry[$item])) {
                $carry = $carry[$item];
                return $carry;

            } else {
                return $default;
            }
        },$array);

    }
}