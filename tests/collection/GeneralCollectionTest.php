<?php
namespace Tests\collection;

use Andileong\Collection\Collection;
use InvalidArgumentException;

class GeneralCollectionTest extends CollectionTestCase
{

    public function setUp() :void
    {
        parent::setUp();
    }


    /** @test */
    public function it_can_instantiate_collection_by_utilize_global_helpers()
    {
        $collection = collection([]);
        $this->assertInstanceOf(Collection::class , $collection);
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
        $newCollection = $this->collection->filter(fn($value) => $value != 'one')->values();
        $newCollection2 = $this->collection->filter(fn($value,$key) => $key > 2)->values();

        $this->assertEquals('two',$newCollection->first());
        $this->assertEquals('four',$newCollection2->first());
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
    public function it_throw_exception_if_remove_a_collection_key_is_not_exist()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->associateCollection->remove('nameeeeeeee');
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

    /** @test */
    public function it_can_reduce_a_collection_to_a_single_value()
    {
        $collection = $this->numberCollection->reduce(fn($carry,$item) => $carry += $item , 0);
        $this->assertEquals( 15 , $collection);
    }

    /** @test */
    public function it_can_implode_the_collection_to_a_string()
    {
        $collection = $this->collection->implode(',');
        $this->assertEquals( 'one,two,three,four' , $collection);
    }

    /** @test */
    public function it_can_check_if_a_collection_contains_a_value()
    {
        $result = $this->associateCollection->contains('Desk');
        $result2 = $this->associateCollection->contains('Desk2');
        $result3 = $this->associateCollection->contains(function ($item,$key){
            return $key == 'price222';
        });

        $this->assertTrue($result);
        $this->assertFalse($result2);
        $this->assertFalse($result3);
    }

    /** @test */
    public function it_can_transpose_the_collection()
    {
        $data = [
            'name' => ['sherry','sally','sofia'],
            'email' => ['sherry@example.com','sally@example.com','sofia@example.com'],
            'salary' => ['1000','3000','10000']
        ];
        $lookup = ['sherry','sherry@example.com','1000'];

        $collection = collection($data)->transpose();

        $this->assertEquals($lookup,$collection->first());
    }


    /** @test */
    public function it_throw_exception_if_key_by_key_not_found_from_the_collection()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->multiDimensionalCollection->keyBy('id22');
    }

    /** @test */
    public function it_can_key_by_from_the_collection()
    {
       $collection = $this->multiDimensionalCollection->keyBy('id');
        $this->assertEquals([99,100],$collection->keys()->all());
    }

    /** @test */
    public function it_can_trigger_callback_if_collection_is_empty()
    {
        $collection = $this->numberCollection
            ->filter(fn($item) => $item > 100)
            ->ifEmpty(fn($items) => $items->push(100));

        $this->assertEquals(100,$collection->first());
    }

    /** @test */
    public function it_can_trigger_callback_if_collection_is_not_empty()
    {
        $collection = $this->numberCollection
            ->filter(fn($item) => $item > 3)
            ->ifEmpty(fn($items) => $items->push(100))
            ->ifNotEmpty(fn($items) => $items->push(99));

        $this->assertEquals(99,$collection->last());
    }

    /** @test */
    public function it_can_zip_collection_with_an_array()
    {

        $first = ['Chair', 'Desk'];
        $second = [100, 200];
        $collection = collection($first)->zip($second);

        $this->assertEquals(['Chair',100],$collection->first());
        $this->assertEquals(['Desk',200],$collection->second());
    }

    /** @test */
    public function it_can_use_when_method_to_conditionally_trigger_callback()
    {
        $collection = $this->numberCollection
            ->when(true,fn($collection) => $collection->push(100))
            ->when(false,fn($collection) => $collection->push(101));;

        $this->assertEquals(100,$collection->last());
        $this->assertNotEquals(101,$collection->last());
    }


    /** @test */
    public function it_can_parse_values_to_a_collection_instance()
    {
        $collection = Collection::parse('one','two');
        $this->assertInstanceOf(Collection::class,$collection);
    }

    /** @test */
    public function it_can_pop_items_from_the_end_of_collection()
    {
        $emptyCollection = collection([])->pop();
        $collection = $this->numberCollection;
        $pop = $collection->pop();
        $lastTwo = $this->collection->pop(2);

        $this->assertEquals(5,$pop);
        $this->assertEquals([1,2,3,4],$collection->toArray());
        $this->assertEquals(0,$emptyCollection);
        $this->assertEquals(['three','four'],$lastTwo->toArray());
    }

    /** @test */
    public function it_throw_exception_if_try_to_pop_items_amount_bigger_than_collection_count()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->collection->pop(2000);
    }

    /** @test */
    public function it_can_prepend_items_to_the_collection()
    {
        $collection = $this->collection->prepend('zero','-1');
        $this->assertEquals('zero',$collection->first());
    }

    /** @test */
    public function it_can_explode_to_a_collection()
    {
        $string = "hello,gorgeous,are,you,free,tonight";
        $collection = Collection::explode(',',$string);

        $this->assertInstanceOf(Collection::class,$collection);
        $this->assertEquals('hello',$collection->first());
    }

    /** @test */
    public function it_can_use_each_loop_from_a_collection()
    {
        $temp = 0;
        $this->numberCollection->each( function($item, $key) use(&$temp){
             $temp += $item;
        });
        $this->assertEquals(15,$temp);
    }

    /** @test */
    public function it_can_check_if_collection_contains_duplicates_values()
    {
        $duplication = collection(['a', 'b', 'a', 'c', 'b'])->hasDuplicates();
        $duplication2 = collection(['a', 'b'])->hasDuplicates();
//        $duplication2 = collection([
//            ['email' => 'abigail@example.com', 'position' => 'Developer'],
//            ['email' => 'james@example.com', 'position' => 'Designer'],
//            ['email' => 'victoria@example.com', 'position' => 'Developer'],
//        ])->duplicates('position')->toArray();

        $this->assertTrue($duplication);
        $this->assertFalse($duplication2);


    }

    /** @test */
    public function it_can_get_the_duplicate_values_from_a_collection()
    {
        $duplication = collection(['a', 'b', 'a', 'c', 'b','d','c','dd'])->duplicates()->toArray();
        $duplication2 = collection([
            ['email' => 'abigail@example.com', 'position' => 'Developer'],
            ['email' => 'james@example.com', 'position' => 'Designer'],
            ['email' => 'victoria@example.com', 'position' => 'Developer'],
        ])->duplicates('position')->toArray();

        $this->assertEquals(['a', 'b','c'],$duplication);
        $this->assertEquals(['Developer'],$duplication2);

    }

    /** @test */
    public function it_can_get_eachcons_from_collection()
    {
        $arr = [1,2,3,4,5,6,7];
        $length = 3;
        $collection = collection($arr)->eachCons($length)->toArray();
        $collection2 = collection($arr)->eachCons(2)->toArray();

        $expected = [
            [1,2,3],
            [2,3,4],
            [3,4,5],
            [4,5,6],
            [5,6,7],
        ];

        $expected2 = [
            [1,2],
            [2,3],
            [3,4],
            [4,5],
            [5,6],
            [6,7],
        ];


        $this->assertEquals($expected,$collection);
        $this->assertEquals($expected2,$collection2);
        foreach ($collection as $index => $value){
            $this->assertEquals($length,count($value));
        }
    }

    // prepend => unshift , pop => array_pop


}