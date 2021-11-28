<?php
namespace Tests\collection;

class CompareCollectionTest extends CollectionTestCase
{

    public array $compareArray;

    public function setUp() :void
    {
        parent::setUp();

        $this->compareArray = [
            'product_id' => 1,
            'name' => 'Desk',
            'price' => 1111,
            'random_key' => false
        ];
    }

    /** @test */
    public function it_can_get_shared_array_from_collection()
    {
        $newCollection = $this->associateCollection->shared($this->compareArray);
        $newCollection2 = $this->associateCollection->shared([],'default');

        $this->assertEquals( 'default', $newCollection2);
        $this->assertEquals( 2, $newCollection->count());
    }

    /** @test */
    public function it_can_get_shared_array_value_from_collection()
    {
        $newCollection = $this->associateCollection->sharedValues($this->compareArray);
        $newCollection2 = $this->associateCollection->sharedValues([],'default');

        $this->assertEquals( 3, $newCollection->count());
        $this->assertEquals( 'default', $newCollection2);
        $this->assertTrue( $newCollection->exist('discount'));
    }

    /** @test */
    public function it_can_get_different_array_value_from_collection()
    {
        $newCollection = $this->associateCollection->diffValues($this->compareArray);
        $newCollection2 = $this->associateCollection->diffValues($this->associateArray,'default');

        $this->assertEquals( 1, $newCollection->count());
        $this->assertTrue( $newCollection->exist('price'));
        $this->assertEquals( 'default', $newCollection2);
    }

    /** @test */
    public function it_can_get_different_array_from_collection()
    {
        $newCollection = $this->associateCollection->diff($this->compareArray);
        $newCollection2 = $this->associateCollection->diff($this->associateArray,'default');

        $this->assertEquals( 'default', $newCollection2);
        $this->assertEquals( 2, $newCollection->count());
        $this->assertTrue( $newCollection->exist('price'));
        $this->assertTrue( $newCollection->exist('discount'));
    }
}