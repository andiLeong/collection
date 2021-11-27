<?php

namespace Tests\helper;

use Tests\CollectionTestCase;

class ArrayGetHelperTest extends CollectionTestCase
{

    public array $arrayGet;
    public $arrayGetHelperCollection;

    public function setUp(): void
    {
        parent::setUp();

        $this->arrayGet = [
            'name' => 'andi',
            'kids' => [
                ['name' => 'sing'],
                ['name' => 'unknown'],
            ],
        ];

        $this->arrayGetHelperCollection = collection($this->arrayGet);
    }

    /** @test */
    public function it_can_get_an_array_item()
    {
        $value1 = arr_get($this->arrayGet, 'name');
        $this->assertEquals('andi', $value1);
    }

    /** @test */
    public function it_can_get_an_array_item_from_a_ArrayAccess_object()
    {
        $value2 = arr_get($this->arrayGetHelperCollection, 'name');
        $this->assertEquals('andi', $value2);
    }

    /** @test */
    public function it_can_get_a_default_value_if_an_array_item_is_not_found()
    {
        $value3 = arr_get($this->arrayGetHelperCollection, 'foo', $this->default);
        $this->assertEquals($this->default, $value3);
    }

    /** @test */
    public function it_can_get_an_array_item_by_using_dot_notation()
    {
        $value4 = arr_get($this->arrayGetHelperCollection, 'kids.0.name');
        $this->assertEquals('sing', $value4);
    }

    /** @test */
    public function it_will_fall_back_to_default_if_cant_find_dot_notation_item()
    {
        $value5 = arr_get($this->arrayGetHelperCollection, 'kids.0.name2222', $this->default);
        $this->assertEquals($this->default, $value5);
    }

}