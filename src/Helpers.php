<?php

use Andileong\Collection\Collection;

if (!function_exists('collection')) {
    function collection(array $items): Collection
    {
        return Collection::make($items);
    }
}