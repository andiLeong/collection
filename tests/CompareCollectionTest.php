<?php
namespace Tests;

use Andileong\Collection\Collection;
use PHPUnit\Framework\TestCase;

class CompareCollectionTest extends testcase
{


    public array $array;
    public array $compareArray;
    public Collection $collection;

    public function setUp() :void
    {
        parent::setUp();

        $array = [
            'product_id' => 1,
            'name' => 'Desk',
            'price' => 100,
            'discount' => false,
        ];

        $compareArray = [
            'product_id' => 1,
            'name' => 'Desk',
            'price' => 1111,
            'random_key' => false
        ];

        $this->array = $array;
        $this->compareArray = $compareArray;

        $this->collection = Collection::make($array);
    }

    /** @test */
    public function it_can_get_shared_array_from_collection()
    {
        $newCollection = $this->collection->shared($this->compareArray);
        $this->assertEquals( 2, $newCollection->count());
    }

    /** @test */
    public function it_can_get_shared_array_value_from_collection()
    {
        $newCollection = $this->collection->sharedValues($this->compareArray);
        $this->assertEquals( 3, $newCollection->count());
        $this->assertTrue( $newCollection->exist('discount'));
    }

    /** @test */
    public function it_can_get_different_array_value_from_collection()
    {
        $newCollection = $this->collection->diffValues($this->compareArray);
        $this->assertEquals( 1, $newCollection->count());
        $this->assertTrue( $newCollection->exist('price'));
    }

    /** @test */
    public function it_can_get_different_array_from_collection()
    {
        $newCollection = $this->collection->diff($this->compareArray);
        $this->assertEquals( 2, $newCollection->count());
        $this->assertTrue( $newCollection->exist('price'));
        $this->assertTrue( $newCollection->exist('discount'));
    }
}