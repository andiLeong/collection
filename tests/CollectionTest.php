<?php
namespace Tests;

use Andileong\Collection\Collection;

class CollectionTest extends CollectionTestCase
{

    public function setUp() :void
    {
        parent::setUp();
    }


    /** @test */
    public function it_gets_the_collection_count()
    {
        $this->assertCount(4,$this->collection);
        $this->assertEquals(4,$this->collection->count());
    }

    /** @test */
    public function it_behaves_like_array()
    {
        $this->assertEquals('one' , $this->collection[0]);
        $this->collection['new'] = 'value';
        $this->assertEquals('value' , $this->collection['new']);
    }

    /** @test */
    public function it_can_be_iterated()
    {
        foreach ($this->collection as $key => $value)
        {
            $this->assertEquals( $this->array[$key] , $value);
        }
    }

    /** @test */
    public function it_can_be_map_over()
    {
        $collection = $this->collection->map(fn($item) => ucfirst($item));
        $this->assertEquals('One',$collection->first());
    }

    /** @test */
    public function it_can_be_filter()
    {
        $newCollection = $this->collection->filter(
            fn($value) => $value != 'one'
        )->values();
        $this->assertEquals('two',$newCollection->first());
    }

    /** @test */
    public function it_can_filter_same_values()
    {
        $arr = [1, 1, 2, 2, 3, 4, 2];
        $collection = Collection::make($arr);
        $this->assertCount(7,$collection);
        $newCollection = $collection->unique()->values();
        $this->assertCount(4,$newCollection);
    }

    /** @test */
    public function it_can_determine_if_key_is_exist()
    {
        $arr = ['first' => 'firstValue', 'second' => 'secondValue' , 'third' => null , 'forth' => ''];
        $collection = Collection::make($arr);

        $this->assertTrue($collection->exist('third'));
        $this->assertTrue($collection->exist('forth'));
        $this->assertFalse($collection->has('third'));
        $this->assertFalse($collection->has('forth'));
    }

    /** @test */
    public function it_can_merge_a_new_array()
    {
        $arr = ['first' => 'firstValue', 'second' => 'secondValue'];
        $newCollection = $this->collection->merge($arr);

        $this->assertCount(6 , $newCollection);
    }

    /** @test */
    public function it_can_check_collection_is_empty()
    {
        $this->assertFalse($this->collection->isEmpty());
    }

    /** @test */
    public function it_can_check_collection_is_not_empty()
    {
        $this->assertTrue($this->collection->isNotEmpty());
    }

    /** @test */
    public function it_can_remove_a_specific_key_from_collection()
    {
        $this->assertTrue($this->associateCollection->exist('name'));
        $newCollection = $this->associateCollection->remove('name');
        $this->assertFalse( $newCollection->exist('name'));
    }

    /** @test */
    public function it_can_sum_collection_items()
    {
        $arr = [1, 1, 2, 2, 3, 4, 2, 'asd']; //15
        $res = Collection::make($arr)->sum();

        $arr2 = [
            ['name' => 'andi', 'saving' => 100],
            ['name' => 'liang', 'saving' => 200],
        ];

        $res2 = Collection::make($arr2)->sum('saving');

        $this->assertEquals( 15, $res);
        $this->assertEquals( 300, $res2);
    }

    /** @test */
    public function it_can_get_average_collection_items()
    {
        $arr = [1, 1, 2, 2, 3, 4, 2, 'asd']; //15
        $res = Collection::make($arr)->average();

        $arr2 = [
            ['name' => 'andi', 'saving' => 100],
            ['name' => 'liang', 'saving' => 200],
        ];
        $res2 = Collection::make($arr2)->average('saving');

        $this->assertEquals( 2, $res);
        $this->assertEquals( 150, $res2);
    }

    /** @test */
    public function it_can_flatten_the_collection_items()
    {

        $array = ['name' => 'Joe', 'languages' => ['PHP', 'Ruby', 'Backend' => ['java','nodejs']  ]];
        $collection = Collection::make($array);
        $newCollection = $collection->flatten();

        $this->assertEquals( 'Joe', $newCollection[0]);
        $this->assertEquals( 'PHP', $newCollection[1]);
        $this->assertEquals( 'java', $newCollection[3]);
    }

    /** @test */
    public function it_can_slice_collection_items()
    {
        $collection = $this->collection->slice(1,1);
        $this->assertEquals('two' , $collection[0]);
    }

    /** @test */
    public function it_can_push_a_item_to_the_end_of_collection()
    {
        $collection = $this->collection->push('hello','world');
        $this->assertEquals( 6 , $collection->count());
        $this->assertEquals( 'world' , $collection->last());
    }


}