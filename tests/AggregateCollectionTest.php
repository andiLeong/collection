<?php


namespace Tests;


use Andileong\Collection\Collection;

class AggregateCollectionTest extends CollectionTestCase
{


    public array $aggregateArray;
    public Collection $aggregateCollection;

    public function setUp() :void
    {
        parent::setUp();

        $this->aggregateArray = [
            ['name' => 'andi', 'saving' => 100],
            ['name' => 'liang', 'saving' => 200],
        ];

        $this->aggregateCollection = collection($this->aggregateArray);
    }

    /** @test */
    public function it_can_sum_collection_items()
    {
        $res = $this->numberCollection->sum();
        $res2 = $this->aggregateCollection->sum('saving');

        $this->assertEquals( 15, $res);
        $this->assertEquals( 300, $res2);
    }

    /** @test */
    public function it_can_get_average_collection_items()
    {
        $res = $this->numberCollection->average();
        $res2 = $this->aggregateCollection->average('saving');

        $this->assertEquals( 3, $res);
        $this->assertEquals( 150, $res2);
    }

    /** @test */
    public function it_can_get_the_minimum_value_from_collection()
    {
        $collection = $this->numberCollection->unshift(1)->min();
        $this->assertEquals(1, $collection);
    }

    /** @test */
    public function it_can_get_the_maximum_value_from_collection()
    {
        $collection = $this->numberCollection->push(1000)->max();
        $this->assertEquals(1000, $collection);
    }
}