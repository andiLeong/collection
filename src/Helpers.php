<?php

use Andileong\Collection\Collection;

if (!function_exists('collection')) {

    function collection(array $items): Collection
    {
        return Collection::make($items);
    }
}

if (!function_exists('arr_get')) {

    function arr_get(iterable|ArrayAccess $array,$key,$default = null)
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