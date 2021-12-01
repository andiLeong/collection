<?php


namespace Tests\mock;


class TapMock
{
    public $dummy ;

    public function getDummy()
    {
        return $this->dummy;
    }

    public function setDummy($dummy)
    {
        $this->dummy = $dummy;
    }

}