<?php
namespace Tests;

class CompareCollectionTest extends CollectionTestCase
{

    public array $compareArray;

    public function setUp() :void
    {
        parent::setUp();

        $compareArray = [
            'product_id' => 1,
            'name' => 'Desk',
            'price' => 1111,
            'random_key' => false
        ];

        $this->compareArray = $compareArray;
    }

    /** @test */
    public function it_can_get_shared_array_from_collection()
    {
        $newCollection = $this->associateCollection->shared($this->compareArray);
        $this->assertEquals( 2, $newCollection->count());
    }

    /** @test */
    public function it_can_get_shared_array_value_from_collection()
    {
        $newCollection = $this->associateCollection->sharedValues($this->compareArray);
        $this->assertEquals( 3, $newCollection->count());
        $this->assertTrue( $newCollection->exist('discount'));
    }

    /** @test */
    public function it_can_get_different_array_value_from_collection()
    {
        $newCollection = $this->associateCollection->diffValues($this->compareArray);
        $this->assertEquals( 1, $newCollection->count());
        $this->assertTrue( $newCollection->exist('price'));
    }

    /** @test */
    public function it_can_get_different_array_from_collection()
    {
        $newCollection = $this->associateCollection->diff($this->compareArray);
        $this->assertEquals( 2, $newCollection->count());
        $this->assertTrue( $newCollection->exist('price'));
        $this->assertTrue( $newCollection->exist('discount'));
    }
}