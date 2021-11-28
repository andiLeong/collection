<?php

use Andileong\Collection\Arr;
use Andileong\Collection\Collection;

if (!function_exists('collection')) {

    function collection(array $items): Collection
    {
        return Collection::make($items);
    }
}

if (!function_exists('array_get')) {

    function array_get(iterable|ArrayAccess $array,$key,$default = null)
    {
        return Arr::get($array,$key,$default);
    }
}

if (!function_exists('array_first')) {

    function array_first(iterable $array,callable $callback,$default = null)
    {
        return Arr::first($array,$callback,$default);
    }
}