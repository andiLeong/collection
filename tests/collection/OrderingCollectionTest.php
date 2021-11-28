<?php
namespace Tests\collection;


use Andileong\Collection\Collection;

class OrderingCollectionTest extends CollectionTestCase
{

    public function setUp() :void
    {
        parent::setUp();

    }

    /** @test */
    public function it_can_revert_collection_items()
    {
        $newCollection = $this->collection->reverse();
        $this->assertEquals( 'four', $newCollection[0]);
    }

    /** @test */
    public function it_can_sort_the_collection_items()
    {
        $sortAscending = $this->collection->sort('asc');
        $sortDescending  = $this->collection->sort();

        $this->assertEquals('four' , $sortAscending[0]);
        $this->assertEquals('two' , $sortDescending[0]);
    }

    /** @test */
    public function it_can_sort_the_collection_items_by_a_specific_key()
    {
        $array = [
            ['name' => 'Desk', 'price' => 200],
            ['name' => 'Chair', 'price' => 100],
            ['name' => 'Bookcase', 'price' => 150],
        ];
        $collection = Collection::make($array);
        $sortAscending = $collection->sortBy('price' );
        $sortDescending = $collection->sortBy('price', SORT_DESC  );

        $this->assertEquals(100 , $sortAscending[0]['price']);
        $this->assertEquals(200 , $sortDescending[0]['price']);
    }
}