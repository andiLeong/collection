<?php
namespace Tests;


use Andileong\Collection\Collection;
use InvalidArgumentException;

class CollectionGetTest extends CollectionTestCase
{

    /** @test */
    public function it_can_grab_the_first_item()
    {

        $first = $this->collection->first(fn($item , $key) => $key > 1);
        $firstNull = $this->collection->first(fn($item , $key) => $key > 10000);
        $firstDefault = $this->collection->first(fn($item , $key) => $key > 10000,'default');

        $this->assertNull($firstNull);
        $this->assertEquals('three', $first);
        $this->assertEquals('one',$this->collection->first());
        $this->assertEquals('default',$firstDefault);
    }

    /** @test */
    public function it_can_grab_the_second_item()
    {
        $this->assertEquals('two',$this->collection->second());
    }

    /** @test */
    public function it_can_grab_the_last_item()
    {
        $this->assertEquals('four',$this->collection->last());
    }

    /** @test */
    public function it_can_get_a_value_of_an_item()
    {
        $product_id = $this->associateCollection->get('product_id');
        $this->assertEquals(1 , $product_id);
    }

    /** @test */
    public function it_can_get_a_default_value_of_an_item_if_key_not_found()
    {
        $id = $this->associateCollection->get('id', 300);
        $this->assertEquals(300 , $id);
    }

    /** @test */
    public function it_can_get_a_default_value_of_an_item_if_key_not_found_by_using_callback()
    {
        $id = $this->associateCollection->get('id', fn() => 300);
        $this->assertEquals(300 , $id);
    }

    /** @test */
    public function it_can_get_certain_collection_items()
    {
        $newCollection = $this->associateCollection->only(['product_id','name']);
        $newCollection2 = $this->associateCollection->only('name');

        $this->assertEquals( 2, $newCollection->count());
        $this->assertEquals( 1, $newCollection2->count());
    }

    /** @test */
    public function it_can_pluck_a_collection_item()
    {
        $arr2 = [
            ['name' => 'andi', 'saving' => 100],
            ['name' => 'liang', 'saving' => 200],
        ];
        $optinons = ['index' => 'name', 'default' => 'default'];

        $newCollection = Collection::make($arr2)->pluck('saving');
        $newCollection2 = Collection::make($arr2)->pluck('givemedefault', $optinons);
        $newCollection3 = Collection::make($arr2)->pluck('saving', $optinons);

        $this->assertEquals( 100, $newCollection[0]);
        $this->assertEquals( 200, $newCollection[1]);
        $this->assertEquals( 100, $newCollection3['andi']);
        $this->assertEquals( 'default', $newCollection2);
    }

    /** @test */
    public function it_can_get_random_items_from_collection()
    {
        $res = in_array( $this->collection->random() , $this->array);
        $newCollection2 =  $this->collection->random(3);

        $this->assertEquals( 4 , $this->collection->count());
        $this->assertTrue( $res);
        $this->assertEquals( 3 , $newCollection2->count());
    }

    /** @test */
    public function if_random_length_large_than_collection_count_exception_will_throw()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->collection->random(300);
    }

    /** @test */
    public function it_can_get_collection_keys()
    {
        $collection = $this->associateCollection->keys();
        $this->assertEquals(['product_id','name','price','discount'], $collection->toArray());
    }
}