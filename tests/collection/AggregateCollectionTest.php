<?php

namespace Tests\collection;


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

    /** @test */
    public function it_can_get_the_multiply_values_from_a_collection()
    {
        $multiplied = $this->numberCollection->multiply();
        $this->assertEquals(120,$multiplied);
    }

    /** @test */
    public function it_can_get_double_value_from_the_collection()
    {
        $doubled = $this->numberCollection->double();
        $this->assertEquals([2,4,6,8,10],$doubled->all());
    }

    /** @test */
    public function it_can_get_triple_value_from_the_collection()
    {
        $doubled = $this->numberCollection->triple();
        $this->assertEquals([3,6,9,12,15],$doubled->all());
    }
}