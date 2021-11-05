<?php
namespace Tests;

use Andileong\Collection\Collection;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CollectionTest extends testcase
{

    public array $array;
    public Collection $collection;

    public function setUp() :void
    {
        parent::setUp();

        $array = ['one','two','three','four'];
        $this->array = $array;

        $this->collection = Collection::make($array);
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
    public function it_can_be_render_as_json()
    {
        $this->assertJson(json_encode($this->collection));
    }

    /** @test */
    public function it_can_be_map_over()
    {
        $collection = $this->collection->map(fn($item) => ucfirst($item));
        $this->assertEquals('One',$collection->first());
    }

    /** @test */
    public function it_can_grab_the_first_item()
    {
        $this->assertEquals('one',$this->collection->first());
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
        $arr = ['first' => 'firstValue', 'second' => 'secondValue'];
        $collection = Collection::make($arr);

        $this->assertTrue($collection->exist('first'));
        $newCollection = $collection->remove('first');
        $this->assertFalse( $newCollection->exist('first'));
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
    public function it_can_pluck_collection_items()
    {
        $arr2 = [
            ['name' => 'andi', 'saving' => 100],
            ['name' => 'liang', 'saving' => 200],
        ];
        $newCollection = Collection::make($arr2)->pluck('saving');
        $this->assertEquals( 100, $newCollection[0]);
        $this->assertEquals( 200, $newCollection[1]);
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
    public function it_can_get_certain_collection_items()
    {
        $arr = [
            'product_id' => 1,
            'name' => 'Desk',
            'price' => 100,
            'discount' => false,
        ];
        $newCollection = Collection::make($arr)->only(['product_id','name']);
        $newCollection2 = Collection::make($arr)->only('name');


        $this->assertEquals( 2, $newCollection->count());
        $this->assertEquals( 1, $newCollection2->count());
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
    public function it_can_revert_collection_items()
    {
        $newCollection = $this->collection->reverse();
        $this->assertEquals( 'four', $newCollection[0]);
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

    /** @test */
    public function it_can_covert_collection_to_array()
    {
        $collection = $this->collection->toArray();
        $this->assertIsArray($collection);
    }

    /** @test */
    public function it_can_covert_collection_to_json()
    {
        $collection = $this->collection->toJson();
        $this->assertJson($collection);
    }
}