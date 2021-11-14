<?php
namespace Andileong\Collection;


trait Aggregatable
{

    public function sum($key = null)
    {
        return is_null($key)
            ? array_sum($this->items)
            : array_sum(
                $this->pluck($key)->all()
            );
    }

    public function min()
    {
        return min($this->items);
    }

    public function average($key = null)
    {
        return round($this->sum($key) / $this->count(), 0);
    }

    public function max()
    {
        return max($this->items);
    }
}